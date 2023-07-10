<?php

namespace Aldeebhasan\LaravelCF\Recommender;

use Aldeebhasan\LaravelCF\Enums\RelationType;
use Aldeebhasan\LaravelCF\Models\Relation;
use Aldeebhasan\LaravelCF\Similarity\Pearson;

class UserBasedRecommender extends AbstractRecommender
{
    private $neibours = 25;

    private $items = [];

    public function construct(RelationType $type): AbstractRecommender
    {
        $this->data = Relation::where('type', $type)->get()->groupBy('source')
            ->mapWithKeys(function ($targets, $source) {
                return [
                    //ex : [user => [item=>rating]]
                    $source => $targets->mapWithKeys(fn ($item) => [$item->target => $item->value]),
                ];
            })->toArray();
        $this->items = Relation::where('type', $type)->pluck('target')->toArray();
        $this->similarityFn = (new Pearson($this->data));

        return $this;
    }

    public function recommendTo(array|int|string $sources, $top = 10): array
    {
        $sources = (array) $sources;
        $recommendations = [];

        foreach ($this->items as $item) {
            if (in_array($item, array_keys($this->data[$sources[0]]))) {
                continue;
            }

            $weightedSum = 0;
            $totalSimilarity = 0;
            foreach ($sources as $source) {
                if (isset($this->similarityMatrix[$source])) {
                    $totalSimilarity += 1;
                    $weightedSum += $this->getUserItemSimilarity($item, $source);
                }
            }
            if ($totalSimilarity > 0) {
                $recommendations[$item] = round($weightedSum / $totalSimilarity, 2);
            }
        }
        arsort($recommendations);

        return array_slice($recommendations, 0, $top, true);
    }

    private function getUserItemSimilarity($item, $user): float
    {
        //get top n neighbours
        $users = $this->similarityMatrix[$user];
        arsort($users);
        $nearestNeighbours = array_slice($users, 0, $this->neibours, true);

        //calculate the similarity based on the selected neighbours
        $avg = mean($this->data[$user]);
        $weightAvg = mean($this->similarityMatrix[$user]);

        $similarity = 0;
        foreach (array_keys($nearestNeighbours) as $neighbour) {
            $neighbourAvg = mean($this->data[$neighbour]);
            $similarity += (($this->data[$neighbour][$item] ?? 0) - $neighbourAvg) * $this->similarityMatrix[$user][$neighbour];
        }

        return $avg + ($similarity / $weightAvg);
    }
}
