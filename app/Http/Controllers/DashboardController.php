<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Document;
use App\Models\Finding;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isPrivileged = $user->canViewRawFindings();

        // Stats — admin/compliance see all, regular users see their own
        $documentsQuery = $isPrivileged ? Document::query() : Document::where('user_id', $user->id);

        $stats = [
            'total_documents' => (clone $documentsQuery)->count(),
            'documents_scanned' => (clone $documentsQuery)->where('status', 'complete')->count(),
            'documents_pending' => (clone $documentsQuery)->whereIn('status', ['pending', 'scanning'])->count(),
            'documents_failed' => (clone $documentsQuery)->where('status', 'failed')->count(),
        ];

        // Findings by severity
        $findingsQuery = $isPrivileged
            ? Finding::query()
            : Finding::whereHas('document', fn ($q) => $q->where('user_id', $user->id));

        $stats['findings_high'] = (clone $findingsQuery)->where('severity', 'high')->count();
        $stats['findings_medium'] = (clone $findingsQuery)->where('severity', 'medium')->count();
        $stats['findings_low'] = (clone $findingsQuery)->where('severity', 'low')->count();
        $stats['findings_total'] = $stats['findings_high'] + $stats['findings_medium'] + $stats['findings_low'];

        // Findings by source (regex vs llm)
        $stats['findings_regex'] = (clone $findingsQuery)->where('source', 'regex')->count();
        $stats['findings_llm'] = (clone $findingsQuery)->where('source', 'llm')->count();

        // Recent documents
        $recentDocuments = (clone $documentsQuery)
            ->withCount('findings')
            ->latest()
            ->take(5)
            ->get();

        // Recent audit logs
        $auditLogsQuery = $isPrivileged
            ? AuditLog::with(['user', 'document'])
            : AuditLog::with(['user', 'document'])->where('user_id', $user->id);

        $recentActivity = $auditLogsQuery->latest()->take(10)->get();

        return view('dashboard', compact('stats', 'recentDocuments', 'recentActivity'));
    }
}
