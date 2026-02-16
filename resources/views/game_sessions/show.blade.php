@extends('layouts.app')

@section('title', $session->game->name)

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Page Header --}}
    <div class="mb-6">
        <div class="flex items-center justify-between mb-2">
            <div class="flex items-center">
                <a href="{{ route('game_sessions.index') }}" 
                   class="mr-3 p-2 hover:bg-app-secondary rounded-lg transition-colors">
                    <x-heroicon-o-arrow-left class="w-6 h-6 text-app-secondary"/>
                </a>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-app-primary">{{ $session->game->name }}</h1>
                    <p class="text-sm text-app-secondary">{{ $session->started_at ? $session->started_at->format('M j, Y') : $session->created_at->format('M j, Y') }}</p>
                </div>
            </div>
            @if($session->completed_at)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[var(--color-success-bg)] text-[var(--color-success)]">
                    <x-heroicon-s-check-circle class="w-4 h-4 mr-1"/>
                    Complete
                </span>
            @endif
        </div>
    </div>
    
    <form id="scorebookForm" action="{{ route('game_sessions.update_results', $session) }}" method="POST" class="space-y-4 pb-24">
        @csrf

        {{-- Session Helper Summary --}}
        <div class="bg-app-elevated rounded-xl p-5 border border-app">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-sm font-semibold text-app-primary">Session Helper</h2>
                    <p class="text-sm text-app-secondary mt-1">{{ $helper['label'] }} — {{ $helper['description'] }}</p>
                    @if($helper['key'] === 'flip7')
                        <p class="text-xs text-[var(--color-warning)] mt-1">Flip 7 rule: first player to reach target score can end the session.</p>
                    @endif
                </div>

                <div class="w-44">
                    <label for="target_score" class="block text-xs font-medium text-app-secondary mb-1">Target Score</label>
                    <input id="target_score" name="target_score" type="number" min="1"
                           value="{{ old('target_score', $session->target_score ?? $helper['default_target']) }}"
                           class="w-full px-3 py-2 border border-app-dark rounded-lg bg-app-primary text-app-primary focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent">
                </div>
            </div>
        </div>

        {{-- Player Score Cards --}}
        @foreach($session->results as $result)
            <div class="bg-app-elevated rounded-xl p-6 border border-app" data-player-card="{{ $result->id }}">
                {{-- Player Name --}}
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-[var(--color-primary-light)] flex items-center justify-center mr-3">
                            <span class="text-lg font-bold text-[var(--color-primary)]">{{ substr($result->player->name, 0, 1) }}</span>
                        </div>
                        <h2 class="text-xl font-semibold text-app-primary">{{ $result->player->name }}</h2>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-app-tertiary">Running Total</p>
                        <p class="text-2xl font-bold text-[var(--color-primary)]" data-player-total="{{ $result->id }}">0</p>
                    </div>
                </div>

                {{-- Add Round Score --}}
                <div class="grid grid-cols-1 sm:grid-cols-[1fr_auto] gap-3 mb-4">
                    <input type="number" step="1" data-round-input="{{ $result->id }}" placeholder="Round score..."
                           class="w-full px-4 py-3 border border-app-dark rounded-lg bg-app-primary text-app-primary focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent transition-shadow">
                    <button type="button" data-add-round="{{ $result->id }}"
                            class="px-5 py-3 bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] text-white font-semibold rounded-lg transition-colors">
                        Add Round
                    </button>
                </div>

                {{-- Round Log --}}
                <div>
                    <h3 class="text-sm font-semibold text-app-primary mb-2">Rounds</h3>
                    <div class="space-y-2" data-round-list="{{ $result->id }}">
                        <p class="text-sm text-app-tertiary" data-empty="{{ $result->id }}">No rounds recorded yet.</p>
                    </div>
                </div>

                <input type="hidden" name="scores[{{ $result->id }}][total]" data-total-input="{{ $result->id }}" value="0">
                <input type="hidden" name="scores[{{ $result->id }}][rounds_json]" data-rounds-json="{{ $result->id }}" value="[]">
            </div>
        @endforeach

        {{-- Sticky Bottom Actions --}}
        <div class="fixed bottom-0 left-0 right-0 md:left-64 bg-app-elevated border-t border-app p-4 z-20">
            <div class="max-w-4xl mx-auto flex items-center space-x-3">
                <button type="submit" 
                        class="flex-1 flex items-center justify-center px-6 py-3 bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] text-white font-semibold rounded-lg transition-colors">
                    <x-heroicon-s-check class="w-5 h-5 mr-2"/>
                    Save Scores
                </button>
                
                @if(!$session->completed_at)
                    <button type="button" 
                            id="complete-session-btn"
                            onclick="document.getElementById('completeForm').submit();"
                            {{ $canComplete ? '' : 'disabled' }}
                            class="px-6 py-3 bg-[var(--color-success)] hover:bg-[var(--color-success)] text-white font-semibold rounded-lg transition-colors flex items-center">
                        <x-heroicon-s-check-circle class="w-5 h-5 mr-2"/>
                        Complete
                    </button>
                @endif
            </div>
        </div>
    </form>
    
    {{-- Complete Session Form (Hidden) --}}
    @if(!$session->completed_at)
        <form id="completeForm" action="{{ route('game_sessions.complete', $session) }}" method="POST" class="hidden">
            @csrf
            @method('PATCH')
        </form>
    @endif
