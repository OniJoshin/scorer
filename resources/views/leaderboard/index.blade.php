@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6 text-purple-700">🏆 Leaderboard</h1>

<form method="GET" class="mb-6 flex flex-wrap gap-4 items-center">
    <div>
        <label class="block text-sm font-medium text-gray-700">Filter by Year</label>
        <select name="year" onchange="this.form.submit()" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
            <option value="">All Time</option>
            @foreach($availableYears as $availableYear)
                <option value="{{ $availableYear }}" {{ $year == $availableYear ? 'selected' : '' }}>
                    {{ $availableYear }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Filter by Game</label>
        <select name="game_id" onchange="this.form.submit()" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
            <option value="">All Games</option>
            @foreach($availableGames as $game)
                <option value="{{ $game->id }}" {{ $gameId == $game->id ? 'selected' : '' }}>
                    {{ $game->name }}
                </option>
            @endforeach
        </select>
    </div>
</form>

<table class="min-w-full bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
    <thead class="bg-purple-100 text-purple-800">
        <tr>
            <th class="py-2 px-4 text-left text-sm font-semibold">#</th>
            <th class="py-2 px-4 text-left text-sm font-semibold">Player</th>
            <th class="py-2 px-4 text-left text-sm font-semibold">Total Points</th>
            <th class="py-2 px-4 text-left text-sm font-semibold">Games Played</th>
            <th class="py-2 px-4 text-left text-sm font-semibold">Avg Points</th>
        </tr>
    </thead>
    <tbody>
        @forelse($players as $index => $player)
            <tr class="hover:bg-purple-50">
                <td class="py-2 px-4 font-bold text-purple-700">{{ $index + 1 }}</td>
                <td class="py-2 px-4">{{ $player->name }}</td>
                <td class="py-2 px-4">{{ $player->total_points ?? 0 }}</td>
                <td class="py-2 px-4">{{ $player->scores_count }}</td>
                <td class="py-2 px-4">
                    @if($player->scores_count > 0)
                        {{ number_format($player->total_points / $player->scores_count, 2) }}
                    @else
                        0
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="py-4 px-4 text-center text-gray-500">No results found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
