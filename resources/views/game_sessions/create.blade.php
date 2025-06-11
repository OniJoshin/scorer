@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6 text-purple-700">🆕 Start New Game Session</h1>

<form action="{{ route('game_sessions.store') }}" method="POST" class="space-y-6">
    @csrf

    <div>
        <label class="block text-sm font-medium text-gray-700">Select Game</label>
        <select name="game_id" required
                class="mt-1 block w-full border-gray-300 rounded shadow-sm">
            <option value="">-- Choose a Game --</option>
            @foreach($games as $game)
                <option value="{{ $game->id }}">{{ $game->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Choose Players (2–4)</label>
        <div class="grid grid-cols-2 gap-2 mt-2">
            @foreach($players as $player)
                <label class="inline-flex items-center space-x-2">
                    <input type="checkbox" name="player_ids[]" value="{{ $player->id }}"
                           class="text-purple-600 focus:ring-purple-500 rounded">
                    <span>{{ $player->name }}</span>
                </label>
            @endforeach
        </div>
        @error('player_ids')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Notes (optional)</label>
        <textarea name="notes" rows="3"
                  class="mt-1 block w-full border-gray-300 rounded shadow-sm">{{ old('notes') }}</textarea>
    </div>

    <button type="submit"
            class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 shadow">
        Start Session
    </button>

    <a href="{{ route('game_sessions.index') }}" class="inline-block ml-3 text-sm text-gray-600 hover:underline">
        Cancel
    </a>
</form>
@endsection
