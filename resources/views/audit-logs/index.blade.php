<x-app-layout>
    @section('title', 'Audit Log')

    <x-slot name="header">
        <h1 class="text-2xl font-bold text-white">Audit Log</h1>
        <p class="text-sm text-slate-400 mt-1">Complete record of all document activity</p>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-2xl overflow-hidden">
            @if($logs->count() > 0)
                <table class="min-w-full" id="audit-log-table">
                    <thead>
                        <tr class="border-b border-slate-700/50">
                            <th class="px-5 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">User</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Action</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Document</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/30">
                        @foreach($logs as $log)
                            <tr class="hover:bg-slate-700/20 transition-colors">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 bg-gradient-to-br from-violet-500/30 to-cyan-500/30 rounded-full flex items-center justify-center text-xs font-bold text-white">
                                            {{ strtoupper(substr($log->user->name ?? '?', 0, 1)) }}
                                        </div>
                                        <span class="text-sm text-slate-300">{{ $log->user->name ?? 'Unknown' }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3">
                                    @php $action = str_replace('_', ' ', $log->action); @endphp
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full
                                        {{ str_contains($log->action, 'upload') ? 'bg-blue-500/10 text-blue-400 border border-blue-500/20' : '' }}
                                        {{ str_contains($log->action, 'view') ? 'bg-slate-500/10 text-slate-300 border border-slate-500/20' : '' }}
                                        {{ str_contains($log->action, 'scan') ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : '' }}
                                        {{ str_contains($log->action, 'delete') ? 'bg-red-500/10 text-red-400 border border-red-500/20' : '' }}
                                    ">
                                        {{ ucfirst($action) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    @if($log->document)
                                        <a href="{{ route('documents.show', $log->document) }}" class="text-sm text-violet-400 hover:text-violet-300 transition-colors truncate block max-w-[200px]">
                                            {{ $log->document->original_filename }}
                                        </a>
                                    @else
                                        <span class="text-sm text-slate-500">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    <span class="text-sm text-slate-400" title="{{ $log->created_at->format('M d, Y H:i:s') }}">{{ $log->created_at->diffForHumans() }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="px-5 py-3 border-t border-slate-700/50">
                    {{ $logs->links() }}
                </div>
            @else
                <div class="px-5 py-16 text-center">
                    <svg class="w-12 h-12 text-slate-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <p class="text-sm text-slate-500">No audit log entries yet</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
