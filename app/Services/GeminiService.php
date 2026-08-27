<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\CustomRule;

class GeminiService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key', '');
    }

    /**
     * Analyze document text for sensitive information using Gemini Flash.
     *
     * @return array<int, array{type: string, snippet: string, reason: string, severity: string, source: string}>
     */
    public function analyzeText(string $content): array
    {
        if (empty($this->apiKey)) {
            Log::warning('Gemini API key not configured. Skipping LLM analysis.');
            return [];
        }

        $customRules = CustomRule::activePromptInstructions();

        $prompt = $this->buildPrompt($content, $customRules);

        try {
            $response = Http::timeout(60)->post("{$this->baseUrl}?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'temperature' => 0.1,
                    'responseMimeType' => 'application/json',
                ],
            ]);

            if ($response->failed()) {
                Log::error('Gemini API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return [];
            }

            $data = $response->json();
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';

            return $this->parseFindings($text);
        } catch (\Exception $e) {
            Log::error('Gemini API exception', ['message' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Analyze a file (image/PDF) using Gemini's multimodal capabilities.
     *
     * @return array<int, array{type: string, snippet: string, reason: string, severity: string, source: string}>
     */
    public function analyzeFile(string $filePath, string $mimeType): array
    {
        if (empty($this->apiKey)) {
            Log::warning('Gemini API key not configured. Skipping LLM analysis.');
            return [];
        }

        $customRules = CustomRule::activePromptInstructions();
        $fileData = base64_encode(file_get_contents($filePath));

        $prompt = $this->buildPrompt('', $customRules, true);

        try {
            $response = Http::timeout(90)->post("{$this->baseUrl}?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'inlineData' => [
                                    'mimeType' => $mimeType,
                                    'data' => $fileData,
                                ],
                            ],
                            ['text' => $prompt],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'temperature' => 0.1,
                    'responseMimeType' => 'application/json',
                ],
            ]);

            if ($response->failed()) {
                Log::error('Gemini API multimodal request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return [];
            }

            $data = $response->json();
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';

            return $this->parseFindings($text);
        } catch (\Exception $e) {
            Log::error('Gemini API multimodal exception', ['message' => $e->getMessage()]);
            return [];
        }
    }

    protected function buildPrompt(string $content, string $customRules, bool $isMultimodal = false): string
    {
        $base = <<<PROMPT
You are a Data Loss Prevention (DLP) security analyst. Your job is to analyze the provided document content and identify ALL sensitive, confidential, or personally identifiable information (PII).

Look for the following categories of sensitive data:
1. **Financial data**: Bank account numbers, routing numbers, financial figures in context of mergers/acquisitions/deals
2. **Personal data**: Names with identifying context, dates of birth, addresses, medical information
3. **Business confidential**: Trade secrets, unreleased product names, merger details, pricing strategies, internal codenames
4. **Credentials**: Passwords, tokens, keys, connection strings
5. **Legal/Compliance**: Legal hold notices, attorney-client privileged content, regulatory findings
{$customRules}

For each finding, respond with a JSON array of objects. Each object must have:
- "type": category of the sensitive data (e.g., "financial_data", "pii", "business_confidential", "credentials", "legal")
- "snippet": the exact text that is sensitive (keep it short, max 200 chars)
- "reason": why this is sensitive (1 sentence)
- "severity": "low", "medium", or "high"

If no sensitive data is found, return an empty array: []

IMPORTANT: Only return the JSON array. No other text.
PROMPT;

        if ($isMultimodal) {
            $base .= "\n\nAnalyze the attached document/image for sensitive information.";
        } else {
            $base .= "\n\nDocument content to analyze:\n---\n{$content}\n---";
        }

        return $base;
    }

    /**
     * Parse the JSON response from Gemini into a structured findings array.
     */
    protected function parseFindings(string $jsonText): array
    {
        $findings = json_decode($jsonText, true);

        if (!is_array($findings)) {
            Log::warning('Failed to parse Gemini response as JSON', ['response' => $jsonText]);
            return [];
        }

        return array_map(function ($finding) {
            return [
                'type' => $finding['type'] ?? 'unknown',
                'snippet' => $finding['snippet'] ?? '',
                'reason' => $finding['reason'] ?? 'Detected by AI analysis',
                'severity' => in_array($finding['severity'] ?? '', ['low', 'medium', 'high']) ? $finding['severity'] : 'medium',
                'source' => 'llm',
            ];
        }, $findings);
    }
}
