@extends('layouts.app')

@section('title', 'Players')

@section('content')
<div class="max-w-3xl mx-auto">
    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-app-primary">Players</h1>
        <p class="text-app-secondary mt-1">Manage your game night crew</p>
    </div>
    
    @if($players->count())
        {{-- Players List --}}
        <div class="space-y-2 mb-20">
            @foreach($players as $player)
                <div class="bg-app-elevated rounded-xl border border-app overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between p-4">
                        <div class="flex items-center flex-1">
                            {{-- Avatar --}}
                            <div class="w-12 h-12 rounded-full bg-[var(--color-primary-light)] flex items-center justify-center mr-4">
                                <span class="text-lg font-bold text-[var(--color-primary)]">{{ substr($player->name, 0, 1) }}</span>
                            </div>
                            
                            {{-- Player Info --}}
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-app-primary">{{ $player->name }}</h3>
                                <p class="text-sm text-app-secondary flex items-center">
                                    <x-heroicon-o-puzzle-piece class="w-4 h-4 mr-1"/>
                                    {{ $player->gameSessionResults->count() }} {{ $player->gameSessionResults->count() === 1 ? 'game' : 'games' }} played
                                </p>
                            </div>
                        </div>
                        
                        {{-- Actions --}}
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('players.edit', $player) }}" 
                               class="p-2 text-[var(--color-primary)] hover:bg-app-secondary rounded-lg transition-colors"
                               aria-label="Edit {{ $player->name }}">
                                <x-heroicon-o-pencil-square class="w-5 h-5"/>
                            </a>
                            <form action="{{ route('players.destroy', $player) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Delete {{ $player->name }}? This cannot be undone.')"
                                        class="p-2 text-[var(--color-error)] hover:bg-[var(--color-error-bg)] rounded-lg transition-colors"
                                        aria-label="Delete {{ $player->name }}">
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
            <x-heroicon-o-user-group class="w-20 h-20 mx-auto text-app-tertiary mb-4"/>
            <h3 class="text-xl font-semibold text-app-primary mb-2">No players yet</h3>
            <p class="text-app-secondary mb-6 max-w-md mx-auto">Add players to start tracking their scores and compete!</p>
            <a href="{{ route('players.create') }}" 
               class="inline-flex items-center px-6 py-3 bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] text-white font-semibold rounded-lg transition-colors">
                <x-heroicon-s-plus class="w-5 h-5 mr-2"/>
                Add First Player
            </a>
        </div>
    @endif
    
    {{-- Floating Action Button --}}
    @if($players->count())
        <a href="{{ route('players.create') }}" 
           class="md:hidden fixed bottom-24 right-6 w-14 h-14 bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] rounded-full shadow-lg flex items-center justify-center transition-all hover:scale-110"
           aria-label="Add player">
            <x-heroicon-s-plus class="w-6 h-6 text-white"/>
        </a>
    @endif
</div>
@endsection
