<?php

namespace App\Services;

class MCRNGService
{
    public static function generate(int $seed, int $totalQuestions, int $limit): array
    {
        if ($totalQuestions <= 0 || $limit <= 0) {
            return [];
        }

        $limit = min($limit, $totalQuestions);

        // Multiplicative Congruential (Park-Miller)
        $m = 2147483647; // 2^31 - 1
        $a = 48271;

        $x = $seed % $m;
        if ($x <= 0) {
            $x = 1;
        }

        $picked = [];
        $seen = [];

        while (count($picked) < $limit) {
            $x = ($a * $x) % $m;
            $idx = $x % $totalQuestions;

            if (isset($seen[$idx])) {
                continue;
            }

            $seen[$idx] = true;
            $picked[] = $idx;
        }

        return $picked;
    }
}
