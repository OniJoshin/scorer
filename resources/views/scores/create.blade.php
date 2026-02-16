@extends('layouts.app')

@section('title', 'Add Match Results')

@section('content')
<div class="max-w-2xl mx-auto">
    {{-- Page Header --}}
    <div class="mb-6">
        <div class="flex items-center mb-2">
            <a href="{{ route('scores.index') }}" 
               class="mr-3 p-2 hover:bg-app-secondary rounded-lg transition-colors">
                <x-heroicon-o-arrow-left class="w-6 h-6 text-app-secondary"/>
            </a>
            <h1 class="text-3xl font-bold text-app-primary">Add Match Results</h1>
        </div>
        <p class="text-app-secondary">Enter all players and positions for one completed game</p>
    </div>
    
    <form action="{{ route('scores.store') }}" method="POST" class="space-y-6" x-data="matchResultsForm()">
        @csrf
        
        <div class="bg-app-elevated rounded-xl p-6 border border-app space-y-6">
            {{-- Game Selection --}}
            <div>
                <label for="game_id" class="flex items-center text-sm font-semibold text-app-primary mb-3">
                    <x-heroicon-o-puzzle-piece class="w-5 h-5 mr-2 text-[var(--color-primary)]"/>
                    Game *
                </label>
                <select name="game_id" 
                        id="game_id" 
                        required
                        class="w-full px-4 py-3 text-lg border border-app-dark rounded-lg bg-app-primary text-app-primary focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent">
                    <option value="">-- Select Game --</option>
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

            {{-- Played Date --}}
            <div>
                <label for="played_at" class="flex items-center text-sm font-semibold text-app-primary mb-3">
                    <x-heroicon-o-calendar class="w-5 h-5 mr-2 text-[var(--color-primary)]"/>
                    Played Date
                </label>
                <input type="date"
                       name="played_at"
                       id="played_at"
                       value="{{ old('played_at', now()->format('Y-m-d')) }}"
                       class="w-full px-4 py-3 text-lg border border-app-dark rounded-lg bg-app-primary text-app-primary focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent">
                @error('played_at')
                    <p class="text-sm text-[var(--color-error)] mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Match Entries --}}
            <div>
                <div class="flex items-center justify-between mb-3">
                    <label class="flex items-center text-sm font-semibold text-app-primary">
                        <x-heroicon-o-user-group class="w-5 h-5 mr-2 text-[var(--color-primary)]"/>
                        Player Results *
                    </label>
                    <button type="button"
                            @click="addEntry()"
                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-[var(--color-primary)] border border-app-dark rounded-lg hover:bg-app-secondary transition-colors">
                        <x-heroicon-o-plus class="w-4 h-4 mr-1"/>
                        Add Player
                    </button>
                </div>

                <template x-for="(entry, index) in entries" :key="index">
                    <div class="grid grid-cols-12 gap-3 mb-3 p-3 rounded-lg border border-app-dark">
                        <div class="col-span-8">
                            <label class="block text-xs text-app-secondary mb-1">Player</label>
                            <select :name="`entries[${index}][player_id]`"
                                    x-model="entry.player_id"
                                    required
                                    class="w-full px-3 py-2 border border-app-dark rounded-lg bg-app-primary text-app-primary focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent">
                                <option value="">-- Select Player --</option>
                                @foreach($players as $player)
                                    <option value="{{ $player->id }}">{{ $player->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-3">
                            <label class="block text-xs text-app-secondary mb-1">Position</label>
                            <input type="number"
                                   min="1"
                                   required
                                   :name="`entries[${index}][position]`"
                                   x-model="entry.position"
                                   placeholder="1"
                                   class="w-full px-3 py-2 border border-app-dark rounded-lg bg-app-primary text-app-primary focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent">
                        </div>

                        <div class="col-span-1 flex items-end">
                            <button type="button"
                                    @click="removeEntry(index)"
                                    x-show="entries.length > 2"
                                    class="w-full p-2 text-[var(--color-error)] hover:bg-[var(--color-error-bg)] rounded-lg transition-colors"
                                    aria-label="Remove entry">
                                <x-heroicon-o-trash class="w-4 h-4"/>
                            </button>
                        </div>
                    </div>
                </template>

                @error('entries')
                    <p class="text-sm text-[var(--color-error)] mt-2">{{ $message }}</p>
                @enderror
                @error('entries.*.player_id')
                    <p class="text-sm text-[var(--color-error)] mt-2">Each result row needs a valid player.</p>
                @enderror
                @error('entries.*.position')
                    <p class="text-sm text-[var(--color-error)] mt-2">Each result row needs a valid position.</p>
                @enderror
            </div>
        </div>
        
        {{-- Action Buttons --}}
        <div class="flex items-center space-x-3">
            <button type="submit"
                    class="flex-1 flex items-center justify-center px-6 py-3 bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] text-white font-semibold rounded-lg transition-colors">
                <x-heroicon-s-check class="w-5 h-5 mr-2"/>
                Save Match Results
            </button>
            <a href="{{ route('scores.index') }}" 
               class="px-6 py-3 border-2 border-app-dark hover:bg-app-secondary text-app-primary font-semibold rounded-lg transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
function matchResultsForm() {
    return {
        entries: @js(old('entries', [
            ['player_id' => '', 'position' => 1],
            ['player_id' => '', 'position' => 2],
        ])),
        addEntry() {
            this.entries.push({ player_id: '', position: this.entries.length + 1 });
        },
        removeEntry(index) {
            if (this.entries.length <= 2) {
                return;
            }

            this.entries.splice(index, 1);
        },
    };
}
</script>
@endsection
