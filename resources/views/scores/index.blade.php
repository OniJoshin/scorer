@extends('layouts.app')

@section('title', 'Direct Scores')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Page Header --}}
    <div class="mb-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-app-primary flex items-center">
                    <x-heroicon-o-clipboard-document-list class="w-8 h-8 mr-2 text-[var(--color-primary)]"/>
                    Direct Scores
                </h1>
                <p class="text-app-secondary mt-1">Add match results directly without running a session</p>
            </div>

            <a href="{{ route('scores.create') }}"
               class="hidden sm:inline-flex items-center px-4 py-2 bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] text-white font-semibold rounded-lg transition-colors whitespace-nowrap">
                <x-heroicon-s-plus class="w-4 h-4 mr-2"/>
                Add Match Results
            </a>
        </div>
        <div class="mt-3 p-3 bg-[var(--color-info-bg)] border border-[var(--color-info)] rounded-lg flex items-start">
            <x-heroicon-o-information-circle class="w-5 h-5 text-[var(--color-info)] mr-2 flex-shrink-0 mt-0.5"/>
            <p class="text-sm text-app-secondary">
                Direct scores are useful when you already know final positions. For in-game tracking and round helpers, use
                <a href="{{ route('game_sessions.index') }}" class="text-[var(--color-primary)] font-semibold hover:underline">Game Sessions</a>.
            </p>
        </div>
    </div>
    
    @if($scores->count())
        {{-- Scores List --}}
        <div class="space-y-2 mb-20">
            @foreach($scores as $score)
                <div class="bg-app-elevated rounded-xl border border-app overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="flex items-center p-4">
                        {{-- Position Badge --}}
                        <div class="w-12 h-12 flex-shrink-0 mr-4">
                            @if($score->position === 1)
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center">
                                    <x-heroicon-s-trophy class="w-6 h-6 text-white"/>
                                </div>
                            @elseif($score->position === 2)
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center">
                                    <x-heroicon-s-circle-stack class="w-5 h-5 text-white"/>
                                </div>
                            @elseif($score->position === 3)
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-600 to-orange-800 flex items-center justify-center">
                                    <x-heroicon-s-star class="w-5 h-5 text-white"/>
                                </div>
                            @else
                                <div class="w-12 h-12 rounded-full bg-app-secondary flex items-center justify-center">
                                    <span class="text-lg font-bold text-app-secondary">{{ $score->position }}</span>
                                </div>
                            @endif
                        </div>
                        
                        {{-- Score Info --}}
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-app-primary truncate">{{ $score->player->name }}</h3>
                            <p class="text-sm text-app-secondary flex items-center">
                                <x-heroicon-o-puzzle-piece class="w-4 h-4 mr-1"/>
                                {{ $score->game->name }}
                            </p>
                            @if($score->played_at)
                                <p class="text-xs text-app-tertiary flex items-center mt-0.5">
                                    <x-heroicon-o-calendar class="w-3 h-3 mr-1"/>
                                    {{ $score->played_at->format('M j, Y') }}
                                </p>
                            @endif
                        </div>
                        
                        {{-- Points --}}
                        <div class="text-right mr-4">
                            <div class="text-2xl font-bold text-[var(--color-primary)]">{{ $score->points ?? '-' }}</div>
                            <div class="text-xs text-app-secondary">points</div>
                        </div>
                        
                        {{-- Actions --}}
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('scores.edit', $score) }}" 
                               class="p-2 text-[var(--color-primary)] hover:bg-app-secondary rounded-lg transition-colors"
                               aria-label="Edit score">
                                <x-heroicon-o-pencil-square class="w-5 h-5"/>
                            </a>
                            <form action="{{ route('scores.destroy', $score) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Delete this score? This cannot be undone.')"
                                        class="p-2 text-[var(--color-error)] hover:bg-[var(--color-error-bg)] rounded-lg transition-colors"
                                        aria-label="Delete score">
                                    <x-heroicon-o-trash class="w-5 h-5"/>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- Empty State --}}
        <div class="bg-app-elevated rounded-xl p-12 border border-app border-dashed text-center">
            <x-heroicon-o-clipboard-document-list class="w-20 h-20 mx-auto text-app-tertiary mb-4"/>
            <h3 class="text-xl font-semibold text-app-primary mb-2">No scores recorded</h3>
            <p class="text-app-secondary mb-6 max-w-md mx-auto">Start tracking your game outcomes with direct position-based scoring.</p>
            <a href="{{ route('scores.create') }}" 
               class="inline-flex items-center px-6 py-3 bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] text-white font-semibold rounded-lg transition-colors">
                <x-heroicon-s-plus class="w-5 h-5 mr-2"/>
                Add First Match
            </a>
        </div>
    @endif
    
    {{-- Floating Action Button --}}
    <a href="{{ route('scores.create') }}" 
       class="sm:hidden fixed bottom-24 right-6 w-14 h-14 bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] rounded-full shadow-lg flex items-center justify-center transition-all hover:scale-110"
       aria-label="Add match results">
        <x-heroicon-s-plus class="w-6 h-6 text-white"/>
    </a>
</div>
@endsection
