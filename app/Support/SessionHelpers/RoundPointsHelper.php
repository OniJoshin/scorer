<?php

namespace App\Support\SessionHelpers;

use Illuminate\Support\Collection;

class RoundPointsHelper implements SessionHelperInterface
{
    public function key(): string
    {
        return 'round_points';
    }

    public function label(): string
    {
        return 'Round Points';
    }

    public function description(): string
    {
        return 'Track per-round points and running totals.';
    }

    public function defaultTargetScore(): ?int
    {
        return null;
    }

    public function canComplete(Collection $results, ?int $targetScore): bool
    {
        if (!$targetScore) {
            return true;
        }

        return $results->contains(function ($result) use ($targetScore) {
            return (int) ($result->custom_score['total'] ?? 0) >= $targetScore;
        });
    }
}
