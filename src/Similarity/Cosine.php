<?php

namespace Aldeebhasan\FastRecommender\Similarity;

use MathPHP\Statistics\Distance;

class Cosine extends AbstractSimilarity
{
    public function getSimilarity(array $a, array $b): float
    {
        list($a, $b) = $this->prepareInputVectors($a, $b);

        return round(Distance::cosineSimilarity($a, $b), 2);
    }
}
