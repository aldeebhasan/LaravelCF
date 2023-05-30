<?php

namespace Aldeebhasan\FastRecommender\Recommender;

interface RecommenderIU
{
    public function construct(string $type): self;

    public function train(): self;

    public function recommendTo(array|int $sources, $top = 10): array;
}
