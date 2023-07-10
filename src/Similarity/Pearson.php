<?php

namespace Aldeebhasan\LaravelCF\Similarity;

class Pearson extends AbstractSimilarity
{
    public function getSimilarity(array $a, array $b): float
    {
        list($a, $b) = $this->prepareInputVectors($a, $b);

        if (\count($a) === 1 && \end($a) == 0 &&
            \count($b) === 1 && \end($b) == 0) {
            return 1;
        }
        //give very low similarity for none-evaluated items
        if (\count($a) == 0 || count($b) == 0) {
            return -100;
        }

        $meanA = mean($a);
        $meanB = mean($b);
        $numerator = array_sum(array_map(fn ($x, $y) => ($x - $meanA) * ($y - $meanB), $a, $b));
        $denominator = sqrt(diff_from_mean($a) * diff_from_mean($b));

        if ($denominator == 0) {
            return 0;
        }

        return round($numerator / $denominator, 3);
    }
}
