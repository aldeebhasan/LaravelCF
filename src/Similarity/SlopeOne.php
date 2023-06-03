<?php

namespace Aldeebhasan\FastRecommender\Similarity;

class SlopeOne extends AbstractSimilarity
{
    protected array $averages;

    protected function afterConstructHook(): void
    {
        $total = 0;
        foreach ($this->data as $targets) {
            foreach ($targets as $target => $value) {
                $this->averages[$target] = ($this->weights[$target] ?? 0) + $value;
                $total++;
            }
        }
        $this->averages = array_map(fn ($value) => round($value / $total, 4), $this->averages);
    }

    public function getSimilarity(array $a, array $b): float
    {
        list($a, $b) = $this->prepareInputVectors($a, $b);

        $deviation = array_map(fn ($x, $y) => $x - $y, $a, $b);
        $N = count($deviation);
        $avgDeviation = array_sum($deviation) / $N;

        $similarity = array_map(function ($x, $key) use ($avgDeviation, $N) {
            return $avgDeviation + ($x - $this->averages[$key]) / $N;
        }, $a, array_keys($a));

        if ($N <= 0) {
            return 0;
        }

        return round(array_sum($similarity) / $N, 2);
    }
}
