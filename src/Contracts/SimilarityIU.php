<?php

namespace Aldeebhasan\LaravelCF\Contracts;

interface SimilarityIU
{
    public function getSimilarity(array $a, array $b): float;
}
