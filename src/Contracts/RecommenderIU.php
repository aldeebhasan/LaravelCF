<?php

namespace Aldeebhasan\LaravelCF\Contracts;

use Aldeebhasan\LaravelCF\Enums\MissingValue;

interface RecommenderIU
{
    public function construct(string $type): self;

    public function train(): self;

    public function recommendTo(array|string|int $sources, $top = 10): array;

    public function setSimilarityFunction(string $similarity, $missing = MissingValue::ZERO): self;
}
