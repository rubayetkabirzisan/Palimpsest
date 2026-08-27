<x-app-layout>
    @section('title', 'Edit Rule')

    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('custom-rules.index') }}" class="text-slate-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-white">Edit Rule: {{ $rule->name }}</h1>
                <p class="text-sm text-slate-400 mt-1">Update this detection rule</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-2xl p-6">
            <form action="{{ route('custom-rules.update', $rule) }}" method="POST" id="edit-rule-form">
                @csrf
                @method('PUT')

                <div class="space-y-5">
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-300 mb-1.5">Rule Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $rule->name) }}" class="w-full bg-slate-900/50 border border-slate-600 text-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500 transition-colors" required>
                        @error('name') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-300 mb-1.5">Description</label>
                        <input type="text" name="description" id="description" value="{{ old('description', $rule->description) }}" class="w-full bg-slate-900/50 border border-slate-600 text-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500 transition-colors">
                        @error('description') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="prompt_instruction" class="block text-sm font-medium text-slate-300 mb-1.5">Prompt Instruction</label>
                        <textarea name="prompt_instruction" id="prompt_instruction" rows="4" class="w-full bg-slate-900/50 border border-slate-600 text-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500 transition-colors font-mono" required>{{ old('prompt_instruction', $rule->prompt_instruction) }}</textarea>
                        @error('prompt_instruction') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ $rule->is_active ? 'checked' : '' }} class="rounded bg-slate-900/50 border-slate-600 text-violet-500 focus:ring-violet-500/50">
                        <label for="is_active" class="text-sm text-slate-300">Active</label>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <a href="{{ route('custom-rules.index') }}" class="px-4 py-2.5 text-sm font-medium text-slate-400 hover:text-white transition-colors">Cancel</a>
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-violet-600 to-cyan-600 hover:from-violet-500 hover:to-cyan-500 text-white text-sm font-medium rounded-xl shadow-lg shadow-violet-500/20 hover:shadow-violet-500/40 transition-all">
                        Update Rule
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
