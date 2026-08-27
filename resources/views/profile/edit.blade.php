<x-app-layout>
    @section('title', 'Profile')

    <x-slot name="header">
        <h1 class="text-2xl font-bold text-white">Profile</h1>
        <p class="text-sm text-slate-400 mt-1">Manage your account settings</p>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="p-6 bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-2xl">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-6 bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-2xl">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="p-6 bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-2xl">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
