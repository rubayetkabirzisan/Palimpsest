<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-violet-600 to-cyan-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:from-violet-500 hover:to-cyan-500 focus:outline-none focus:ring-2 focus:ring-violet-500/50 focus:ring-offset-2 focus:ring-offset-slate-800 shadow-lg shadow-violet-500/20 hover:shadow-violet-500/40 transition-all ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
