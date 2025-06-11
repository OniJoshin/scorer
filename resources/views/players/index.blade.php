@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6 text-purple-700">🎭 Players</h1>

<a href="{{ route('players.create') }}"
   class="inline-block mb-4 bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 shadow">
    ➕ Add Player
</a>

@if($players->count())
    <ul class="grid grid-cols-1 gap-3">
        @foreach($players as $player)
            <li class="flex justify-between items-center bg-white shadow-sm rounded px-4 py-3 hover:bg-purple-50">
                <span class="text-lg font-medium">{{ $player->name }}</span>
                <div class="space-x-2">
                    <a href="{{ route('players.edit', $player) }}"
                       class="text-sm text-purple-700 hover:underline">Edit</a>

                    <form action="{{ route('players.destroy', $player) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Delete this player?')"
                                class="text-sm text-red-600 hover:underline">Delete</button>
                    </form>
                </div>
            </li>
        @endforeach
    </ul>
@else
    <p class="text-gray-500">No players yet.</p>
@endif
@endsection
