@extends('layouts.app')

@section('title', 'More')

@section('content')
<div class="max-w-2xl mx-auto">
    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-app-primary">More</h1>
        <p class="text-app-secondary mt-1">Additional features and settings</p>
    </div>
    
    {{-- Main Navigation Items --}}
    <div class="bg-app-elevated rounded-xl border border-app divide-y divide-app overflow-hidden mb-6">
        <a href="{{ route('leaderboard.index') }}" 
           class="flex items-center justify-between p-4 hover:bg-app-secondary transition-colors min-h-[72px]">
            <div class="flex items-center flex-1">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-[var(--color-primary-light)] mr-4">
                    <x-heroicon-o-trophy class="w-6 h-6 text-[var(--color-primary)]"/>
                </div>
                <div>
                    <div class="font-semibold text-app-primary">Leaderboard</div>
                    <div class="text-sm text-app-secondary">View rankings and statistics</div>
                </div>
            </div>
            <x-heroicon-o-chevron-right class="w-6 h-6 text-app-tertiary ml-4"/>
        </a>
        
        <a href="{{ route('scores.index') }}" 
           class="flex items-center justify-between p-4 hover:bg-app-secondary transition-colors min-h-[72px]">
            <div class="flex items-center flex-1">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-[var(--color-warning-bg)] mr-4">
                    <x-heroicon-o-star class="w-6 h-6 text-[var(--color-warning)]"/>
                </div>
                <div>
                    <div class="font-semibold text-app-primary">Direct Scores</div>
                    <div class="text-sm text-app-secondary">Add final positions without a session</div>
                </div>
            </div>
            <x-heroicon-o-chevron-right class="w-6 h-6 text-app-tertiary ml-4"/>
        </a>
    </div>
    
    {{-- Settings & System --}}
    <div class="mb-3">
        <h2 class="text-sm font-semibold text-app-tertiary uppercase tracking-wide px-4">System</h2>
    </div>
    
    <div class="bg-app-elevated rounded-xl border border-app divide-y divide-app overflow-hidden">
        <a href="{{ route('settings.index') }}" 
           class="flex items-center justify-between p-4 hover:bg-app-secondary transition-colors min-h-[72px]">
            <div class="flex items-center flex-1">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-app-secondary mr-4">
                    <x-heroicon-o-cog-6-tooth class="w-6 h-6 text-app-secondary"/>
                </div>
                <div>
                    <div class="font-semibold text-app-primary">Settings</div>
                    <div class="text-sm text-app-secondary">Theme, display, and data</div>
                </div>
            </div>
            <x-heroicon-o-chevron-right class="w-6 h-6 text-app-tertiary ml-4"/>
        </a>
        
        <a href="#" 
           class="flex items-center justify-between p-4 hover:bg-app-secondary transition-colors min-h-[72px]">
            <div class="flex items-center flex-1">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-app-secondary mr-4">
                    <x-heroicon-o-question-mark-circle class="w-6 h-6 text-app-secondary"/>
                </div>
                <div>
                    <div class="font-semibold text-app-primary">Help & Support</div>
                    <div class="text-sm text-app-secondary">Get help using Scorer</div>
                </div>
            </div>
            <x-heroicon-o-chevron-right class="w-6 h-6 text-app-tertiary ml-4"/>
        </a>
        
        <a href="#" 
           class="flex items-center justify-between p-4 hover:bg-app-secondary transition-colors min-h-[72px]">
            <div class="flex items-center flex-1">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-app-secondary mr-4">
                    <x-heroicon-o-information-circle class="w-6 h-6 text-app-secondary"/>
                </div>
                <div>
                    <div class="font-semibold text-app-primary">About Scorer</div>
                    <div class="text-sm text-app-secondary">Version and credits</div>
                </div>
            </div>
            <x-heroicon-o-chevron-right class="w-6 h-6 text-app-tertiary ml-4"/>
        </a>
    </div>
    
    {{-- Quick Theme Toggle --}}
    <div class="mt-8 p-4 bg-app-secondary rounded-xl border border-app">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <x-heroicon-o-light-bulb class="w-5 h-5 text-app-secondary mr-3"/>
                <span class="font-medium text-app-primary">Quick Theme Toggle</span>
            </div>
            <button @click="toggleTheme()" 
                    class="px-4 py-2 rounded-lg bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] text-white font-medium transition-colors flex items-center">
                <x-heroicon-o-sun x-show="!isTheme('dark')" class="w-5 h-5 mr-2"/>
                <x-heroicon-o-moon x-show="isTheme('dark')" class="w-5 h-5 mr-2"/>
                <span x-text="isTheme('dark') ? 'Switch to Light' : 'Switch to Dark'"></span>
            </button>
        </div>
    </div>
</div>
@endsection