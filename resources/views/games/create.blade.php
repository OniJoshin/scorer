@extends('layouts.app')

@section('title', 'Add Game')

@section('content')
<div class="max-w-3xl mx-auto">
    {{-- Page Header --}}
    <div class="mb-6">
        <div class="flex items-center mb-2">
            <a href="{{ route('games.index') }}" 
               class="mr-3 p-2 hover:bg-app-secondary rounded-lg transition-colors">
                <x-heroicon-o-arrow-left class="w-6 h-6 text-app-secondary"/>
            </a>
            <h1 class="text-3xl font-bold text-app-primary">Add Game</h1>
        </div>
        <p class="text-app-secondary">Add a new board game to your library</p>
    </div>
    
    <form action="{{ route('games.store') }}" method="POST" class="space-y-6">
        @csrf
        
        {{-- Basic Information --}}
        <div class="bg-app-elevated rounded-xl p-6 border border-app">
            <h2 class="text-lg font-semibold text-app-primary mb-4 flex items-center">
                <x-heroicon-o-information-circle class="w-5 h-5 mr-2 text-[var(--color-primary)]"/>
                Basic Information
            </h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-app-secondary mb-2">Game Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g., Ticket to Ride"
                           class="w-full px-4 py-3 border border-app-dark rounded-lg bg-app-primary text-app-primary focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent transition-shadow">
                    @error('name')
                        <p class="text-sm text-[var(--color-error)] mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-app-secondary mb-2">First Played Date</label>
                    <input type="date" name="played_at" value="{{ old('played_at') }}"
                           class="w-full px-4 py-3 border border-app-dark rounded-lg bg-app-primary text-app-primary focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent transition-shadow">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-app-secondary mb-2">Notes (Optional)</label>
                    <textarea name="notes" rows="3" placeholder="Add any notes about this game..."
                              class="w-full px-4 py-3 border border-app-dark rounded-lg bg-app-primary text-app-primary focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent transition-shadow">{{ old('notes') }}</textarea>
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
                        <input type="number" name="position_points[{{ $i }}]" value="{{ old('position_points.'.$i) }}" min="0" placeholder="0"
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
                Save Game
            </button>
            <a href="{{ route('games.index') }}" 
               class="px-6 py-3 border-2 border-app-dark hover:bg-app-secondary text-app-primary font-semibold rounded-lg transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
