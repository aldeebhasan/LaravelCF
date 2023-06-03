<?php

namespace Aldeebhasan\FastRecommender\Recommender;

class ItemBasedRecommender extends AbstractRecommender
{
    public function train(): self
    {
        // Calculate item-item similarity matrix
        foreach ($this->data as $item_1 => $targets_1) {
            foreach ($this->data as $item_2 => $targets_2) {
                if ($item_1 != $item_2 && ! isset($this->similarityMatrix[$item_1][$item_2])) {
                    $similarity = $this->similarityFn->getSimilarity($targets_1, $targets_2);
                    $this->similarityMatrix[$item_1][$item_2] = $similarity;
                    $this->similarityMatrix[$item_2][$item_1] = $similarity;
                }
            }
        }

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
