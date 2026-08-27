<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = $user->canViewRawFindings()
            ? AuditLog::with(['user', 'document'])
            : AuditLog::with(['user', 'document'])->where('user_id', $user->id);

        $logs = $query->latest()->paginate(25);

        return view('audit-logs.index', compact('logs'));
    }
}
