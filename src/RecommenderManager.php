<?php

namespace Aldeebhasan\LaravelCF;

use Aldeebhasan\LaravelCF\Contracts\RecommenderIU;
use Aldeebhasan\LaravelCF\Enums\RelationType;
use Aldeebhasan\LaravelCF\Models\Relation;
use Aldeebhasan\LaravelCF\Recommender\ItemBasedRecommender;
use Aldeebhasan\LaravelCF\Recommender\UserBasedRecommender;
use Illuminate\Database\Eloquent\Model;

class RecommenderManager
{
    public function getItemBasedRecommender(RelationType $type): RecommenderIU
    {
        return (new ItemBasedRecommender())
            ->construct($type);
    }

    public function getUserBasedRecommender(RelationType $type): RecommenderIU
    {
        return (new UserBasedRecommender())
            ->construct($type);
    }

    public function addPurchase(int|string|Model $user, int|string|Model $item, float $amount, string $group = '*')
    {
        $this->addRelation($user, $item, $amount, RelationType::PURCHASE, $group);
    }

    public function addCartAddition(int|string|Model $user, int|string|Model $item, float $amount, string $group = '*')
    {
        $this->addRelation($user, $item, $amount, RelationType::CART_ACTION, $group);
    }

    public function addRating(int|string|Model $user, int|string|Model $item, float $amount, string $group = '*')
    {
        $this->addRelation($user, $item, $amount, RelationType::RATE, $group);
    }

    public function addBookmark(int|string|Model $user, int|string|Model $item, float $amount, string $group = '*')
    {
        $this->addRelation($user, $item, $amount, RelationType::BOOKMARK, $group);
    }

    public function remove(int|string|Model $user, int|string|Model $item, RelationType $type = null, string $group = '*')
    {
        $source = $user instanceof Model ? $user->id : $user;
        $target = $item instanceof Model ? $item->id : $item;

        Relation::where([
            'group' => $group,
            'source' => $source,
            'target' => $target,
        ])->when($type, fn ($q) => $q->where('type', $type))
            ->delete();
    }

    public function flush()
    {
        Relation::truncate();
    }

    private function addRelation(int|string|Model $user, int|string|Model $item, float $amount, RelationType $type, string $group = '*')
    {
        $source = $user instanceof Model ? $user->id : $user;
        $target = $item instanceof Model ? $item->id : $item;
        Relation::updateOrCreate([
            'group' => $group,
            'source' => $source,
            'target' => $target,
            'type' => $type,
        ], [
            'value' => $amount,
        ]);
    }
}
