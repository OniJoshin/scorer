@extends('layouts.app')

@section('title', 'Edit Player')

@section('content')
<div class="max-w-2xl mx-auto">
    {{-- Page Header --}}
    <div class="mb-6">
        <div class="flex items-center mb-2">
            <a href="{{ route('players.index') }}" 
               class="mr-3 p-2 hover:bg-app-secondary rounded-lg transition-colors">
                <x-heroicon-o-arrow-left class="w-6 h-6 text-app-secondary"/>
            </a>
            <h1 class="text-3xl font-bold text-app-primary">Edit Player</h1>
        </div>
        <p class="text-app-secondary">Update {{ $player->name }}'s information</p>
    </div>
    
    <form action="{{ route('players.update', $player) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="bg-app-elevated rounded-xl p-6 border border-app">
            <div>
                <label for="name" class="flex items-center text-sm font-semibold text-app-primary mb-3">
                    <x-heroicon-o-user class="w-5 h-5 mr-2 text-[var(--color-primary)]"/>
                    Player Name *
                </label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       required
                       value="{{ old('name', $player->name) }}"
                       class="w-full px-4 py-3 text-lg border border-app-dark rounded-lg bg-app-primary text-app-primary focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent transition-shadow">
                @error('name')
                    <p class="text-sm text-[var(--color-error)] mt-2">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        {{-- Action Buttons --}}
        <div class="flex items-center space-x-3">
            <button type="submit"
                    class="flex-1 flex items-center justify-center px-6 py-3 bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] text-white font-semibold rounded-lg transition-colors">
                <x-heroicon-s-check class="w-5 h-5 mr-2"/>
                Update Player
            </button>
            <a href="{{ route('players.index') }}" 
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
        <p class="text-sm text-app-secondary mb-4">Deleting this player will remove them from all game sessions. This cannot be undone.</p>
        <form action="{{ route('players.destroy', $player) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    onclick="return confirm('Are you sure you want to delete {{ $player->name }}? This will remove them from all sessions. This action cannot be undone!')"
                    class="flex items-center px-6 py-3 bg-[var(--color-error)] hover:opacity-90 text-white font-semibold rounded-lg transition-opacity">
                <x-heroicon-s-trash class="w-5 h-5 mr-2"/>
                Delete Player Permanently
            </button>
        </form>
    </div>
</div>
@endsection
