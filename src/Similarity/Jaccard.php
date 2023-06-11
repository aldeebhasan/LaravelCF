<?php

namespace Aldeebhasan\LaravelCF\Similarity;

class Jaccard extends AbstractSimilarity
{
    public function getSimilarity(array $a, array $b): float
    {
        $numerator = count(array_intersect_key($a, $b));
        $denominator = count(array_merge($a, $b));

        return round($numerator / $denominator, 2);
    }
}
