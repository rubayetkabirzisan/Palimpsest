<?php

namespace App\Services;

use App\Models\Document;
use App\Models\Finding;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DocumentScanService
{
    public function __construct(
        protected RegexDetector $regexDetector,
        protected GeminiService $geminiService,
    ) {}

    /**
     * Run the full scanning pipeline on a document: regex pass + LLM pass.
     */
    public function scan(Document $document): void
    {
        $document->update(['status' => 'scanning']);

        try {
            $filePath = Storage::disk('local')->path($document->storage_path);

            if (!file_exists($filePath)) {
                Log::error("Document file not found: {$filePath}");
                $document->update(['status' => 'failed']);
                return;
            }

            $isTextBased = $this->isTextBasedFile($document->mime_type);

            // ── Regex pass (text files only) ──────────────────────────
            if ($isTextBased) {
                $content = file_get_contents($filePath);
                $regexFindings = $this->regexDetector->scan($content);

                foreach ($regexFindings as $finding) {
                    Finding::create([
                        'document_id' => $document->id,
                        ...$finding,
                    ]);
                }
            }

            // ── LLM pass ─────────────────────────────────────────────
            if ($isTextBased) {
                $content = $content ?? file_get_contents($filePath);
                // Truncate very long documents for the LLM
                $truncated = mb_substr($content, 0, 30000);
                $llmFindings = $this->geminiService->analyzeText($truncated);
            } else {
                // Multimodal: send the file directly to Gemini
                $llmFindings = $this->geminiService->analyzeFile($filePath, $document->mime_type);
            }

            foreach ($llmFindings as $finding) {
                // Avoid duplicates — check if snippet already found by regex
                $exists = Finding::where('document_id', $document->id)
                    ->where('snippet', $finding['snippet'])
                    ->exists();

                if (!$exists) {
                    Finding::create([
                        'document_id' => $document->id,
                        ...$finding,
                    ]);
                }
            }

            $document->update(['status' => 'complete']);
        } catch (\Exception $e) {
            Log::error('Document scan failed', [
                'document_id' => $document->id,
                'error' => $e->getMessage(),
            ]);
            $document->update(['status' => 'failed']);
        }
    }

    protected function isTextBasedFile(?string $mimeType): bool
    {
        if (!$mimeType) {
            return true;
        }

        $textMimes = [
            'text/plain',
            'text/csv',
            'text/html',
            'text/markdown',
            'application/json',
            'application/xml',
            'text/xml',
            'application/javascript',
        ];

        return in_array($mimeType, $textMimes) || str_starts_with($mimeType, 'text/');
    }
}
