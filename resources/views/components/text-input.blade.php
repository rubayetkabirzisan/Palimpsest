@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-slate-900/50 border-slate-600 text-slate-200 focus:border-violet-500 focus:ring-violet-500/50 rounded-xl shadow-sm placeholder-slate-500']) }}>
