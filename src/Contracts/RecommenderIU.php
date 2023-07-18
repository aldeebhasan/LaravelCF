<?php

namespace Aldeebhasan\LaravelCF\Contracts;

use Aldeebhasan\LaravelCF\Enums\MissingValue;
use Aldeebhasan\LaravelCF\Enums\RelationType;

interface RecommenderIU
{
    public function construct(RelationType $type): self;

    public function train(): self;

    public function recommendTo(array|string|int $sources, $top = 10, $except = []): array;

    public function userItemSimilarity($user, $item): float;

    public function setSimilarityFunction(string $similarity, $fillingMethod = MissingValue::ZERO, $shouldFill = true): self;
}
