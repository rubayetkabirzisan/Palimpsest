<?php

namespace App\Http\Controllers;

use App\Jobs\ScanDocumentJob;
use App\Models\AuditLog;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display a listing of documents.
     */
    public function index()
    {
        $documents = Document::where('user_id', Auth::id())
            ->withCount('findings')
            ->latest()
            ->paginate(15);

        return view('documents.index', compact('documents'));
    }

    /**
     * Show the upload form.
     */
    public function create()
    {
        return view('documents.create');
    }

    /**
     * Store a newly uploaded document.
     */
    public function store(Request $request)
    {
        $request->validate([
            'document' => 'required|file|max:10240|mimes:txt,csv,json,xml,html,md,pdf,png,jpg,jpeg,gif,webp',
        ]);

        $file = $request->file('document');
        $path = $file->store('documents', 'local');

        $document = Document::create([
            'user_id' => Auth::id(),
            'original_filename' => $file->getClientOriginalName(),
            'storage_path' => $path,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'status' => 'pending',
        ]);

        // Log the upload
        AuditLog::log(Auth::id(), 'document_uploaded', $document->id, [
            'filename' => $document->original_filename,
            'size' => $document->file_size,
        ]);

        // Dispatch the scan job to the queue
        ScanDocumentJob::dispatch($document);

        return redirect()->route('documents.index')
            ->with('success', 'Document uploaded and queued for scanning.');
    }

    /**
     * Display a single document with findings.
     */
    public function show(Document $document)
    {
        // Ensure user owns the document or is admin/compliance
        if ($document->user_id !== Auth::id() && !Auth::user()->canViewRawFindings()) {
            abort(403);
        }

        $document->load('findings');

        // Log the view
        AuditLog::log(Auth::id(), 'document_viewed', $document->id);

        $canViewRaw = Gate::allows('view-raw-findings');

        // Build content with redactions for users who can't see raw findings
        $content = null;
        $filePath = Storage::disk('local')->path($document->storage_path);

        if (file_exists($filePath) && $this->isTextFile($document->mime_type)) {
            $content = file_get_contents($filePath);

            if (!$canViewRaw) {
                // Apply redactions — replace sensitive snippets with redacted markers
                foreach ($document->findings->sortByDesc('position') as $finding) {
                    $content = str_replace(
                        $finding->snippet,
                        $finding->redactedSnippet(),
                        $content
                    );
                }
            }
        }

        return view('documents.show', compact('document', 'canViewRaw', 'content'));
    }

    /**
     * Return the status of a document (for polling/API).
     */
    public function status(Document $document)
    {
        if ($document->user_id !== Auth::id() && !Auth::user()->canViewRawFindings()) {
            abort(403);
        }

        return response()->json([
            'id' => $document->id,
            'status' => $document->status,
            'findings_count' => $document->findings()->count(),
        ]);
    }

    /**
     * Delete a document.
     */
    public function destroy(Document $document)
    {
        if ($document->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        Storage::disk('local')->delete($document->storage_path);

        AuditLog::log(Auth::id(), 'document_deleted', $document->id, [
            'filename' => $document->original_filename,
        ]);

        $document->delete();

        return redirect()->route('documents.index')
            ->with('success', 'Document deleted successfully.');
    }

    protected function isTextFile(?string $mimeType): bool
    {
        if (!$mimeType) return true;
        return str_starts_with($mimeType, 'text/') || in_array($mimeType, [
            'application/json', 'application/xml', 'application/javascript',
        ]);
    }
}
