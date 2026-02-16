<?php

namespace App\Support\SessionHelpers;

use Illuminate\Support\Collection;

class FlipSevenHelper implements SessionHelperInterface
{
    public function key(): string
    {
        return 'flip7';
    }

    public function label(): string
    {
        return 'Flip 7';
    }

    public function description(): string
    {
        return 'Round points race to target score.';
    }

    public function defaultTargetScore(): ?int
    {
        return 200;
    }

    public function canComplete(Collection $results, ?int $targetScore): bool
    {
        $target = $targetScore ?: $this->defaultTargetScore();

        return $results->contains(function ($result) use ($target) {
            return (int) ($result->custom_score['total'] ?? 0) >= $target;
        });
    }
}
