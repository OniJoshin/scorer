@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6 text-purple-700">➕ Add Player</h1>

<form action="{{ route('players.store') }}" method="POST" class="space-y-4">
    @csrf
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Player Name</label>
        <input type="text" name="name" id="name" required
               value="{{ old('name') }}"
               class="mt-1 block w-full border-gray-300 rounded shadow-sm focus:ring-purple-500 focus:border-purple-500">
        @error('name')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit"
            class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 shadow">
        Save Player
    </button>

    <a href="{{ route('players.index') }}" class="inline-block ml-3 text-sm text-gray-600 hover:underline">Cancel</a>
</form>
@endsection
