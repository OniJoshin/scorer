@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6 text-purple-700">✏️ Edit Score</h1>

<form action="{{ route('scores.update', $score) }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')

    <div>
        <label class="block text-sm font-medium text-gray-700">Game</label>
        <select name="game_id" required
                class="mt-1 block w-full border-gray-300 rounded shadow-sm">
            @foreach($games as $game)
                <option value="{{ $game->id }}" {{ $score->game_id == $game->id ? 'selected' : '' }}>
                    {{ $game->name }} ({{ optional($game->played_at)->format('Y-m-d') }})
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Player</label>
        <select name="player_id" required
                class="mt-1 block w-full border-gray-300 rounded shadow-sm">
            @foreach($players as $player)
                <option value="{{ $player->id }}" {{ $score->player_id == $player->id ? 'selected' : '' }}>
                    {{ $player->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Position</label>
        <input type="number" name="position" value="{{ old('position', $score->position) }}" min="1" required
               class="mt-1 block w-full border-gray-300 rounded shadow-sm">
    </div>

    <button type="submit"
            class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 shadow">
        Update Score
    </button>

    <a href="{{ route('scores.index') }}" class="inline-block ml-3 text-sm text-gray-600 hover:underline">Cancel</a>
</form>
@endsection
