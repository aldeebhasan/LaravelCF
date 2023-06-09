<?php

namespace Aldeebhasan\LaravelCF\Similarity;

class SlopeOne extends AbstractSimilarity
{
    protected array $averages;

    public function getSimilarity(array $a, array $b): float
    {
        list($a, $b) = $this->prepareInputVectors($a, $b);

        $deviation = array_map(fn ($x, $y) => $x - $y, $a, $b);
        $N = count($deviation);
        $avgDeviation = array_sum($deviation) / $N;
        if ($N <= 0) {
            return 0;
        }

        return round($avgDeviation, 2);
    }
}
