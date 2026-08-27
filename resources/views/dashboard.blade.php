<x-app-layout>
    @section('title', 'Dashboard')

    <x-slot name="header">
        <h1 class="text-2xl font-bold text-white">Dashboard</h1>
        <p class="text-sm text-slate-400 mt-1">Overview of your document security posture</p>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Total Documents -->
            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-2xl p-5 hover:border-slate-600/50 transition-colors">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-400">Total Documents</p>
                        <p class="text-3xl font-bold text-white mt-1">{{ $stats['total_documents'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-500/10 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                </div>
                <div class="flex items-center gap-2 mt-3">
                    <span class="text-xs text-emerald-400">{{ $stats['documents_scanned'] }} scanned</span>
                    <span class="text-slate-600">•</span>
                    <span class="text-xs text-amber-400">{{ $stats['documents_pending'] }} pending</span>
                </div>
            </div>

            <!-- High Severity Findings -->
            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-2xl p-5 hover:border-red-500/30 transition-colors">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-400">High Severity</p>
                        <p class="text-3xl font-bold text-red-400 mt-1">{{ $stats['findings_high'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-500/10 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                    </div>
                </div>
                <p class="text-xs text-slate-500 mt-3">Immediate attention required</p>
            </div>

            <!-- Medium Severity Findings -->
            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-2xl p-5 hover:border-amber-500/30 transition-colors">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-400">Medium Severity</p>
                        <p class="text-3xl font-bold text-amber-400 mt-1">{{ $stats['findings_medium'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-amber-500/10 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <p class="text-xs text-slate-500 mt-3">Review recommended</p>
            </div>

            <!-- Total Findings -->
            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-2xl p-5 hover:border-slate-600/50 transition-colors">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-400">Total Findings</p>
                        <p class="text-3xl font-bold text-white mt-1">{{ $stats['findings_total'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-violet-500/10 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>
                <div class="flex items-center gap-2 mt-3">
                    <span class="text-xs text-cyan-400">{{ $stats['findings_regex'] }} regex</span>
                    <span class="text-slate-600">•</span>
                    <span class="text-xs text-violet-400">{{ $stats['findings_llm'] }} AI</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Documents -->
            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-700/50 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-white">Recent Documents</h2>
                    <a href="{{ route('documents.index') }}" class="text-sm text-violet-400 hover:text-violet-300 transition-colors">View all →</a>
                </div>
                @forelse ($recentDocuments as $doc)
                    <a href="{{ route('documents.show', $doc) }}" class="block px-5 py-3 hover:bg-slate-700/30 transition-colors border-b border-slate-700/20 last:border-0">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                                    {{ $doc->status === 'complete' ? 'bg-emerald-500/10' : '' }}
                                    {{ $doc->status === 'scanning' ? 'bg-blue-500/10' : '' }}
                                    {{ $doc->status === 'pending' ? 'bg-slate-500/10' : '' }}
                                    {{ $doc->status === 'failed' ? 'bg-red-500/10' : '' }}
                                ">
                                    @if($doc->status === 'complete')
                                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    @elseif($doc->status === 'scanning')
                                        <svg class="w-4 h-4 text-blue-400 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                    @elseif($doc->status === 'failed')
                                        <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    @else
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @endif
                                </div>
                                <span class="text-sm text-slate-200 truncate">{{ $doc->original_filename }}</span>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                @if($doc->findings_count > 0)
                                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-red-500/10 text-red-400 border border-red-500/20">
                                        {{ $doc->findings_count }} {{ Str::plural('finding', $doc->findings_count) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="px-5 py-12 text-center">
                        <svg class="w-12 h-12 text-slate-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p class="text-sm text-slate-500">No documents uploaded yet</p>
                        <a href="{{ route('documents.create') }}" class="inline-flex items-center gap-1 mt-2 text-sm text-violet-400 hover:text-violet-300">Upload your first document →</a>
                    </div>
                @endforelse
            </div>

            <!-- Recent Activity -->
            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-700/50 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-white">Recent Activity</h2>
                    <a href="{{ route('audit-logs.index') }}" class="text-sm text-violet-400 hover:text-violet-300 transition-colors">View all →</a>
                </div>
                @forelse ($recentActivity as $log)
                    <div class="px-5 py-3 border-b border-slate-700/20 last:border-0">
                        <div class="flex items-start gap-3">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center mt-0.5 flex-shrink-0
                                {{ str_contains($log->action, 'upload') ? 'bg-blue-500/10' : '' }}
                                {{ str_contains($log->action, 'view') ? 'bg-slate-500/10' : '' }}
                                {{ str_contains($log->action, 'scan') ? 'bg-emerald-500/10' : '' }}
                                {{ str_contains($log->action, 'delete') ? 'bg-red-500/10' : '' }}
                            ">
                                @if(str_contains($log->action, 'upload'))
                                    <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                @elseif(str_contains($log->action, 'scan'))
                                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @elseif(str_contains($log->action, 'delete'))
                                    <svg class="w-3.5 h-3.5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                @else
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm text-slate-300">
                                    <span class="font-medium text-white">{{ $log->user->name }}</span>
                                    <span class="text-slate-400">{{ str_replace('_', ' ', $log->action) }}</span>
                                    @if($log->document)
                                        <span class="text-slate-400">—</span>
                                        <span class="text-slate-200 truncate">{{ $log->document->original_filename }}</span>
                                    @endif
                                </p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $log->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-12 text-center">
                        <svg class="w-12 h-12 text-slate-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <p class="text-sm text-slate-500">No activity recorded yet</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
