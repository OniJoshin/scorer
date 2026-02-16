@extends('layouts.app')

@section('title', 'Add Player')

@section('content')
<div class="max-w-2xl mx-auto">
    {{-- Page Header --}}
    <div class="mb-6">
        <div class="flex items-center mb-2">
            <a href="{{ route('players.index') }}" 
               class="mr-3 p-2 hover:bg-app-secondary rounded-lg transition-colors">
                <x-heroicon-o-arrow-left class="w-6 h-6 text-app-secondary"/>
            </a>
            <h1 class="text-3xl font-bold text-app-primary">Add Player</h1>
        </div>
        <p class="text-app-secondary">Add a new player to your game night roster</p>
    </div>
    
    <form action="{{ route('players.store') }}" method="POST" class="space-y-6">
        @csrf
        
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
                       autofocus
                       placeholder="Enter player name..."
                       value="{{ old('name') }}"
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
                Save Player
            </button>
            <a href="{{ route('players.index') }}" 
               class="px-6 py-3 border-2 border-app-dark hover:bg-app-secondary text-app-primary font-semibold rounded-lg transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
