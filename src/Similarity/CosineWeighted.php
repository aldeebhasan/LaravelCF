<?php

namespace Aldeebhasan\LaravelCF\Similarity;

class CosineWeighted extends Cosine
{
    private array $weights = [];

    protected function afterConstructHook(): void
    {
        //calculate the weight of each user values based on their total number of values .
        $total = 0;
        $data = $this->transpose();
        foreach ($data as $source => $targets) {
            $totalCount = count($targets);
            $this->weights[$source] = ($this->weights[$source] ?? 0) + $totalCount;
            $total += $totalCount;
        }
        $this->weights = array_map(fn ($value) => round($value / $total, 4), $this->weights);
    }

    protected function prepareInputVectors(array $a, array $b): array
    {
        list($a, $b) = parent::prepareInputVectors($a, $b);
        array_walk($a, fn (&$value, $key) => $value = $value * $this->weights[$key]);
        array_walk($b, fn (&$value, $key) => $value = $value * $this->weights[$key]);

        return [$a, $b];
    }
}
