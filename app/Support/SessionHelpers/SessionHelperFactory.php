<?php

namespace App\Support\SessionHelpers;

class SessionHelperFactory
{
    /**
     * @return array<string, SessionHelperInterface>
     */
    public function all(): array
    {
        $helpers = [
            new RoundPointsHelper(),
            new FlipSevenHelper(),
        ];

        $indexed = [];
        foreach ($helpers as $helper) {
            $indexed[$helper->key()] = $helper;
        }

        return $indexed;
    }

    public function resolve(?string $key): SessionHelperInterface
    {
        $helpers = $this->all();

        if ($key && isset($helpers[$key])) {
            return $helpers[$key];
        }

        return $helpers['round_points'];
    }
}
