@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6 text-purple-700">➕ Add Game</h1>

<form action="{{ route('games.store') }}" method="POST" class="space-y-6">
    @csrf

    <div>
        <label class="block text-sm font-medium text-gray-700">Game Name</label>
        <input type="text" name="name" value="{{ old('name') }}" required
               class="mt-1 block w-full rounded border-gray-300 shadow-sm">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Played At</label>
        <input type="date" name="played_at" value="{{ old('played_at') }}"
               class="mt-1 block w-full rounded border-gray-300 shadow-sm">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Notes (optional)</label>
        <textarea name="notes" rows="3" class="mt-1 block w-full rounded border-gray-300 shadow-sm">{{ old('notes') }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Points Per Position</label>
        <div class="grid grid-cols-2 gap-4">
            @for($i = 1; $i <= 4; $i++)
                <div>
                    <label class="block text-sm">{{ $i }}{{ $i==1?'st':($i==2?'nd':($i==3?'rd':'th')) }} Place</label>
                    <input type="number" name="position_points[{{ $i }}]" value="{{ old('position_points.'.$i) }}" min="0"
                           class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                </div>
            @endfor
        </div>
    </div>

    <div x-data="scoringRuleBuilder()" class="mt-6 bg-purple-50 p-4 rounded">
        <h3 class="text-lg font-semibold mb-3">Scoring Rules</h3>

        <template x-for="(field, index) in fields" :key="index">
            <div class="grid grid-cols-5 gap-2 mb-2 items-center">
                <input type="text" class="border rounded px-2 py-1 col-span-2"
                    placeholder="Label" x-model="field.label">

                <input type="text" class="border rounded px-2 py-1"
                    placeholder="Key" x-model="field.key">

                <select class="border rounded px-2 py-1" x-model="field.type">
                    <option value="number">Number</option>
                    <option value="checkbox">Checkbox</option>
                </select>

                <div class="flex gap-1 items-center">
                    <input type="number" step="any" class="border rounded px-2 py-1 w-20"
                        placeholder="Mult" x-model="field.multiplier">
                    <input type="number" class="border rounded px-2 py-1 w-20"
                        placeholder="Points" x-model="field.points">
                    <button type="button" class="text-red-600 font-bold" @click="removeField(index)">✕</button>
                </div>
            </div>
        </template>

        <button type="button" class="mt-2 px-3 py-1 bg-purple-600 text-white rounded"
                @click="addField()">Add Rule Field</button>

        <!-- Hidden JSON field -->
        <textarea name="scoring_rules" x-model="json" hidden></textarea>
    </div>


    <div class="pt-4">
        <button type="submit"
                class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 shadow">
            Save Game
        </button>
        <a href="{{ route('games.index') }}" class="inline-block ml-3 text-sm text-gray-600 hover:underline">Cancel</a>
    </div>
</form>

<script>
function scoringRuleBuilder() {
    return {
        fields: @json(old('scoring_rules', [])),
        addField() {
            this.fields.push({ label: '', key: '', type: 'number', multiplier: null, points: null });
        },
        removeField(index) {
            this.fields.splice(index, 1);
        },
        get json() {
            return JSON.stringify({
                fields: this.fields,
                auto_rank: 'sum'
            });
        }
    }
}
</script>

@endsection
