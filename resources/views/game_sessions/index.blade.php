@extends('layouts.app')

@section('title', 'Sessions')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-app-primary">Game Sessions</h1>
        <p class="text-app-secondary mt-1">Track scores for each game played</p>
    </div>
    
    @if($sessions->isEmpty())
        {{-- Empty State --}}
        <div class="bg-app-elevated rounded-xl p-12 border border-app border-dashed text-center">
            <x-heroicon-o-puzzle-piece class="w-20 h-20 mx-auto text-app-tertiary mb-4"/>
            <h3 class="text-xl font-semibold text-app-primary mb-2">No sessions yet</h3>
            <p class="text-app-secondary mb-6 max-w-md mx-auto">Start your first game session to begin tracking scores and competing with friends!</p>
            <a href="{{ route('game_sessions.create') }}" 
               class="inline-flex items-center px-6 py-3 bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] text-white font-semibold rounded-lg transition-colors">
                <x-heroicon-s-plus class="w-5 h-5 mr-2"/>
                Start First Session
            </a>
        </div>
    @else
        {{-- Sessions List --}}
        <div class="space-y-3 mb-20">
            @foreach($sessions as $session)
                <a href="{{ route('game_sessions.show', $session) }}" 
                   class="flex items-center justify-between p-4 bg-app-elevated hover:bg-app-secondary border border-app rounded-xl transition-colors">
                    <div class="flex-1">
                        <div class="flex items-center mb-2">
                            <x-heroicon-o-cube class="w-5 h-5 text-[var(--color-primary)] mr-2"/>
                            <h3 class="font-semibold text-app-primary">{{ $session->game->name }}</h3>
                        </div>
                        <div class="flex items-center text-sm text-app-secondary space-x-3">
                            <div class="flex items-center">
                                <x-heroicon-o-user-group class="w-4 h-4 mr-1"/>
                                <span>{{ $session->results->count() }} players</span>
                            </div>
                            <div class="flex items-center">
                                <x-heroicon-o-calendar class="w-4 h-4 mr-1"/>
                                <span>{{ $session->started_at ? $session->started_at->format('M j, Y') : $session->created_at->format('M j, Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="ml-4 flex items-center space-x-3">
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
                        <x-heroicon-o-chevron-right class="w-5 h-5 text-app-tertiary"/>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
    
    {{-- Floating Action Button --}}
    <a href="{{ route('game_sessions.create') }}" 
       class="md:hidden fixed bottom-24 right-6 w-14 h-14 bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] rounded-full shadow-lg flex items-center justify-center transition-all hover:scale-110"
       aria-label="New session">
        <x-heroicon-s-plus class="w-6 h-6 text-white"/>
    </a>
</div>
@endsection
