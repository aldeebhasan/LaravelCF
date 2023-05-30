<?php

namespace Aldeebhasan\FastRecommender;

use Aldeebhasan\FastRecommender\Model\Relation;
use Aldeebhasan\FastRecommender\Recommender\ItemBasedRecommender;
use Aldeebhasan\FastRecommender\Recommender\RecommenderIU;
use Aldeebhasan\FastRecommender\Recommender\UserBasedRecommender;
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
            ->construct($type)
            ->train();
    }

    public function getUserBasedRecommender(): RecommenderIU
    {
        return new UserBasedRecommender();
    }

    public function addPurchase(int|Model $user, int|Model $item, float $amount)
    {
        $this->addRelation($user, $item, $amount, Relation::TYPE_PURCHASE);
    }

    public function addCartAddition(int|Model $user, int|Model $item, float $amount)
    {
        $this->addRelation($user, $item, $amount, Relation::TYPE_SHOP);
    }

    public function addRating(int|Model $user, int|Model $item, float $amount)
    {
        $this->addRelation($user, $item, $amount, Relation::TYPE_RATE);
    }

    public function addBookmark(int|Model $user, int|Model $item, float $amount)
    {
        $this->addRelation($user, $item, $amount, Relation::TYPE_BOOKMARK);
    }

    private function addRelation(int|Model $user, int|Model $item, float $amount, string $type)
    {
        $source = $user instanceof Model ? $user->id : $user;
        $target = $item instanceof Model ? $item->id : $item;
        Relation::updateOrCreate([
            'source' => $source,
            'target' => $target,
            'type' => $type
        ], [
            'value' => $amount
        ]);
    }
}
