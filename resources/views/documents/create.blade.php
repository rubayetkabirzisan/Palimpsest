<x-app-layout>
    @section('title', 'Upload Document')

    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('documents.index') }}" class="text-slate-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-white">Upload Document</h1>
                <p class="text-sm text-slate-400 mt-1">Submit a file for DLP scanning</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-2xl p-6">
            <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" id="upload-form">
                @csrf

                <!-- Drag & Drop Zone -->
                <div class="relative" x-data="{ dragging: false }">
                    <div
                        class="border-2 border-dashed rounded-2xl p-12 text-center transition-all cursor-pointer"
                        :class="dragging ? 'border-violet-500 bg-violet-500/5' : 'border-slate-600 hover:border-slate-500'"
                        @dragover.prevent="dragging = true"
                        @dragleave.prevent="dragging = false"
                        @drop.prevent="dragging = false; $refs.fileInput.files = $event.dataTransfer.files; $refs.fileName.textContent = $event.dataTransfer.files[0]?.name || ''; $refs.fileInfo.classList.remove('hidden')"
                        @click="$refs.fileInput.click()"
                    >
                        <div class="w-16 h-16 bg-gradient-to-br from-violet-500/20 to-cyan-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        </div>
                        <p class="text-sm font-medium text-white mb-1">Drop your file here or click to browse</p>
                        <p class="text-xs text-slate-400">Supports: TXT, CSV, JSON, XML, HTML, MD, PDF, PNG, JPG, GIF, WEBP (max 10MB)</p>

                        <!-- File selected indicator -->
                        <div x-ref="fileInfo" class="hidden mt-4 p-3 bg-slate-700/50 rounded-xl">
                            <p class="text-sm text-emerald-400 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span x-ref="fileName"></span>
                            </p>
                        </div>
                    </div>

                    <input
                        type="file"
                        name="document"
                        x-ref="fileInput"
                        class="hidden"
                        accept=".txt,.csv,.json,.xml,.html,.md,.pdf,.png,.jpg,.jpeg,.gif,.webp"
                        @change="$refs.fileName.textContent = $event.target.files[0]?.name || ''; $refs.fileInfo.classList.remove('hidden')"
                        id="document-input"
                    >
                </div>

                @error('document')
                    <p class="text-sm text-red-400 mt-2">{{ $message }}</p>
                @enderror

                <div class="mt-6 flex items-center justify-end gap-3">
                    <a href="{{ route('documents.index') }}" class="px-4 py-2.5 text-sm font-medium text-slate-400 hover:text-white transition-colors">Cancel</a>
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-violet-600 to-cyan-600 hover:from-violet-500 hover:to-cyan-500 text-white text-sm font-medium rounded-xl shadow-lg shadow-violet-500/20 hover:shadow-violet-500/40 transition-all" id="submit-upload">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Upload & Scan
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Card -->
        <div class="mt-4 bg-slate-800/30 border border-slate-700/30 rounded-2xl p-5">
            <h3 class="text-sm font-medium text-slate-300 mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                How scanning works
            </h3>
            <ol class="space-y-2 text-xs text-slate-400">
                <li class="flex items-start gap-2">
                    <span class="w-5 h-5 bg-violet-500/10 text-violet-400 rounded-full flex items-center justify-center flex-shrink-0 text-[10px] font-bold">1</span>
                    <span><strong class="text-slate-300">Pattern Matching</strong> — Regex scans for credit cards, SSNs, API keys, emails, phone numbers</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="w-5 h-5 bg-cyan-500/10 text-cyan-400 rounded-full flex items-center justify-center flex-shrink-0 text-[10px] font-bold">2</span>
                    <span><strong class="text-slate-300">AI Analysis</strong> — Gemini Flash analyzes context for sensitive info regex can't catch</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="w-5 h-5 bg-emerald-500/10 text-emerald-400 rounded-full flex items-center justify-center flex-shrink-0 text-[10px] font-bold">3</span>
                    <span><strong class="text-slate-300">Results</strong> — Findings are classified by severity and shown with redaction options</span>
                </li>
            </ol>
        </div>
    </div>
</x-app-layout>
