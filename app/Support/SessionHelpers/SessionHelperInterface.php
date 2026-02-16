<?php

namespace App\Support\SessionHelpers;

use Illuminate\Support\Collection;

interface SessionHelperInterface
{
    public function key(): string;

    public function label(): string;

    public function description(): string;

    public function defaultTargetScore(): ?int;

    public function canComplete(Collection $results, ?int $targetScore): bool;
}
