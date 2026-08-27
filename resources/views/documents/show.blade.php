<x-app-layout>
    @section('title', $document->original_filename)

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('documents.index') }}" class="text-slate-400 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-white">{{ $document->original_filename }}</h1>
                    <div class="flex items-center gap-3 mt-1">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-xs font-medium rounded-full
                            {{ $document->status === 'complete' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : '' }}
                            {{ $document->status === 'scanning' ? 'bg-blue-500/10 text-blue-400 border border-blue-500/20' : '' }}
                            {{ $document->status === 'pending' ? 'bg-slate-500/10 text-slate-400 border border-slate-500/20' : '' }}
                            {{ $document->status === 'failed' ? 'bg-red-500/10 text-red-400 border border-red-500/20' : '' }}
                        ">
                            @if($document->status === 'scanning')
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                            @endif
                            {{ ucfirst($document->status) }}
                        </span>
                        <span class="text-xs text-slate-500">{{ number_format($document->file_size / 1024, 1) }} KB</span>
                        <span class="text-xs text-slate-500">{{ $document->created_at->format('M d, Y H:i') }}</span>
                    </div>
                </div>
            </div>

            @if($canViewRaw)
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-500/10 text-amber-400 text-xs font-medium rounded-lg border border-amber-500/20">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Raw View (Unredacted)
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-500/10 text-slate-400 text-xs font-medium rounded-lg border border-slate-500/20">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                    Redacted View
                </span>
            @endif
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Document Content (2/3 width) -->
            <div class="lg:col-span-2">
                <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-2xl overflow-hidden">
                    <div class="px-5 py-3 border-b border-slate-700/50">
                        <h2 class="text-sm font-medium text-slate-300">Document Content</h2>
                    </div>
                    <div class="p-5">
                        @if($content !== null)
                            <pre class="text-sm text-slate-300 whitespace-pre-wrap font-mono leading-relaxed bg-slate-900/50 rounded-xl p-4 max-h-[600px] overflow-y-auto" id="document-content">{!! nl2br(e($content)) !!}</pre>
                        @else
                            <div class="text-center py-12">
                                <svg class="w-12 h-12 text-slate-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <p class="text-sm text-slate-500">Binary or image file — content preview not available.</p>
                                <p class="text-xs text-slate-600 mt-1">AI analysis results are shown in the findings panel.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Findings Panel (1/3 width) -->
            <div>
                <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-2xl overflow-hidden">
                    <div class="px-5 py-3 border-b border-slate-700/50 flex items-center justify-between">
                        <h2 class="text-sm font-medium text-slate-300">Findings</h2>
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-slate-700 text-slate-300">{{ $document->findings->count() }}</span>
                    </div>

                    @if($document->findings->count() > 0)
                        <!-- Severity Summary -->
                        <div class="px-5 py-3 border-b border-slate-700/30 flex items-center gap-3">
                            @php
                                $high = $document->findings->where('severity', 'high')->count();
                                $medium = $document->findings->where('severity', 'medium')->count();
                                $low = $document->findings->where('severity', 'low')->count();
                            @endphp
                            @if($high > 0)
                                <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-red-500/10 text-red-400">{{ $high }} High</span>
                            @endif
                            @if($medium > 0)
                                <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-amber-500/10 text-amber-400">{{ $medium }} Medium</span>
                            @endif
                            @if($low > 0)
                                <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-slate-500/10 text-slate-400">{{ $low }} Low</span>
                            @endif
                        </div>

                        <div class="max-h-[500px] overflow-y-auto divide-y divide-slate-700/20">
                            @foreach($document->findings->sortByDesc(fn ($f) => ['high' => 3, 'medium' => 2, 'low' => 1][$f->severity] ?? 0) as $finding)
                                <div class="px-5 py-3 hover:bg-slate-700/20 transition-colors">
                                    <div class="flex items-center gap-2 mb-1.5">
                                        <span class="w-2 h-2 rounded-full flex-shrink-0
                                            {{ $finding->severity === 'high' ? 'bg-red-400' : '' }}
                                            {{ $finding->severity === 'medium' ? 'bg-amber-400' : '' }}
                                            {{ $finding->severity === 'low' ? 'bg-slate-400' : '' }}
                                        "></span>
                                        <span class="text-xs font-medium uppercase tracking-wider
                                            {{ $finding->severity === 'high' ? 'text-red-400' : '' }}
                                            {{ $finding->severity === 'medium' ? 'text-amber-400' : '' }}
                                            {{ $finding->severity === 'low' ? 'text-slate-400' : '' }}
                                        ">{{ $finding->severity }}</span>
                                        <span class="text-slate-600">•</span>
                                        <span class="text-xs text-slate-500 uppercase">{{ str_replace('_', ' ', $finding->type) }}</span>
                                        <span class="text-slate-600">•</span>
                                        <span class="px-1.5 py-0.5 text-[10px] font-medium rounded
                                            {{ $finding->source === 'llm' ? 'bg-violet-500/10 text-violet-400' : 'bg-cyan-500/10 text-cyan-400' }}
                                        ">{{ $finding->source === 'llm' ? 'AI' : 'Regex' }}</span>
                                    </div>
                                    <p class="text-xs text-slate-400 mb-1">{{ $finding->reason }}</p>
                                    @if($canViewRaw)
                                        <code class="text-xs bg-red-500/5 text-red-300 px-2 py-1 rounded-md border border-red-500/10 block truncate">{{ $finding->snippet }}</code>
                                    @else
                                        <code class="text-xs bg-slate-700/50 text-slate-400 px-2 py-1 rounded-md block">{{ $finding->redactedSnippet() }}</code>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="px-5 py-12 text-center">
                            @if($document->isComplete())
                                <svg class="w-10 h-10 text-emerald-500/50 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                <p class="text-sm text-emerald-400 font-medium">All clear!</p>
                                <p class="text-xs text-slate-500 mt-1">No sensitive data detected</p>
                            @elseif($document->isScanning() || $document->isPending())
                                <svg class="w-10 h-10 text-blue-500/50 mx-auto mb-2 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                <p class="text-sm text-blue-400 font-medium">Scanning in progress...</p>
                                <p class="text-xs text-slate-500 mt-1">Results will appear automatically</p>
                            @else
                                <p class="text-sm text-red-400 font-medium">Scan failed</p>
                                <p class="text-xs text-slate-500 mt-1">Please try re-uploading the document</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($document->isScanning() || $document->isPending())
    <script>
        // Poll for status updates (fallback if Reverb/WebSocket is not connected)
        const pollInterval = setInterval(async () => {
            try {
                const res = await fetch('{{ route("documents.status", $document) }}');
                const data = await res.json();
                if (data.status === 'complete' || data.status === 'failed') {
                    clearInterval(pollInterval);
                    window.location.reload();
                }
            } catch (e) {}
        }, 3000);
    </script>
    @endif
</x-app-layout>
