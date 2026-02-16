@extends('layouts.app')

@section('title', 'Edit Game')

@section('content')
<div class="max-w-3xl mx-auto">
    {{-- Page Header --}}
    <div class="mb-6">
        <div class="flex items-center mb-2">
            <a href="{{ route('games.index') }}" 
               class="mr-3 p-2 hover:bg-app-secondary rounded-lg transition-colors">
                <x-heroicon-o-arrow-left class="w-6 h-6 text-app-secondary"/>
            </a>
            <h1 class="text-3xl font-bold text-app-primary">Edit Game</h1>
        </div>
        <p class="text-app-secondary">Update {{ $game->name }}</p>
    </div>
    
    <form action="{{ route('games.update', $game) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        {{-- Basic Information --}}
        <div class="bg-app-elevated rounded-xl p-6 border border-app">
            <h2 class="text-lg font-semibold text-app-primary mb-4 flex items-center">
                <x-heroicon-o-information-circle class="w-5 h-5 mr-2 text-[var(--color-primary)]"/>
                Basic Information
            </h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-app-secondary mb-2">Game Name *</label>
                    <input type="text" name="name" value="{{ old('name', $game->name) }}" required
                           class="w-full px-4 py-3 border border-app-dark rounded-lg bg-app-primary text-app-primary focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent transition-shadow">
                    @error('name')
                        <p class="text-sm text-[var(--color-error)] mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-app-secondary mb-2">First Played Date</label>
                    <input type="date" name="played_at" value="{{ old('played_at', optional($game->played_at)->format('Y-m-d')) }}"
                           class="w-full px-4 py-3 border border-app-dark rounded-lg bg-app-primary text-app-primary focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent transition-shadow">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-app-secondary mb-2">Notes (Optional)</label>
                    <textarea name="notes" rows="3"
                              class="w-full px-4 py-3 border border-app-dark rounded-lg bg-app-primary text-app-primary focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent transition-shadow">{{ old('notes', $game->notes) }}</textarea>
                </div>
            </div>
        </div>
        
        {{-- Position-Based Points --}}
        <div class="bg-app-elevated rounded-xl p-6 border border-app">
            <h2 class="text-lg font-semibold text-app-primary mb-2 flex items-center">
                <x-heroicon-o-trophy class="w-5 h-5 mr-2 text-[var(--color-primary)]"/>
                Position Points
            </h2>
            <p class="text-sm text-app-secondary mb-4">Award leaderboard points based on finishing position</p>
            
            <div class="grid grid-cols-2 gap-3">
                @for($i = 1; $i <= 8; $i++)
                    <div>
                        <label class="block text-sm font-medium text-app-secondary mb-2">
                            {{ $i }}{{ $i==1?'st':($i==2?'nd':($i==3?'rd':'th')) }} Place
                        </label>
                        <input type="number" name="position_points[{{ $i }}]" value="{{ old('position_points.'.$i, $game->position_points[$i] ?? '') }}" min="0" placeholder="0"
                               class="w-full px-4 py-3 border border-app-dark rounded-lg bg-app-primary text-app-primary focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent transition-shadow">
                    </div>
                @endfor
            </div>
        </div>
        
        {{-- Action Buttons --}}
        <div class="flex items-center space-x-3 pt-4">
            <button type="submit"
                    class="flex-1 flex items-center justify-center px-6 py-3 bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] text-white font-semibold rounded-lg transition-colors">
                <x-heroicon-s-check class="w-5 h-5 mr-2"/>
                Update Game
            </button>
            <a href="{{ route('games.index') }}" 
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
        <p class="text-sm text-app-secondary mb-4">Deleting this game will remove all associated sessions and scores. This cannot be undone.</p>
        <form action="{{ route('games.destroy', $game) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    onclick="return confirm('Are you sure you want to delete {{ $game->name }}? This will remove all game sessions and scores. This action cannot be undone!')"
                    class="flex items-center px-6 py-3 bg-[var(--color-error)] hover:opacity-90 text-white font-semibold rounded-lg transition-opacity">
                <x-heroicon-s-trash class="w-5 h-5 mr-2"/>
                Delete Game Permanently
            </button>
        </form>
    </div>
</div>
@endsection
