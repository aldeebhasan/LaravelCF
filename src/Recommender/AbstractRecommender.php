<?php

namespace Aldeebhasan\LaravelCF\Recommender;

use Aldeebhasan\LaravelCF\Contracts\RecommenderIU;
use Aldeebhasan\LaravelCF\Contracts\SimilarityIU;
use Aldeebhasan\LaravelCF\Enums\MissingValue;
use Aldeebhasan\LaravelCF\Enums\RelationType;
use http\Exception\InvalidArgumentException;

abstract class AbstractRecommender implements RecommenderIU
{
    protected array $similarityMatrix = [];

    protected array $data = [];

    protected SimilarityIU $similarityFn;

    public function construct(RelationType $type): self
    {
        return $this;
    }

    public function setSimilarityFunction(string $similarity, $fillingMethod = MissingValue::ZERO, $shouldFill = true): self
    {
        if (! class_exists($similarity)) {
            throw new InvalidArgumentException('Similarity class not found');
        }
        $this->similarityFn = new $similarity($this->data, $fillingMethod, $shouldFill);

        return $this;
    }

    public function train(): self
    {
        // Calculate item-item similarity matrix
        foreach ($this->data as $key_1 => $targets_1) {
            foreach ($this->data as $key_2 => $targets_2) {
                if ($key_1 != $key_2 && ! isset($this->similarityMatrix[$key_1][$key_2])) {
                    $similarity = $this->similarityFn->getSimilarity($targets_1, $targets_2);
                    $this->similarityMatrix[$key_1][$key_2] = $similarity;
                    $this->similarityMatrix[$key_2][$key_1] = $similarity;
                }
            }
        }

        return $this;
    }

    abstract public function recommendTo(array|string|int $sources, $top = 10, $except = []): array;

    abstract public function userItemSimilarity($user, $item): float;
}
