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
}
