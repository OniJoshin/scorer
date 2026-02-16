@extends('layouts.app')

@section('title', 'Games')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-app-primary">Games Library</h1>
        <p class="text-app-secondary mt-1">Manage your board game collection</p>
    </div>
    
    @if($games->count())
        {{-- Games Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-20">
            @foreach($games as $game)
                <div class="bg-app-elevated rounded-xl border border-app overflow-hidden hover:shadow-lg transition-shadow">
                    {{-- Game Card Content --}}
                    <div class="p-5">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-app-primary mb-1">{{ $game->name }}</h3>
                                @if($game->played_at)
                                    <p class="text-xs text-app-tertiary flex items-center">
                                        <x-heroicon-o-calendar class="w-3 h-3 mr-1"/>
                                        {{ $game->played_at->format('M j, Y') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        
                        @if($game->notes)
                            <p class="text-sm text-app-secondary mb-3 line-clamp-2">{{ $game->notes }}</p>
                        @endif
                        
                        {{-- Position Points Info --}}
                        @if($game->position_points)
                            <div class="flex flex-wrap gap-1 mb-3">
                                @foreach($game->position_points as $pos => $pts)
                                    @if($pts)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-app-secondary text-app-secondary">
                                            {{ $pos }}{{ $pos==1?'st':($pos==2?'nd':($pos==3?'rd':'th')) }}: {{ $pts }}pts
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                    
                    {{-- Actions --}}
                    <div class="flex border-t border-app">
                        <a href="{{ route('games.edit', $game) }}" 
                           class="flex-1 flex items-center justify-center py-3 text-[var(--color-primary)] hover:bg-app-secondary transition-colors">
                            <x-heroicon-o-pencil-square class="w-4 h-4 mr-1"/>
                            <span class="text-sm font-medium">Edit</span>
                        </a>
                        <form action="{{ route('games.destroy', $game) }}" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Delete {{ $game->name }}? This cannot be undone.')"
                                    class="w-full flex items-center justify-center py-3 text-[var(--color-error)] hover:bg-[var(--color-error-bg)] transition-colors">
                                <x-heroicon-o-trash class="w-4 h-4 mr-1"/>
                                <span class="text-sm font-medium">Delete</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- Empty State --}}
        <div class="bg-app-elevated rounded-xl p-12 border border-app border-dashed text-center">
            <x-heroicon-o-cube class="w-20 h-20 mx-auto text-app-tertiary mb-4"/>
            <h3 class="text-xl font-semibold text-app-primary mb-2">No games yet</h3>
            <p class="text-app-secondary mb-6 max-w-md mx-auto">Add your first board game to start tracking scores and sessions!</p>
            <a href="{{ route('games.create') }}" 
               class="inline-flex items-center px-6 py-3 bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] text-white font-semibold rounded-lg transition-colors">
                <x-heroicon-s-plus class="w-5 h-5 mr-2"/>
                Add First Game
            </a>
        </div>
    @endif
    
    {{-- Floating Action Button --}}
    @if($games->count())
        <a href="{{ route('games.create') }}" 
           class="md:hidden fixed bottom-24 right-6 w-14 h-14 bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] rounded-full shadow-lg flex items-center justify-center transition-all hover:scale-110"
           aria-label="Add game">
            <x-heroicon-s-plus class="w-6 h-6 text-white"/>
        </a>
    @endif
</div>
@endsection
