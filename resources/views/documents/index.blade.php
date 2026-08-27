<x-app-layout>
    @section('title', 'Documents')

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">Documents</h1>
                <p class="text-sm text-slate-400 mt-1">Upload and scan documents for sensitive data</p>
            </div>
            <a href="{{ route('documents.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-violet-600 to-cyan-600 hover:from-violet-500 hover:to-cyan-500 text-white text-sm font-medium rounded-xl shadow-lg shadow-violet-500/20 hover:shadow-violet-500/40 transition-all" id="upload-btn">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                Upload Document
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($documents->count() > 0)
            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-2xl overflow-hidden">
                <table class="min-w-full" id="documents-table">
                    <thead>
                        <tr class="border-b border-slate-700/50">
                            <th class="px-5 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Document</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Findings</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Size</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Uploaded</th>
                            <th class="px-5 py-3 text-right text-xs font-medium text-slate-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/30">
                        @foreach($documents as $document)
                            <tr class="hover:bg-slate-700/20 transition-colors" id="document-row-{{ $document->id }}">
                                <td class="px-5 py-4">
                                    <a href="{{ route('documents.show', $document) }}" class="flex items-center gap-3 group">
                                        <div class="w-9 h-9 bg-slate-700/50 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-slate-200 group-hover:text-white truncate transition-colors">{{ $document->original_filename }}</p>
                                            <p class="text-xs text-slate-500">{{ $document->mime_type }}</p>
                                        </div>
                                    </a>
                                </td>
                                <td class="px-5 py-4">
                                    @php $status = $document->status; @endphp
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full
                                        {{ $status === 'complete' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : '' }}
                                        {{ $status === 'scanning' ? 'bg-blue-500/10 text-blue-400 border border-blue-500/20' : '' }}
                                        {{ $status === 'pending' ? 'bg-slate-500/10 text-slate-400 border border-slate-500/20' : '' }}
                                        {{ $status === 'failed' ? 'bg-red-500/10 text-red-400 border border-red-500/20' : '' }}
                                    ">
                                        @if($status === 'scanning')
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                                        @elseif($status === 'complete')
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                        @elseif($status === 'failed')
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                                        @else
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        @endif
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    @if($document->findings_count > 0)
                                        <span class="text-sm font-medium text-red-400">{{ $document->findings_count }}</span>
                                    @else
                                        <span class="text-sm text-slate-500">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <span class="text-sm text-slate-400">{{ number_format($document->file_size / 1024, 1) }} KB</span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="text-sm text-slate-400">{{ $document->created_at->diffForHumans() }}</span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <form action="{{ route('documents.destroy', $document) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this document?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-slate-500 hover:text-red-400 transition-colors" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $documents->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-2xl p-16 text-center">
                <div class="w-16 h-16 bg-slate-700/50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                </div>
                <h3 class="text-lg font-semibold text-white mb-2">No documents yet</h3>
                <p class="text-sm text-slate-400 mb-6 max-w-md mx-auto">Upload your first document to begin scanning for sensitive data. Palimpsest will analyze it using pattern matching and AI to detect PII, credentials, and confidential information.</p>
                <a href="{{ route('documents.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-violet-600 to-cyan-600 hover:from-violet-500 hover:to-cyan-500 text-white text-sm font-medium rounded-xl shadow-lg shadow-violet-500/20 hover:shadow-violet-500/40 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Upload Your First Document
                </a>
            </div>
        @endif
    </div>
</x-app-layout>
