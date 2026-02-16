<!DOCTYPE html>
@php($initialTheme = in_array(($appTheme ?? 'auto'), ['light', 'dark', 'auto'], true) ? ($appTheme ?? 'auto') : 'auto')
<html lang="en" class="{{ $initialTheme === 'dark' ? 'dark' : '' }}" x-data="themeManager('{{ $initialTheme }}')" x-init="init()" :class="getEffectiveTheme()">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Scorer') - Board Game Tracker</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#7C3AED" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#8B5CF6" media="(prefers-color-scheme: dark)">
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="bg-app-primary text-app-primary font-sans min-h-screen transition-colors duration-200">

    {{-- Desktop Sidebar Navigation (≥ 768px) --}}
    <aside class="hidden md:flex md:fixed md:inset-y-0 md:left-0 md:w-64 md:flex-col bg-app-elevated border-r border-app z-30">
        <div class="flex flex-col flex-1 overflow-y-auto">
            {{-- Logo/Branding --}}
            <div class="flex items-center justify-between px-6 py-5 border-b border-app">
                <div class="flex items-center space-x-3">
                    <x-heroicon-o-cube class="w-8 h-8 text-[var(--color-primary)]"/>
                    <span class="text-xl font-bold text-app-primary">Scorer</span>
                </div>
                {{-- Theme Toggle --}}
                <button @click="toggleTheme()" 
                        class="p-2 rounded-lg hover:bg-app-secondary transition-colors"
                        aria-label="Toggle theme">
                    <x-heroicon-o-sun x-show="!isTheme('dark')" class="w-5 h-5 text-app-secondary"/>
                    <x-heroicon-o-moon x-show="isTheme('dark')" class="w-5 h-5 text-app-secondary"/>
                </button>
            </div>
            
            {{-- Navigation Links --}}
            <nav class="flex-1 px-3 py-4 space-y-1">
                <a href="{{ route('home') }}" 
                   class="flex items-center px-3 py-3 rounded-lg transition-colors {{ request()->routeIs('home') ? 'bg-[var(--color-primary-light)] text-[var(--color-primary)]' : 'text-app-secondary hover:bg-app-secondary' }}">
                    <x-heroicon-o-home class="w-6 h-6 mr-3 {{ request()->routeIs('home') ? 'text-[var(--color-primary)]' : '' }}"/>
                    <span class="font-medium">Home</span>
                </a>
                
                <a href="{{ route('game_sessions.index') }}" 
                   class="flex items-center px-3 py-3 rounded-lg transition-colors {{ request()->routeIs('game_sessions.*') ? 'bg-[var(--color-primary-light)] text-[var(--color-primary)]' : 'text-app-secondary hover:bg-app-secondary' }}">
                    <x-heroicon-o-puzzle-piece class="w-6 h-6 mr-3 {{ request()->routeIs('game_sessions.*') ? 'text-[var(--color-primary)]' : '' }}"/>
                    <span class="font-medium">Sessions</span>
                </a>
                
                <a href="{{ route('games.index') }}" 
                   class="flex items-center px-3 py-3 rounded-lg transition-colors {{ request()->routeIs('games.*') ? 'bg-[var(--color-primary-light)] text-[var(--color-primary)]' : 'text-app-secondary hover:bg-app-secondary' }}">
                    <x-heroicon-o-cube class="w-6 h-6 mr-3 {{ request()->routeIs('games.*') ? 'text-[var(--color-primary)]' : '' }}"/>
                    <span class="font-medium">Games</span>
                </a>
                
                <a href="{{ route('players.index') }}" 
                   class="flex items-center px-3 py-3 rounded-lg transition-colors {{ request()->routeIs('players.*') ? 'bg-[var(--color-primary-light)] text-[var(--color-primary)]' : 'text-app-secondary hover:bg-app-secondary' }}">
                    <x-heroicon-o-user-group class="w-6 h-6 mr-3 {{ request()->routeIs('players.*') ? 'text-[var(--color-primary)]' : '' }}"/>
                    <span class="font-medium">Players</span>
                </a>
                
                <div class="pt-4 mt-4 border-t border-app">
                    <a href="{{ route('leaderboard.index') }}" 
                       class="flex items-center px-3 py-3 rounded-lg transition-colors {{ request()->routeIs('leaderboard.*') ? 'bg-[var(--color-primary-light)] text-[var(--color-primary)]' : 'text-app-secondary hover:bg-app-secondary' }}">
                        <x-heroicon-o-trophy class="w-6 h-6 mr-3 {{ request()->routeIs('leaderboard.*') ? 'text-[var(--color-primary)]' : '' }}"/>
                        <span class="font-medium">Leaderboard</span>
                    </a>
                    
                    <a href="{{ route('scores.index') }}" 
                       class="flex items-center px-3 py-3 rounded-lg transition-colors {{ request()->routeIs('scores.*') ? 'bg-[var(--color-primary-light)] text-[var(--color-primary)]' : 'text-app-secondary hover:bg-app-secondary' }}">
                        <x-heroicon-o-star class="w-6 h-6 mr-3 {{ request()->routeIs('scores.*') ? 'text-[var(--color-primary)]' : '' }}"/>
                        <span class="font-medium">Scores</span>
                    </a>
                    
                    <a href="{{ route('settings.index') }}" 
                       class="flex items-center px-3 py-3 rounded-lg transition-colors {{ request()->routeIs('settings.*') ? 'bg-[var(--color-primary-light)] text-[var(--color-primary)]' : 'text-app-secondary hover:bg-app-secondary' }}">
                        <x-heroicon-o-cog-6-tooth class="w-6 h-6 mr-3 {{ request()->routeIs('settings.*') ? 'text-[var(--color-primary)]' : '' }}"/>
                        <span class="font-medium">Settings</span>
                    </a>
                </div>
            </nav>
        </div>
    </aside>

    {{-- Main Content Area --}}
    <main class="pb-20 md:pb-6 md:pl-64 min-h-screen">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mx-4 mt-4 md:mx-6 bg-[var(--color-success-bg)] text-[var(--color-success)] px-4 py-3 rounded-lg flex items-start">
                <x-heroicon-s-check-circle class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0"/>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        
        @if(session('error'))
            <div class="mx-4 mt-4 md:mx-6 bg-[var(--color-error-bg)] text-[var(--color-error)] px-4 py-3 rounded-lg flex items-start">
                <x-heroicon-s-exclamation-circle class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0"/>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        
        {{-- Page Content --}}
        <div class="px-4 py-6 md:px-6">
            @yield('content')
        </div>
    </main>

    {{-- Mobile Bottom Tab Navigation (< 768px) --}}
    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-app-elevated border-t border-app z-30 safe-area-inset-bottom">
        <div class="flex justify-around items-center h-16">
            {{-- Home Tab --}}
            <a href="{{ route('home') }}" 
               class="flex flex-col items-center justify-center flex-1 h-full space-y-1 transition-colors {{ request()->routeIs('home') ? 'text-[var(--color-primary)]' : 'text-app-tertiary' }}">
                <x-heroicon-{{ request()->routeIs('home') ? 's' : 'o' }}-home class="w-6 h-6"/>
                <span class="text-xs font-medium">Home</span>
            </a>
            
            {{-- Sessions Tab --}}
            <a href="{{ route('game_sessions.index') }}" 
               class="flex flex-col items-center justify-center flex-1 h-full space-y-1 transition-colors {{ request()->routeIs('game_sessions.*') ? 'text-[var(--color-primary)]' : 'text-app-tertiary' }}">
                <x-heroicon-{{ request()->routeIs('game_sessions.*') ? 's' : 'o' }}-puzzle-piece class="w-6 h-6"/>
                <span class="text-xs font-medium">Sessions</span>
            </a>
            
            {{-- Games Tab --}}
            <a href="{{ route('games.index') }}" 
               class="flex flex-col items-center justify-center flex-1 h-full space-y-1 transition-colors {{ request()->routeIs('games.*') ? 'text-[var(--color-primary)]' : 'text-app-tertiary' }}">
                <x-heroicon-{{ request()->routeIs('games.*') ? 's' : 'o' }}-cube class="w-6 h-6"/>
                <span class="text-xs font-medium">Games</span>
            </a>
            
            {{-- Players Tab --}}
            <a href="{{ route('players.index') }}" 
               class="flex flex-col items-center justify-center flex-1 h-full space-y-1 transition-colors {{ request()->routeIs('players.*') ? 'text-[var(--color-primary)]' : 'text-app-tertiary' }}">
                <x-heroicon-{{ request()->routeIs('players.*') ? 's' : 'o' }}-user-group class="w-6 h-6"/>
                <span class="text-xs font-medium">Players</span>
            </a>
            
            {{-- More Tab --}}
            <a href="{{ route('more.index') }}" 
               class="flex flex-col items-center justify-center flex-1 h-full space-y-1 transition-colors {{ request()->routeIs('more.*') || request()->routeIs('leaderboard.*') || request()->routeIs('scores.*') || request()->routeIs('settings.*') ? 'text-[var(--color-primary)]' : 'text-app-tertiary' }}">
                <x-heroicon-{{ request()->routeIs('more.*') || request()->routeIs('leaderboard.*') || request()->routeIs('scores.*') || request()->routeIs('settings.*') ? 's' : 'o' }}-ellipsis-horizontal-circle class="w-6 h-6"/>
                <span class="text-xs font-medium">More</span>
            </a>
        </div>
    </nav>

</body>
</html>


