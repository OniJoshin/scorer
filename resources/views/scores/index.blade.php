@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6 text-purple-700">📋 Game Scores</h1>

<a href="{{ route('scores.create') }}"
   class="inline-block mb-4 bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 shadow">
    ➕ Add Score
</a>

@if($scores->count())
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white shadow rounded border">
            <thead class="bg-purple-100 text-purple-800">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Game</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Player</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Position</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Points</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Played</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($scores as $score)
                    <tr class="hover:bg-purple-50">
                        <td class="px-4 py-2">{{ $score->game->name }}</td>
                        <td class="px-4 py-2">{{ $score->player->name }}</td>
                        <td class="px-4 py-2">{{ $score->position }}</td>
                        <td class="px-4 py-2">{{ $score->points }}</td>
                        <td class="px-4 py-2">{{ optional($score->game->played_at)->format('Y-m-d') }}</td>
                        <td class="px-4 py-2 space-x-2">
                            <a href="{{ route('scores.edit', $score) }}" class="text-sm text-purple-700 hover:underline">Edit</a>
                            <form action="{{ route('scores.destroy', $score) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Delete score?')" type="submit"
                                        class="text-sm text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <p class="text-gray-500">No scores recorded yet.</p>
@endif
@endsection
