<?php

namespace Aldeebhasan\LaravelCF\Recommender;

use Aldeebhasan\LaravelCF\Enums\RelationType;
use Aldeebhasan\LaravelCF\Models\Relation;
use Aldeebhasan\LaravelCF\Similarity\Cosine;

class ItemBasedRecommender extends AbstractRecommender
{
    public function construct(RelationType $type): AbstractRecommender
    {
        $this->data = Relation::where('type', $type)->get()->groupBy('target')
            ->mapWithKeys(function ($targets, $source) {
                return [
                    //ex : [item => [user=>rating]]
                    $source => $targets->mapWithKeys(fn ($item) => [$item->source => $item->value]),
                ];
            })->toArray();
        $this->similarityFn = new Cosine($this->data);

        return $this;
    }

    public function recommendTo(array|string|int $sources, $top = 10): array
    {
        $sources = (array) $sources;
        $recommendations = [];
        $items = array_keys($this->similarityMatrix);
        foreach ($items as $item) {
            //All items that are not currently handled by the sender
            if (in_array($item, $sources)) {
                continue;
            }

            $weighted_sum = 0;
            $total_similarity = 0;
            foreach ($sources as $source) {
                if (isset($this->similarityMatrix[$item][$source])) {
                    $weighted_sum += $this->similarityMatrix[$item][$source];
                    $total_similarity += 1;
                }
            }
            if ($total_similarity > 0) {
                $recommendations[$item] = round($weighted_sum / $total_similarity, 2);
            }
        }
        arsort($recommendations);

        return array_slice($recommendations, 0, $top, true);
    }
}
