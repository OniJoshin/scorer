@extends('layouts.app')

@section('title', 'Edit Direct Score')

@section('content')
<div class="max-w-2xl mx-auto">
    {{-- Page Header --}}
    <div class="mb-6">
        <div class="flex items-center mb-2">
            <a href="{{ route('scores.index') }}" 
               class="mr-3 p-2 hover:bg-app-secondary rounded-lg transition-colors">
                <x-heroicon-o-arrow-left class="w-6 h-6 text-app-secondary"/>
            </a>
            <h1 class="text-3xl font-bold text-app-primary">Edit Direct Score</h1>
        </div>
        <p class="text-app-secondary">Update score information</p>
    </div>
    
    <form action="{{ route('scores.update', $score) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="bg-app-elevated rounded-xl p-6 border border-app space-y-6">
            {{-- Game Selection --}}
            <div>
                <label for="game_id" class="flex items-center text-sm font-semibold text-app-primary mb-3">
                    <x-heroicon-o-puzzle-piece class="w-5 h-5 mr-2 text-[var(--color-primary)]"/>
                    Game *
                </label>
                <select name="game_id" 
                        id="game_id" 
                        required
                        class="w-full px-4 py-3 text-lg border border-app-dark rounded-lg bg-app-primary text-app-primary focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent">
                    @foreach($games as $game)
                        <option value="{{ $game->id }}" {{ $score->game_id == $game->id ? 'selected' : '' }}>
                            {{ $game->name }}
                        </option>
                    @endforeach
                </select>
                @error('game_id')
                    <p class="text-sm text-[var(--color-error)] mt-2">{{ $message }}</p>
                @enderror
            </div>
            
            {{-- Player Selection --}}
            <div>
                <label for="player_id" class="flex items-center text-sm font-semibold text-app-primary mb-3">
                    <x-heroicon-o-user class="w-5 h-5 mr-2 text-[var(--color-primary)]"/>
                    Player *
                </label>
                <select name="player_id" 
                        id="player_id" 
                        required
                        class="w-full px-4 py-3 text-lg border border-app-dark rounded-lg bg-app-primary text-app-primary focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent">
                    @foreach($players as $player)
                        <option value="{{ $player->id }}" {{ $score->player_id == $player->id ? 'selected' : '' }}>
                            {{ $player->name }}
                        </option>
                    @endforeach
                </select>
                @error('player_id')
                    <p class="text-sm text-[var(--color-error)] mt-2">{{ $message }}</p>
                @enderror
            </div>
            
            {{-- Position --}}
            <div>
                <label for="position" class="flex items-center text-sm font-semibold text-app-primary mb-3">
                    <x-heroicon-o-hashtag class="w-5 h-5 mr-2 text-[var(--color-primary)]"/>
                    Position *
                </label>
                <input type="number" 
                       name="position" 
                       id="position" 
                       min="1" 
                       required
                       value="{{ old('position', $score->position) }}"
                       class="w-full px-4 py-3 text-lg border border-app-dark rounded-lg bg-app-primary text-app-primary focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent">
                @error('position')
                    <p class="text-sm text-[var(--color-error)] mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Played Date --}}
            <div>
                <label for="played_at" class="flex items-center text-sm font-semibold text-app-primary mb-3">
                    <x-heroicon-o-calendar class="w-5 h-5 mr-2 text-[var(--color-primary)]"/>
                    Played Date
                </label>
                <input type="date"
                       name="played_at"
                       id="played_at"
                       value="{{ old('played_at', optional($score->played_at)->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
                       class="w-full px-4 py-3 text-lg border border-app-dark rounded-lg bg-app-primary text-app-primary focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent">
                @error('played_at')
                    <p class="text-sm text-[var(--color-error)] mt-2">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        {{-- Action Buttons --}}
        <div class="flex items-center space-x-3">
            <button type="submit"
                    class="flex-1 flex items-center justify-center px-6 py-3 bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] text-white font-semibold rounded-lg transition-colors">
                <x-heroicon-s-check class="w-5 h-5 mr-2"/>
                Update Score
            </button>
            <a href="{{ route('scores.index') }}" 
               class="px-6 py-3 border-2 border-app-dark hover:bg-app-secondary text-app-primary font-semibold rounded-lg transition-colors">
                Cancel
            </a>
        </div>
    </form>
    
    {{-- Danger Zone --}}
    <div class="mt-8 bg-app-elevated rounded-xl p-6 border-2 border-[var(--color-error)]">
        <h3 class="text-lg font-semibold text-[var(--color-error)] mb-2 flex items-center">
            <x-heroicon-o-exclamation-triangle class="w-5 h-5 mr-2"/>
            Danger Zone
        </h3>
        <p class="text-sm text-app-secondary mb-4">Deleting this score will permanently remove it from the leaderboard. This cannot be undone.</p>
        <form action="{{ route('scores.destroy', $score) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    onclick="return confirm('Are you sure you want to delete this score? This action cannot be undone!')"
                    class="flex items-center px-6 py-3 bg-[var(--color-error)] hover:opacity-90 text-white font-semibold rounded-lg transition-opacity">
                <x-heroicon-s-trash class="w-5 h-5 mr-2"/>
                Delete Score Permanently
            </button>
        </form>
    </div>
</div>
@endsection
