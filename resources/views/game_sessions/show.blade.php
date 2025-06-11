@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-10">
    <h1 class="text-3xl font-bold mb-6">Game Session: {{ $session->game->name }}</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('game_sessions.update_results', $session) }}" method="POST">
        @csrf
        <div class="space-y-6">
            @foreach($session->results as $result)
                <div x-data="scoreEntry({{ json_encode($scoringRules['fields'] ?? []) }}, @json($result->custom_score ?? []))" class="border p-4 rounded shadow">
                    <h2 class="text-xl font-semibold mb-4">{{ $result->player->name }}</h2>

                    <template x-for="field in fields" :key="field.key">
                        <div class="mb-3">
                            <label class="block font-medium" x-text="field.label"></label>
                            <template x-if="field.type === 'number'">
                                <input type="number" :name="'scores[' + {{ $result->id }} + '][data][' + field.key + ']'" x-model.number="values[field.key]" class="w-full border p-2 rounded">
                            </template>
                            <template x-if="field.type === 'checkbox'">
                                <input type="checkbox" :name="'scores[' + {{ $result->id }} + '][data][' + field.key + ']'" x-model="values[field.key]" value="1" class="mr-2">
                            </template>
                        </div>
                    </template>

                    <input type="hidden" name="scores[{{ $result->id }}][total]" :value="total">
                    <p class="mt-2 font-semibold text-purple-700">Total: <span x-text="total"></span></p>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save Scores</button>
        </div>
    </form>

    @if(!$session->completed_at)
        <form action="{{ route('game_sessions.complete', $session) }}" method="POST" class="mt-4">
            @csrf
            @method('PATCH')
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Mark as Completed</button>
        </form>
    @endif
</div>

<script>
function scoreEntry(fields, initial) {
    return {
        fields,
        values: initial || {},
        get total() {
            let sum = 0;
            for (const field of this.fields) {
                let val = this.values[field.key];
                if (field.type === 'checkbox') {
                    if (val) sum += field.points ?? 0;
                } else if (field.type === 'number') {
                    let num = Number(val) || 0;
                    if (field.multiplier) {
                        sum += num * field.multiplier;
                    } else {
                        sum += num;
                    }
                }
            }
            return sum;
        },
    };
}
</script>
@endsection
