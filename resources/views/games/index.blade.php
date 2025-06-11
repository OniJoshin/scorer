@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6 text-purple-700">🎲 Games</h1>

<a href="{{ route('games.create') }}"
   class="inline-block mb-4 bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 shadow">
    ➕ Add Game
</a>

@if($games->count())
    <ul class="space-y-4">
        @foreach($games as $game)
            <li class="bg-white rounded shadow p-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-lg font-semibold text-purple-800">{{ $game->name }}</h2>
                        <p class="text-sm text-gray-600">{{ optional($game->played_at)->format('Y-m-d') }}</p>
                        @if($game->notes)
                            <p class="text-sm text-gray-700 mt-1 italic">{{ $game->notes }}</p>
                        @endif
                        <p class="mt-2 text-sm text-gray-700">
                            <strong>Points:</strong>
                            @if($game->position_points)
                                @foreach($game->position_points as $pos => $pts)
                                    <span class="mr-2">{{ $pos }}{{ $pos==1?'st':($pos==2?'nd':($pos==3?'rd':'th')) }}: <span class="text-purple-700 font-semibold">{{ $pts }}</span></span>
                                @endforeach
                            @else
                                <em>No points defined</em>
                            @endif
                        </p>
                    </div>

                    <div class="flex space-x-2">
                        <a href="{{ route('games.edit', $game) }}"
                           class="text-sm text-purple-700 hover:underline">Edit</a>
                        <form action="{{ route('games.destroy', $game) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Delete this game?')"
                                    class="text-sm text-red-600 hover:underline">Delete</button>
                        </form>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
@else
    <p class="text-gray-500">No games added yet.</p>
@endif
@endsection
