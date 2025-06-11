<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Board Game Tracker</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="bg-gradient-to-br from-yellow-50 to-purple-100 text-gray-900 font-sans min-h-screen">

    <nav class="bg-white shadow-md sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
            <div class="flex items-center space-x-4">
                <span class="text-xl font-bold text-purple-700">🎲 Beyond Monopoly</span>
                <a href="{{ route('players.index') }}" class="text-gray-700 hover:text-purple-600 font-medium">Players</a>
                <a href="{{ route('games.index') }}" class="text-gray-700 hover:text-purple-600 font-medium">Games</a>
                <a href="{{ route('scores.index') }}" class="text-gray-700 hover:text-purple-600 font-medium">Scores</a>
                <a href="{{ route('leaderboard.index') }}" class="text-gray-700 hover:text-purple-600 font-medium">Leaderboard</a>
            </div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto py-10 px-4">
        @if(session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>


