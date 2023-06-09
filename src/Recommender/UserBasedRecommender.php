<?php

namespace Aldeebhasan\LaravelCF\Recommender;

use Aldeebhasan\LaravelCF\Enums\RelationType;
use Aldeebhasan\LaravelCF\Models\Relation;
use Aldeebhasan\LaravelCF\Similarity\Cosine;

class UserBasedRecommender extends AbstractRecommender
{
    public function construct(RelationType $type): AbstractRecommender
    {
        $this->data = Relation::where('type', $type)->get()->groupBy('source')
            ->mapWithKeys(function ($targets, $source) {
                return [
                    //ex : [user => [item=>rating]]
                    $source => $targets->mapWithKeys(fn ($item) => [$item->target => $item->value]),
                ];
            })->toArray();
        $this->similarityFn = new Cosine($this->data);

        return $this;
    }
}
