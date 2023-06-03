<?php

namespace Aldeebhasan\FastRecommender\Contracts;

interface SimilarityIU
{
    public function getSimilarity(array $a, array $b): float;
}
