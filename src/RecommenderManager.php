<?php

namespace Aldeebhasan\LaravelCF;

use Aldeebhasan\LaravelCF\Contracts\RecommenderIU;
use Aldeebhasan\LaravelCF\Models\Relation;
use Aldeebhasan\LaravelCF\Recommender\ItemBasedRecommender;
use Aldeebhasan\LaravelCF\Recommender\UserBasedRecommender;
use Illuminate\Database\Eloquent\Model;

class RecommenderManager
{
    private static $instance = null;

    public static function make(): static
    {
        return self::$instance = self::$instance ?: new static();
    }

    public function getItemBasedRecommender(string $type): RecommenderIU
    {
        return (new ItemBasedRecommender())
            ->construct($type);
    }

    public function getUserBasedRecommender(string $type): RecommenderIU
    {
        return (new UserBasedRecommender())
            ->construct($type);
    }

    public function addPurchase(int|string|Model $user, int|string|Model $item, float $amount)
    {
        $this->addRelation($user, $item, $amount, Relation::TYPE_PURCHASE);
    }

    public function addCartAddition(int|string|Model $user, int|string|Model $item, float $amount)
    {
        $this->addRelation($user, $item, $amount, Relation::TYPE_SHOP);
    }

    public function addRating(int|string|Model $user, int|string|Model $item, float $amount)
    {
        $this->addRelation($user, $item, $amount, Relation::TYPE_RATE);
    }

    public function addBookmark(int|string|Model $user, int|string|Model $item, float $amount)
    {
        $this->addRelation($user, $item, $amount, Relation::TYPE_BOOKMARK);
    }

    private function addRelation(int|string|Model $user, int|string|Model $item, float $amount, string $type)
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
