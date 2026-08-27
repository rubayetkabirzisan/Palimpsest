<x-app-layout>
    @section('title', 'Create Rule')

    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('custom-rules.index') }}" class="text-slate-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-white">Create Custom Rule</h1>
                <p class="text-sm text-slate-400 mt-1">Define a new detection rule for the AI scanner</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-2xl p-6">
            <form action="{{ route('custom-rules.store') }}" method="POST" id="create-rule-form">
                @csrf

                <div class="space-y-5">
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-300 mb-1.5">Rule Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full bg-slate-900/50 border border-slate-600 text-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500 transition-colors placeholder-slate-500" placeholder="e.g., Project Phoenix Detection" required>
                        @error('name') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-300 mb-1.5">Description <span class="text-slate-500">(optional)</span></label>
                        <input type="text" name="description" id="description" value="{{ old('description') }}" class="w-full bg-slate-900/50 border border-slate-600 text-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500 transition-colors placeholder-slate-500" placeholder="What does this rule detect?">
                        @error('description') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="prompt_instruction" class="block text-sm font-medium text-slate-300 mb-1.5">Prompt Instruction</label>
                        <p class="text-xs text-slate-500 mb-2">This instruction will be injected into the AI prompt. Be specific about what to look for and why it's sensitive.</p>
                        <textarea name="prompt_instruction" id="prompt_instruction" rows="4" class="w-full bg-slate-900/50 border border-slate-600 text-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500 transition-colors placeholder-slate-500 font-mono" placeholder="Flag any mention of 'Project Phoenix' or codename 'Aurora' as these are unannounced product names" required>{{ old('prompt_instruction') }}</textarea>
                        @error('prompt_instruction') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <a href="{{ route('custom-rules.index') }}" class="px-4 py-2.5 text-sm font-medium text-slate-400 hover:text-white transition-colors">Cancel</a>
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-violet-600 to-cyan-600 hover:from-violet-500 hover:to-cyan-500 text-white text-sm font-medium rounded-xl shadow-lg shadow-violet-500/20 hover:shadow-violet-500/40 transition-all">
                        Create Rule
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
