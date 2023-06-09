<?php

namespace Aldeebhasan\LaravelCF\Similarity;

class Cosine extends AbstractSimilarity
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

        $cosine = 1 - (dotProduct($a, $b) / (l2Norm($a) * l2Norm($b)));

        return round(1 - $cosine, 2);
    }
}
