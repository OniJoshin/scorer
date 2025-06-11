@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10">
    <h1 class="text-2xl font-bold mb-6">Game Sessions</h1>

    <a href="{{ route('game_sessions.create') }}" class="mb-4 inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">New Session</a>

    @if($sessions->isEmpty())
        <p>No game sessions yet.</p>
    @else
        <ul class="divide-y divide-gray-200">
            @foreach($sessions as $session)
                <li class="py-4">
                    <a href="{{ route('game_sessions.show', $session) }}" class="text-lg text-blue-700 font-semibold">
                        {{ $session->game->name }} - {{ $session->started_at->format('j M Y') }}
                    </a>
                    <p class="text-sm text-gray-500">
                        {{ $session->completed_at ? '✅ Completed' : '⏳ Open' }}
                    </p>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
