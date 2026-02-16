@extends('layouts.app')

@section('title', 'Leaderboard')

@section('content')
<div class="max-w-4xl mx-auto mb-20">
    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-app-primary flex items-center">
            <x-heroicon-o-trophy class="w-8 h-8 mr-2 text-[var(--color-warning)]"/>
            Leaderboard
        </h1>
        <p class="text-app-secondary mt-1">Champions of the game night</p>
    </div>
    
    {{-- Filters --}}
    <form method="GET" class="bg-app-elevated rounded-xl p-4 border border-app mb-6 sticky top-0 z-10 shadow-lg">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label for="year" class="flex items-center text-sm font-semibold text-app-primary mb-2">
                    <x-heroicon-o-calendar class="w-4 h-4 mr-1.5 text-[var(--color-primary)]"/>
                    Year
                </label>
                <select name="year" 
                        id="year" 
                        onchange="this.form.submit()" 
                        class="w-full px-3 py-2 border border-app-dark rounded-lg bg-app-primary text-app-primary focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent">
                    <option value="">All Time</option>
                    @foreach($availableYears as $availableYear)
                        <option value="{{ $availableYear }}" {{ $year == $availableYear ? 'selected' : '' }}>
                            {{ $availableYear }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="game_id" class="flex items-center text-sm font-semibold text-app-primary mb-2">
                    <x-heroicon-o-puzzle-piece class="w-4 h-4 mr-1.5 text-[var(--color-primary)]"/>
                    Game
                </label>
                <select name="game_id" 
                        id="game_id" 
                        onchange="this.form.submit()" 
                        class="w-full px-3 py-2 border border-app-dark rounded-lg bg-app-primary text-app-primary focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent">
                    <option value="">All Games</option>
                    @foreach($availableGames as $game)
                        <option value="{{ $game->id }}" {{ $gameId == $game->id ? 'selected' : '' }}>
                            {{ $game->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
    
    @if($players->count())
        {{-- Podium for Top 3 --}}
        @if($players->count() >= 3)
            <div class="grid grid-cols-3 gap-2 mb-6 items-end">
                {{-- 2nd Place --}}
                @php $player = $players[1] @endphp
                <div class="bg-gradient-to-br from-gray-400 to-gray-600 rounded-t-xl p-4 text-center text-white shadow-lg" style="height: 140px;">
                    <div class="flex flex-col items-center justify-between h-full">
                        <x-heroicon-s-circle-stack class="w-10 h-10 opacity-75 mb-1"/>
                        <div>
                            <div class="text-2xl font-bold mb-1">2</div>
                            <div class="font-semibold text-sm truncate">{{ $player->name }}</div>
                            <div class="text-xs opacity-90 mt-1">{{ $player->total_points ?? 0 }} pts</div>
                        </div>
                    </div>
                </div>
                
                {{-- 1st Place --}}
                @php $player = $players[0] @endphp
                <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-t-xl p-4 text-center text-white shadow-xl" style="height: 180px;">
                    <div class="flex flex-col items-center justify-between h-full">
                        <x-heroicon-s-trophy class="w-12 h-12 opacity-90 mb-1"/>
                        <div>
                            <div class="text-3xl font-bold mb-1">1</div>
                            <div class="font-bold truncate">{{ $player->name }}</div>
                            <div class="text-sm opacity-90 mt-1">{{ $player->total_points ?? 0 }} pts</div>
                        </div>
                    </div>
                </div>
                
                {{-- 3rd Place --}}
                @php $player = $players[2] @endphp
                <div class="bg-gradient-to-br from-orange-600 to-orange-800 rounded-t-xl p-4 text-center text-white shadow-lg" style="height: 120px;">
                    <div class="flex flex-col items-center justify-between h-full">
                        <x-heroicon-s-star class="w-8 h-8 opacity-75 mb-1"/>
                        <div>
                            <div class="text-xl font-bold mb-1">3</div>
                            <div class="font-semibold text-xs truncate">{{ $player->name }}</div>
                            <div class="text-xs opacity-90 mt-1">{{ $player->total_points ?? 0 }} pts</div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
        {{-- Full Rankings List --}}
        <div class="space-y-2">
            @foreach($players as $index => $player)
                <div class="bg-app-elevated rounded-xl border border-app overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="flex items-center p-4">
                        {{-- Rank --}}
                        <div class="w-12 flex-shrink-0 mr-4">
                            @if($index === 0)
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center">
                                    <x-heroicon-s-trophy class="w-6 h-6 text-white"/>
                                </div>
                            @elseif($index === 1)
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center">
                                    <x-heroicon-s-circle-stack class="w-5 h-5 text-white"/>
                                </div>
                            @elseif($index === 2)
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-600 to-orange-800 flex items-center justify-center">
                                    <x-heroicon-s-star class="w-5 h-5 text-white"/>
                                </div>
                            @else
                                <div class="w-10 h-10 rounded-full bg-app-secondary flex items-center justify-center">
                                    <span class="text-lg font-bold text-app-secondary">{{ $index + 1 }}</span>
                                </div>
                            @endif
                        </div>
                        
                        {{-- Player Info --}}
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-app-primary truncate">{{ $player->name }}</h3>
                            <p class="text-sm text-app-secondary">{{ $player->scores_count }} {{ $player->scores_count === 1 ? 'game' : 'games' }} played</p>
                        </div>
                        
                        {{-- Stats --}}
                        <div class="flex flex-col items-end ml-4">
                            <div class="text-xl font-bold text-[var(--color-primary)]">{{ $player->total_points ?? 0 }}</div>
                            <div class="text-xs text-app-secondary">
                                @if($player->scores_count > 0)
                                    {{ number_format($player->total_points / $player->scores_count, 1) }} avg
                                @else
                                    0 avg
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- Empty State --}}
        <div class="bg-app-elevated rounded-xl p-12 border border-app border-dashed text-center">
            <x-heroicon-o-trophy class="w-20 h-20 mx-auto text-app-tertiary mb-4"/>
            <h3 class="text-xl font-semibold text-app-primary mb-2">No rankings yet</h3>
            <p class="text-app-secondary mb-6 max-w-md mx-auto">Start logging some scores to see who's leading the pack!</p>
            <a href="{{ route('scores.create') }}" 
               class="inline-flex items-center px-6 py-3 bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] text-white font-semibold rounded-lg transition-colors">
                <x-heroicon-s-plus class="w-5 h-5 mr-2"/>
                Add First Score
            </a>
        </div>
    @endif
</div>
@endsection
