<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('scores', 'played_at')) {
            Schema::table('scores', function (Blueprint $table) {
                $table->timestamp('played_at')->nullable()->after('points');
            });
        }

        $this->ensureIndex('scores', 'scores_player_id_index', ['player_id']);
        $this->ensureIndex('scores', 'scores_game_id_index', ['game_id']);

        if ($this->indexExists('scores', 'scores_player_id_game_id_unique')) {
            Schema::table('scores', function (Blueprint $table) {
                $table->dropUnique('scores_player_id_game_id_unique');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('scores', 'played_at')) {
            Schema::table('scores', function (Blueprint $table) {
                $table->dropColumn('played_at');
            });
        }

        if (!$this->indexExists('scores', 'scores_player_id_game_id_unique')) {
            $hasDuplicatePairs = DB::table('scores')
                ->selectRaw('player_id, game_id, COUNT(*) as score_count')
                ->groupBy('player_id', 'game_id')
                ->havingRaw('COUNT(*) > 1')
                ->exists();

            if (!$hasDuplicatePairs) {
                Schema::table('scores', function (Blueprint $table) {
                    $table->unique(['player_id', 'game_id']);
                });
            }
        }

        if ($this->indexExists('scores', 'scores_player_id_index')) {
            Schema::table('scores', function (Blueprint $table) {
                $table->dropIndex('scores_player_id_index');
            });
        }

        if ($this->indexExists('scores', 'scores_game_id_index')) {
            Schema::table('scores', function (Blueprint $table) {
                $table->dropIndex('scores_game_id_index');
            });
        }
    }

    protected function ensureIndex(string $table, string $indexName, array $columns): void
    {
        if ($this->indexExists($table, $indexName)) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($columns, $indexName) {
            $blueprint->index($columns, $indexName);
        });
    }

    protected function indexExists(string $table, string $indexName): bool
    {
        $result = DB::selectOne('SHOW INDEX FROM `' . $table . '` WHERE Key_name = ?', [$indexName]);

        return $result !== null;
    }
};
