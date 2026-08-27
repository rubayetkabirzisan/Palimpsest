<nav x-data="{ open: false }" class="bg-slate-900/80 backdrop-blur-xl border-b border-slate-700/50 sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
                        <div class="w-8 h-8 bg-gradient-to-br from-violet-500 to-cyan-500 rounded-lg flex items-center justify-center shadow-lg shadow-violet-500/20 group-hover:shadow-violet-500/40 transition-shadow">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <span class="text-white font-bold text-lg tracking-tight">Palimpsest</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-8 sm:flex">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'text-white bg-slate-700/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('documents.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('documents.*') ? 'text-white bg-slate-700/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Documents
                    </a>
                    <a href="{{ route('audit-logs.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('audit-logs.*') ? 'text-white bg-slate-700/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Audit Log
                    </a>
                    @can('manage-rules')
                    <a href="{{ route('custom-rules.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('custom-rules.*') ? 'text-white bg-slate-700/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                        Rules
                    </a>
                    @endcan
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="flex items-center gap-3">
                    <!-- Role Badge -->
                    <span class="px-2 py-0.5 text-xs font-medium rounded-full
                        {{ Auth::user()->role === 'admin' ? 'bg-violet-500/20 text-violet-300 border border-violet-500/30' : '' }}
                        {{ Auth::user()->role === 'compliance' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : '' }}
                        {{ Auth::user()->role === 'user' ? 'bg-slate-500/20 text-slate-300 border border-slate-500/30' : '' }}
                    ">
                        {{ ucfirst(Auth::user()->role) }}
                    </span>

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-slate-700 text-sm leading-4 font-medium rounded-lg text-slate-300 bg-slate-800 hover:text-white hover:border-slate-600 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-white hover:bg-slate-800 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-slate-900 border-t border-slate-700/50">
        <div class="pt-2 pb-3 space-y-1 px-3">
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'text-white bg-slate-700/50' : 'text-slate-400' }}">Dashboard</a>
            <a href="{{ route('documents.index') }}" class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('documents.*') ? 'text-white bg-slate-700/50' : 'text-slate-400' }}">Documents</a>
            <a href="{{ route('audit-logs.index') }}" class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('audit-logs.*') ? 'text-white bg-slate-700/50' : 'text-slate-400' }}">Audit Log</a>
            @can('manage-rules')
            <a href="{{ route('custom-rules.index') }}" class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('custom-rules.*') ? 'text-white bg-slate-700/50' : 'text-slate-400' }}">Rules</a>
            @endcan
        </div>
        <div class="pt-4 pb-1 border-t border-slate-700/50">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-slate-400">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1 px-3">
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-sm text-slate-400 rounded-lg">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-3 py-2 text-sm text-slate-400 rounded-lg">Log Out</a>
                </form>
            </div>
        </div>
    </div>
</nav>