</div>

<script>
const scorebookState = @js($scorebookState);

function normalizeState(resultId) {
    if (!scorebookState[resultId]) {
        scorebookState[resultId] = { rounds: [], total: 0 };
    }

    const rounds = Array.isArray(scorebookState[resultId].rounds)
        ? scorebookState[resultId].rounds.map((value) => Number(value) || 0)
        : [];

    scorebookState[resultId].rounds = rounds;
    scorebookState[resultId].total = rounds.reduce((sum, value) => sum + value, 0);
}

function renderPlayerRounds(resultId) {
    normalizeState(resultId);

    const list = document.querySelector(`[data-round-list="${resultId}"]`);
    const empty = document.querySelector(`[data-empty="${resultId}"]`);
    const totalEl = document.querySelector(`[data-player-total="${resultId}"]`);
    const totalInput = document.querySelector(`[data-total-input="${resultId}"]`);
    const roundsJsonInput = document.querySelector(`[data-rounds-json="${resultId}"]`);
    const rounds = scorebookState[resultId].rounds;

    list.querySelectorAll('[data-round-item]').forEach((node) => node.remove());

    if (rounds.length === 0) {
        empty.classList.remove('hidden');
    } else {
        empty.classList.add('hidden');
        rounds.forEach((roundScore, index) => {
            const item = document.createElement('div');
            item.setAttribute('data-round-item', '1');
            item.className = 'flex items-center justify-between px-3 py-2 rounded-lg bg-app-secondary';
            item.innerHTML = `
                <span class="text-sm text-app-secondary">Round ${index + 1}</span>
                <div class="flex items-center gap-3">
                    <span class="font-semibold text-app-primary">${roundScore}</span>
                    <button type="button" class="text-[var(--color-error)] hover:underline text-xs" data-remove-round="${resultId}" data-round-index="${index}">Remove</button>
                </div>
            `;
            list.appendChild(item);
        });
    }

    totalEl.textContent = String(scorebookState[resultId].total);
    totalInput.value = String(scorebookState[resultId].total);
    roundsJsonInput.value = JSON.stringify(rounds);

    updateCompletionAvailability();
}

function addRound(resultId) {
    const input = document.querySelector(`[data-round-input="${resultId}"]`);
    const value = Number(input.value);

    if (!Number.isFinite(value)) {
        return;
    }

    scorebookState[resultId].rounds.push(value);
    input.value = '';
    renderPlayerRounds(resultId);
}

function removeRound(resultId, roundIndex) {
    scorebookState[resultId].rounds.splice(roundIndex, 1);
    renderPlayerRounds(resultId);
}

function updateCompletionAvailability() {
    const targetInput = document.getElementById('target_score');
    const completeBtn = document.getElementById('complete-session-btn');
    if (!completeBtn) {
        return;
    }

    const target = Number(targetInput?.value || 0);
    if (!target || target < 1) {
        completeBtn.disabled = false;
        completeBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        return;
    }

    const reached = Object.values(scorebookState).some((playerState) => Number(playerState.total || 0) >= target);
    completeBtn.disabled = !reached;
    completeBtn.classList.toggle('opacity-50', !reached);
    completeBtn.classList.toggle('cursor-not-allowed', !reached);
}

document.addEventListener('DOMContentLoaded', () => {
    Object.keys(scorebookState).forEach((resultId) => {
        renderPlayerRounds(resultId);

        const addButton = document.querySelector(`[data-add-round="${resultId}"]`);
        const input = document.querySelector(`[data-round-input="${resultId}"]`);
        addButton?.addEventListener('click', () => addRound(resultId));
        input?.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();
                addRound(resultId);
            }
        });
    });

    document.addEventListener('click', (event) => {
        const removeButton = event.target.closest('[data-remove-round]');
        if (!removeButton) {
            return;
        }

        const resultId = removeButton.getAttribute('data-remove-round');
        const roundIndex = Number(removeButton.getAttribute('data-round-index'));
        removeRound(resultId, roundIndex);
    });

    document.getElementById('target_score')?.addEventListener('input', updateCompletionAvailability);
    updateCompletionAvailability();

    const completeBtn = document.getElementById('complete-session-btn');
    if (completeBtn?.disabled) {
        completeBtn.classList.add('opacity-50', 'cursor-not-allowed');
    }
});
</script>
@endsection
