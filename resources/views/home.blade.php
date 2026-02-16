@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="max-w-6xl mx-auto">
    {{-- Welcome Header --}}
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-app-primary mb-2">Welcome to Scorer</h1>
        <p class="text-lg text-app-secondary">Track scores and settle who's the champion!</p>
    </div>
    
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        {{-- Total Sessions --}}
        <div class="bg-app-elevated rounded-xl p-6 border border-app">
            <div class="flex items-center justify-between mb-2">
                <x-heroicon-o-puzzle-piece class="w-8 h-8 text-[var(--color-primary)]"/>
                <span class="text-3xl font-bold text-app-primary">{{ $totalSessions }}</span>
            </div>
            <p class="text-sm font-medium text-app-secondary">Game Sessions</p>
        </div>
        
        {{-- Total Games --}}
        <div class="bg-app-elevated rounded-xl p-6 border border-app">
            <div class="flex items-center justify-between mb-2">
                <x-heroicon-o-cube class="w-8 h-8 text-[var(--color-info)]"/>
                <span class="text-3xl font-bold text-app-primary">{{ $totalGames }}</span>
            </div>
            <p class="text-sm font-medium text-app-secondary">Games Library</p>
        </div>
        
        {{-- Total Players --}}
        <div class="bg-app-elevated rounded-xl p-6 border border-app">
            <div class="flex items-center justify-between mb-2">
                <x-heroicon-o-user-group class="w-8 h-8 text-[var(--color-success)]"/>
                <span class="text-3xl font-bold text-app-primary">{{ $totalPlayers }}</span>
            </div>
            <p class="text-sm font-medium text-app-secondary">Active Players</p>
        </div>
    </div>
    
    {{-- Quick Actions --}}
    <div class="mb-8">
        <h2 class="text-xl font-bold text-app-primary mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <a href="{{ route('game_sessions.create') }}" 
               class="flex items-center p-4 bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] text-white rounded-xl transition-colors">
                <x-heroicon-s-plus-circle class="w-8 h-8 mr-3"/>
                <div>
                    <div class="font-semibold">New Session</div>
                    <div class="text-sm opacity-90">Start playing</div>
                </div>
            </a>
            
            <a href="{{ route('games.create') }}" 
               class="flex items-center p-4 bg-app-elevated hover:bg-app-secondary border border-app rounded-xl transition-colors">
                <x-heroicon-s-plus-circle class="w-8 h-8 mr-3 text-[var(--color-info)]"/>
                <div>
                    <div class="font-semibold text-app-primary">Add Game</div>
                    <div class="text-sm text-app-secondary">To library</div>
                </div>
            </a>
            
            <a href="{{ route('players.create') }}" 
               class="flex items-center p-4 bg-app-elevated hover:bg-app-secondary border border-app rounded-xl transition-colors">
                <x-heroicon-s-plus-circle class="w-8 h-8 mr-3 text-[var(--color-success)]"/>
                <div>
                    <div class="font-semibold text-app-primary">Add Player</div>
                    <div class="text-sm text-app-secondary">New competitor</div>
                </div>
            </a>
        </div>
    </div>
    
    {{-- Insights Section --}}
    @if($mostPlayedGame || $topPlayer)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        {{-- Most Played Game --}}
        @if($mostPlayedGame)
        <div class="bg-app-elevated rounded-xl p-6 border border-app">
            <div class="flex items-center mb-3">
                <x-heroicon-o-fire class="w-6 h-6 text-[var(--color-warning)] mr-2"/>
                <h3 class="font-semibold text-app-primary">Most Played</h3>
            </div>
            <p class="text-2xl font-bold text-app-primary">{{ $mostPlayedGame->game->name }}</p>
            <p class="text-sm text-app-secondary mt-1">{{ $mostPlayedGame->play_count }} {{ $mostPlayedGame->play_count === 1 ? 'session' : 'sessions' }}</p>
        </div>
        @endif
        
        {{-- Top Player --}}
        @if($topPlayer)
        <div class="bg-app-elevated rounded-xl p-6 border border-app">
            <div class="flex items-center mb-3">
                <x-heroicon-o-trophy class="w-6 h-6 text-[var(--color-warning)] mr-2"/>
                <h3 class="font-semibold text-app-primary">Most Active</h3>
            </div>
            <p class="text-2xl font-bold text-app-primary">{{ $topPlayer->name }}</p>
            <p class="text-sm text-app-secondary mt-1">{{ $topPlayer->game_session_results_count }} {{ $topPlayer->game_session_results_count === 1 ? 'game' : 'games' }} played</p>
        </div>
        @endif
    </div>
    @endif
    
    {{-- Recent Sessions --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-app-primary">Recent Sessions</h2>
            <a href="{{ route('game_sessions.index') }}" 
               class="text-[var(--color-primary)] hover:underline font-medium text-sm flex items-center">
                View All
                <x-heroicon-o-chevron-right class="w-4 h-4 ml-1"/>
            </a>
        </div>
        
        @if($recentSessions->count() > 0)
        <div class="space-y-3">
            @foreach($recentSessions as $session)
            <a href="{{ route('game_sessions.show', $session) }}" 
               class="flex items-center justify-between p-4 bg-app-elevated hover:bg-app-secondary border border-app rounded-xl transition-colors">
                <div class="flex-1">
                    <div class="font-semibold text-app-primary mb-1">{{ $session->game->name }}</div>
                    <div class="flex items-center text-sm text-app-secondary space-x-3">
                        <div class="flex items-center">
                            <x-heroicon-o-user-group class="w-4 h-4 mr-1"/>
                            <span>{{ $session->results->count() }} players</span>
                        </div>
                        <div class="flex items-center">
                            <x-heroicon-o-calendar class="w-4 h-4 mr-1"/>
                            <span>{{ $session->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                <div class="ml-4">
                    @if($session->completed_at)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[var(--color-success-bg)] text-[var(--color-success)]">
                            <x-heroicon-s-check-circle class="w-4 h-4 mr-1"/>
                            Complete
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[var(--color-warning-bg)] text-[var(--color-warning)]">
                            <x-heroicon-s-clock class="w-4 h-4 mr-1"/>
                            In Progress
                        </span>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
        @else
        {{-- Empty State --}}
        <div class="bg-app-elevated rounded-xl p-12 border border-app border-dashed text-center">
            <x-heroicon-o-puzzle-piece class="w-16 h-16 mx-auto text-app-tertiary mb-4"/>
            <h3 class="text-lg font-semibold text-app-primary mb-2">No sessions yet</h3>
            <p class="text-app-secondary mb-6">Start a new game session to begin tracking scores!</p>
            <a href="{{ route('game_sessions.create') }}" 
               class="inline-flex items-center px-6 py-3 bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] text-white font-semibold rounded-lg transition-colors">
                <x-heroicon-s-plus class="w-5 h-5 mr-2"/>
                Start First Session
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
