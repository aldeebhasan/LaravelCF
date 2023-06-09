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

    public function addPurchase(int|string|Model $user, int|string|Model $item, float $amount)
    {
        $this->addRelation($user, $item, $amount, RelationType::PURCHASE);
    }

    public function addCartAddition(int|string|Model $user, int|string|Model $item, float $amount)
    {
        $this->addRelation($user, $item, $amount, RelationType::SHOP);
    }

    public function addRating(int|string|Model $user, int|string|Model $item, float $amount)
    {
        $this->addRelation($user, $item, $amount, RelationType::RATE);
    }

    public function addBookmark(int|string|Model $user, int|string|Model $item, float $amount)
    {
        $this->addRelation($user, $item, $amount, RelationType::BOOKMARK);
    }

    public function remove(int|string|Model $user, int|string|Model $item, RelationType $type = null)
    {
        Relation::where([
            'source' => $user,
            'target' => $item,
        ])->when($type, fn ($q) => $q->where('type', $type))
            ->delete();
    }

    public function flush()
    {
        Relation::truncate();
    }

    private function addRelation(int|string|Model $user, int|string|Model $item, float $amount, RelationType $type)
    {
        $source = $user instanceof Model ? $user->id : $user;
        $target = $item instanceof Model ? $item->id : $item;
        Relation::updateOrCreate([
            'source' => $source,
            'target' => $target,
            'type' => $type,
        ], [
            'value' => $amount,
        ]);
    }
}
