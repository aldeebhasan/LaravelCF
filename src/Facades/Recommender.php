<?php

namespace Aldeebhasan\LaravelCF\Facades;

use Aldeebhasan\LaravelCF\Contracts\RecommenderIU;
use Aldeebhasan\LaravelCF\Enums\RelationType;
use Aldeebhasan\LaravelCF\RecommenderManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;

/**
 * @method static RecommenderIU getItemBasedRecommender(RelationType $type)
 * @method static RecommenderIU getUserBasedRecommender(RelationType $type)
 * @method static RecommenderManager addPurchase(int|string|Model $user, int|string|Model $item, float $amount)
 * @method static RecommenderManager addCartAddition(int|string|Model $user, int|string|Model $item, float $amount)
 * @method static RecommenderManager addRating(int|string|Model $user, int|string|Model $item, float $amount)
 * @method static RecommenderManager addBookmark(int|string|Model $user, int|string|Model $item, float $amount)
 * @method static RecommenderManager remove(int|string|Model $user, int|string|Model $item, RelationType $type = null)
 * @method static RecommenderManager flush()
 *
 * @see RecommenderManager
 */
class Recommender extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'recommender';
    }
}
