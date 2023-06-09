<?php

namespace Aldeebhasan\LaravelCF\Contracts;

use Aldeebhasan\LaravelCF\Enums\MissingValue;
use Aldeebhasan\LaravelCF\Enums\RelationType;

interface RecommenderIU
{
    public function construct(RelationType $type): self;

    public function train(): self;

    public function recommendTo(array|string|int $sources, $top = 10): array;

    public function setSimilarityFunction(string $similarity, $missing = MissingValue::ZERO): self;
}
