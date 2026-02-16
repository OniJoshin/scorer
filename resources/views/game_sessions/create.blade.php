@extends('layouts.app')

@section('title', 'New Session')

@section('content')
<div class="max-w-2xl mx-auto">
    {{-- Page Header --}}
    <div class="mb-6">
        <div class="flex items-center mb-2">
            <a href="{{ route('game_sessions.index') }}" 
               class="mr-3 p-2 hover:bg-app-secondary rounded-lg transition-colors">
                <x-heroicon-o-arrow-left class="w-6 h-6 text-app-secondary"/>
            </a>
            <h1 class="text-3xl font-bold text-app-primary">New Game Session</h1>
        </div>
        <p class="text-app-secondary">Select a game and players to start</p>
    </div>
    
    <form action="{{ route('game_sessions.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Session Helper Configuration --}}
        <div class="bg-app-elevated rounded-xl p-6 border border-app">
            <h2 class="text-lg font-semibold text-app-primary mb-4 flex items-center">
                <x-heroicon-o-calculator class="w-5 h-5 mr-2 text-[var(--color-primary)]"/>
                Session Helper
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-app-secondary mb-2">Helper Type</label>
                    <select id="helper_type" name="helper_type" required
                            class="w-full px-4 py-3 border border-app-dark rounded-lg bg-app-primary text-app-primary focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent transition-shadow">
                        @foreach($helperTypes as $key => $helper)
                            <option value="{{ $key }}"
                                    data-default-target="{{ $helper['default_target'] ?? '' }}"
                                    {{ old('helper_type', 'round_points') === $key ? 'selected' : '' }}>
                                {{ $helper['label'] }}
                            </option>
                        @endforeach
                    </select>
                    <p id="helper_description" class="text-xs text-app-tertiary mt-2"></p>
                    @error('helper_type')
                        <p class="text-sm text-[var(--color-error)] mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-app-secondary mb-2">Target Score (optional)</label>
                    <input type="number" id="target_score" name="target_score" min="1" value="{{ old('target_score', 200) }}"
                           class="w-full px-4 py-3 border border-app-dark rounded-lg bg-app-primary text-app-primary focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent transition-shadow">
                    @error('target_score')
                        <p class="text-sm text-[var(--color-error)] mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        {{-- Select Game --}}
        <div class="bg-app-elevated rounded-xl p-6 border border-app">
            <label class="flex items-center text-sm font-semibold text-app-primary mb-3">
                <x-heroicon-o-cube class="w-5 h-5 mr-2 text-[var(--color-primary)]"/>
                Select Game
            </label>
            <select name="game_id" required
                    class="w-full px-4 py-3 border border-app-dark rounded-lg bg-app-primary text-app-primary focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent transition-shadow">
                <option value="">-- Choose a Game --</option>
                @foreach($games as $game)
                    <option value="{{ $game->id }}" {{ old('game_id') == $game->id ? 'selected' : '' }}>
                        {{ $game->name }}
                    </option>
                @endforeach
            </select>
            @error('game_id')
                <p class="text-sm text-[var(--color-error)] mt-2">{{ $message }}</p>
            @enderror
        </div>
        
        {{-- Select Players --}}
        <div class="bg-app-elevated rounded-xl p-6 border border-app">
            <label class="flex items-center text-sm font-semibold text-app-primary mb-3">
                <x-heroicon-o-user-group class="w-5 h-5 mr-2 text-[var(--color-primary)]"/>
                Select Players (2 or more)
            </label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($players as $player)
                    <label class="flex items-center p-3 rounded-lg border-2 border-app hover:border-[var(--color-primary)] cursor-pointer transition-colors {{ in_array($player->id, old('player_ids', [])) ? 'border-[var(--color-primary)] bg-[var(--color-primary-light)]' : '' }}">
                        <input type="checkbox" name="player_ids[]" value="{{ $player->id }}"
                               {{ in_array($player->id, old('player_ids', [])) ? 'checked' : '' }}
                               class="w-5 h-5 rounded border-app-dark text-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary)] focus:ring-offset-0 mr-3">
                        <span class="font-medium text-app-primary">{{ $player->name }}</span>
                    </label>
                @endforeach
            </div>
            @error('player_ids')
                <p class="text-sm text-[var(--color-error)] mt-2">{{ $message }}</p>
            @enderror
        </div>
        
        {{-- Notes (Optional) --}}
        <div class="bg-app-elevated rounded-xl p-6 border border-app">
            <label class="flex items-center text-sm font-semibold text-app-primary mb-3">
                <x-heroicon-o-pencil class="w-5 h-5 mr-2 text-[var(--color-primary)]"/>
                Notes (Optional)
            </label>
            <textarea name="notes" rows="3" placeholder="Add any notes about this session..."
                      class="w-full px-4 py-3 border border-app-dark rounded-lg bg-app-primary text-app-primary focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent transition-shadow">{{ old('notes') }}</textarea>
        </div>
        
        {{-- Action Buttons --}}
        <div class="flex items-center space-x-3 pt-4">
            <button type="submit"
                    class="flex-1 flex items-center justify-center px-6 py-3 bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] text-white font-semibold rounded-lg transition-colors">
                <x-heroicon-s-play class="w-5 h-5 mr-2"/>
                Start Session
            </button>
            <a href="{{ route('game_sessions.index') }}" 
               class="px-6 py-3 border-2 border-app-dark hover:bg-app-secondary text-app-primary font-semibold rounded-lg transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const helperTypeSelect = document.getElementById('helper_type');
    const targetInput = document.getElementById('target_score');
    const helperDescription = document.getElementById('helper_description');

    const helperDescriptions = @js(collect($helperTypes)->mapWithKeys(fn ($helper, $key) => [$key => $helper['description']])->all());

    function updateHelperDefaults() {
        const selected = helperTypeSelect.options[helperTypeSelect.selectedIndex];
        const helperKey = selected?.value;
        const defaultTarget = selected?.dataset.defaultTarget;

        helperDescription.textContent = helperDescriptions[helperKey] || '';

        if (defaultTarget && !targetInput.value) {
            targetInput.value = defaultTarget;
        }

        if (!defaultTarget && targetInput.value === '200' && helperKey === 'round_points') {
            targetInput.value = '';
        }
    }

    helperTypeSelect.addEventListener('change', updateHelperDefaults);
    updateHelperDefaults();
});
</script>
@endsection
