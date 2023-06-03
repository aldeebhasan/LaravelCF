<?php

namespace Aldeebhasan\LaravelCF\Similarity;

use MathPHP\Statistics\Distance;

class WeightedCosine extends AbstractSimilarity
{
    private array $weights = [];

    protected function afterConstructHook(): void
    {
        //calculate the weight of each user rating based on their total number of ratings.
        $total = 0;
        foreach ($this->data as $targets) {
            $targets = array_keys($targets);
            foreach ($targets as $target) {
                $this->weights[$target] = ($this->weights[$target] ?? 0) + 1;
                $total++;
            }
        }
        $this->weights = array_map(fn ($value) => round($value / $total, 4), $this->weights);
    }

    public function getSimilarity(array $a, array $b): float
    {
        list($a, $b) = $this->prepareInputVectors($a, $b);

        array_walk($a, fn (&$value, $key) => $value = $value * $this->weights[$key]);
        array_walk($b, fn (&$value, $key) => $value = $value * $this->weights[$key]);

        return round(Distance::cosineSimilarity($a, $b), 2);
    }
}
