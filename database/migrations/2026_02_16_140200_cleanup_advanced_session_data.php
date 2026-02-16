<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::table('game_session_results')
            ->whereNotNull('custom_score')
            ->orderBy('id')
            ->chunkById(200, function ($rows): void {
                foreach ($rows as $row) {
                    $decoded = is_array($row->custom_score)
                        ? $row->custom_score
                        : json_decode((string) $row->custom_score, true);

                    if (!is_array($decoded)) {
                        continue;
                    }

                    if (($decoded['mode'] ?? null) === 'scorebook') {
                        continue;
                    }

                    $total = 0;
                    if (isset($decoded['total']) && is_numeric($decoded['total'])) {
                        $total = (int) $decoded['total'];
                    } elseif (isset($decoded['_total']) && is_numeric($decoded['_total'])) {
                        $total = (int) $decoded['_total'];
                    } elseif (isset($decoded['_points']) && is_numeric($decoded['_points'])) {
                        $total = (int) $decoded['_points'];
                    }

                    $normalized = [
                        'mode' => 'scorebook',
                        'helper' => 'round_points',
                        'rounds' => [],
                        'total' => $total,
                    ];

                    DB::table('game_session_results')
                        ->where('id', $row->id)
                        ->update(['custom_score' => json_encode($normalized)]);
                }
            });
    }

    public function down(): void
    {
        // Irreversible cleanup: old advanced payloads are intentionally removed.
    }
};
