<?php

namespace Aldeebhasan\FastRecommender\Recommender;

class UserBasedRecommender extends AbstractRecommender
{
    public function train(): self
    {
        return $this;
    }

    public function recommendTo(array|string|int $data, $top = 1): array
    {
        // Generate recommendations based on user's purchase history
        $recommendations = [];

        // Sort recommendations by similarity score
        arsort($recommendations);
        // Get top N recommendations
        return array_slice($recommendations, 0, $top, true);
    }
}
