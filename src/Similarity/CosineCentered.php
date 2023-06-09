<?php

namespace Aldeebhasan\LaravelCF\Similarity;

use Aldeebhasan\LaravelCF\Enums\MissingValue;

class CosineCentered extends Cosine
{
    protected function prepareInputVectors(array $a, array $b): array
    {
        $this->fillingMethod = MissingValue::ZERO;
        $this->fillMissingValue = true;
        list($a, $b) = parent::prepareInputVectors($a, $b);
        $avgA = array_sum($a) / count($a);
        $avgB = array_sum($b) / count($b);

        array_walk($a, fn (&$value, $key) => $value = ($value != 0) ? ($value - $avgA) : $value);
        array_walk($b, fn (&$value, $key) => $value = ($value != 0) ? ($value - $avgB) : $value);

        return [$a, $b];
    }
}
