<?php

namespace App\Jobs;

use App\Models\AuditLog;
use App\Models\Document;
use App\Services\DocumentScanService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ScanDocumentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 120;

    public function __construct(
        public Document $document
    ) {}

    public function handle(DocumentScanService $scanner): void
    {
        $scanner->scan($this->document);

        // Log the scan completion
        AuditLog::log(
            $this->document->user_id,
            'document_scanned',
            $this->document->id,
            [
                'status' => $this->document->fresh()->status,
                'findings_count' => $this->document->findings()->count(),
            ]
        );
    }

    public function failed(\Throwable $exception): void
    {
        $this->document->update(['status' => 'failed']);
    }
}
