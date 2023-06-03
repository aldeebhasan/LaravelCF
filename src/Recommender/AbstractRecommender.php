<?php

namespace Aldeebhasan\LaravelCF\Recommender;

use Aldeebhasan\LaravelCF\Contracts\RecommenderIU;
use Aldeebhasan\LaravelCF\Contracts\SimilarityIU;
use Aldeebhasan\LaravelCF\Enums\MissingValue;
use Aldeebhasan\LaravelCF\Models\Relation;
use Aldeebhasan\LaravelCF\Similarity\Cosine;
use http\Exception\InvalidArgumentException;

class AbstractRecommender implements RecommenderIU
{
    protected array $similarityMatrix = [];

    protected array $data = [];

    protected SimilarityIU $similarityFn;

    public function construct(string $type): self
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

    public function setSimilarityFunction(string $similarity, $fillMissingValue = false, $fillingMethod = MissingValue::ZERO): self
    {
        if (! class_exists($similarity)) {
            throw new InvalidArgumentException('Similarity class not found');
        }
        $this->similarityFn = new $similarity($this->data, $fillMissingValue, $fillingMethod);

        return $this;
    }

    public function train(): self
    {
        return $this;
    }

    public function recommendTo(array|string|int $sources, $top = 1): array
    {
        return [];
    }
}
