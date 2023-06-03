<?php

namespace Aldeebhasan\LaravelCF\Similarity;

use Aldeebhasan\LaravelCF\Contracts\SimilarityIU;
use Aldeebhasan\LaravelCF\Enums\MissingValue;

abstract class AbstractSimilarity implements SimilarityIU
{
    public function __construct(
        protected array $data,
        protected bool $fillMissingValue = false,
        protected MissingValue $fillingMethod = MissingValue::ZERO,
    ) {
        $this->afterConstructHook();
    }

    protected function afterConstructHook(): void
    {
        //do nothing
    }

    abstract public function getSimilarity(array $a, array $b): float;

    protected function prepareInputVectors(array $a, array $b): array
    {
        if ($this->fillMissingValue) {
            $missingA = array_fill_keys(array_keys(array_diff_key($b, $a)), $this->getMissingValue($a));
            $missingB = array_fill_keys(array_keys(array_diff_key($a, $b)), $this->getMissingValue($b));
            $a = $a + $missingA;
            $b = $b + $missingB;
        } else {
            $a = array_intersect_key($a, $b);
            $b = array_intersect_key($b, $a);
        }

        return [$a, $b];
    }

    protected function getMissingValue(array $a): float
    {
        switch ($this->fillingMethod) {
            case  MissingValue::MEAN:
                return array_sum($a) / count($a);
            case  MissingValue::MEDIAN:
                asort($a);

                return $a[(int) (count($a) / 2)];
            default:
                return 0;
        }
    }
}
