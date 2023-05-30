<?php

namespace Aldeebhasan\FastRecommender\Recommender;

use Aldeebhasan\FastRecommender\Model\Relation;

class ItemBasedRecommender implements RecommenderIU
{
    private array $similarityMatrix = [];
    private array $data = [];

    public function construct(string $type): self
    {
        $this->data = Relation::where('type', $type)->get()->groupBy('source')
            ->mapWithKeys(function ($targets, $source) {
                return [
                    $source => $targets->mapWithKeys(fn($item) => [$item->target => $item->value])
                ];
            })->toArray();
        return $this;
    }

    public function train(): self
    {
        // Calculate item-item similarity matrix
        foreach ($this->data as $source_1 => $target_1) {
            foreach ($this->data as $source_2 => $target_2) {
                if ($source_1 != $source_2 && !isset($this->similarityMatrix[$source_1][$source_2])) {
                    $targetKeys_1 = array_keys($target_1);
                    $targetKeys_2 = array_keys($target_2);
                    $similarity = count(array_intersect($targetKeys_1, $targetKeys_2)) / count(array_unique(array_merge($targetKeys_1, $targetKeys_2)));
                    $this->similarityMatrix[$source_1][$source_2] = $similarity;
                    $this->similarityMatrix[$source_2][$source_1] = $similarity;
                }
            }
        }
        return $this;
    }

    public function recommendTo(array|int $sources, $top = 10): array
    {
        $sources = (array)$sources;
        $recommendations = [];
        $items = array_keys($this->similarityMatrix);
        foreach ($items as $item) {
            //All items that are not currently handled by the sender
            if (in_array($item, $sources)) continue;
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
        // Sort recommendations by similarity score
        arsort($recommendations);
        // Get top N recommendations
        return array_slice($recommendations, 0, $top, true);
    }


}
