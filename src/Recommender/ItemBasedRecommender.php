<?php

namespace Aldeebhasan\LaravelCF\Recommender;

use Aldeebhasan\LaravelCF\Enums\RelationType;
use Aldeebhasan\LaravelCF\Models\Relation;
use Aldeebhasan\LaravelCF\Similarity\Cosine;

class ItemBasedRecommender extends AbstractRecommender
{
    private $neibours = 25;

    public function construct(RelationType $type, $group = '*'): AbstractRecommender
    {
        $this->data = Relation::where(['type' => $type, 'group' => $group])->get()->groupBy('target')
            ->mapWithKeys(function ($targets, $source) {
                return [
                    //ex : [item => [user=>rating]]
                    $source => $targets->mapWithKeys(fn ($item) => [$item->source => $item->value]),
                ];
            })->toArray();
        $this->similarityFn = new Cosine($this->data);

        return $this;
    }

    public function recommendTo(array|string|int $sources, $top = 10, $except = []): array
    {
        $sources = (array) $sources;
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
        $recommendations = array_diff($recommendations, $except);
        arsort($recommendations);

        return array_slice($recommendations, 0, $top, true);
    }

    public function userItemSimilarity($user, $item): float
    {
        //get top n neighbours
        $items = $this->similarityMatrix[$item] ?? [];
        arsort($items);
        $nearestNeighbours = array_slice($items, 0, $this->neibours, true);

        //calculate the similarity based on the selected neighbours
        $avg = mean($this->data[$item] ?? []);
        $weightAvg = mean($this->similarityMatrix[$item] ?? []);

        $similarity = 0;
        foreach (array_keys($nearestNeighbours) as $neighbour) {
//            if (!isset($this->data[$neighbour][$user])) continue;
            $neighbourAvg = mean($this->data[$neighbour]);
            $similarity += (($this->data[$neighbour][$user] ?? 0) - $neighbourAvg) * $this->similarityMatrix[$item][$neighbour];
        }
        if ($similarity == 0) return 0;

        return $avg + ($similarity / $weightAvg);
    }
}
