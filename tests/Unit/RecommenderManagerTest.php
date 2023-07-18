<?php

namespace Aldeebhasan\LaravelCF\Test\Unit;

use Aldeebhasan\LaravelCF\Enums\MissingValue;
use Aldeebhasan\LaravelCF\Enums\RelationType;
use Aldeebhasan\LaravelCF\Facades\Recommender;
use Aldeebhasan\LaravelCF\Models\Relation;
use Aldeebhasan\LaravelCF\Similarity\CosineWeighted;
use Aldeebhasan\LaravelCF\Similarity\Pearson;
use Aldeebhasan\LaravelCF\Test\TestCase;

class RecommenderManagerTest extends TestCase
{
    public function test_add_item()
    {
        Recommender::addRating(1, 1, 5);
        self::assertEquals(1, Relation::count());
    }

    public function test_user_to_user_recommendation()
    {
        Recommender::addRating('user_1', 2, 1);
        Recommender::addRating('user_1', 4, 2);
        Recommender::addRating('user_1', 5, 5);
        Recommender::addRating('user_2', 8, 5);
        Recommender::addRating('user_2', 5, 5);
        Recommender::addRating('user_3', 1, 5);
        Recommender::addRating('user_3', 8, 4);
        Recommender::addRating('user_4', 5, 4);
        Recommender::addRating('user_4', 1, 4);
        $recomanded = Recommender::getUserBasedRecommender(RelationType::RATE)
//            ->setSimilarityFunction(Pearson::class)
            ->train()
            ->recommendTo('user_2');

        self::assertIsArray($recomanded);
    }

    public function test_user_item_similarity_item_based()
    {
        Recommender::addRating('user_1', 2, 1);
        Recommender::addRating('user_1', 4, 2);
        Recommender::addRating('user_1', 5, 5);
        Recommender::addRating('user_2', 8, 5);
        Recommender::addRating('user_2', 2, 3);
        Recommender::addRating('user_2', 5, 5);
        $similarity = Recommender::getItemBasedRecommender(RelationType::RATE)
            ->train()
            ->userItemSimilarity('user_2', 8);
        self::assertIsFloat($similarity);
    }

    public function test_user_item_similarity_user_based()
    {
        Recommender::addRating('user_1', 2, 1);
        Recommender::addRating('user_1', 4, 2);
        Recommender::addRating('user_1', 5, 5);
        Recommender::addRating('user_2', 8, 5);
        Recommender::addRating('user_2', 5, 5);
        $similarity = Recommender::getUserBasedRecommender(RelationType::RATE)
            ->train()
            ->userItemSimilarity('user_2', 2);
        self::assertIsFloat($similarity);
    }

    public function test_item_to_item_recommendation()
    {
        Recommender::addRating(1, 'squid', 1);
        Recommender::addRating(2, 'squid', 1);
        Recommender::addRating(3, 'squid', 0.2);
        Recommender::addRating(1, 'cuttlefish', 0.5);
        Recommender::addRating(3, 'cuttlefish', 0.4);
        Recommender::addRating(4, 'cuttlefish', 0.9);
        Recommender::addRating(1, 'octopus', 0.2);
        Recommender::addRating(2, 'octopus', 0.5);
        Recommender::addRating(3, 'octopus', 1);
        Recommender::addRating(4, 'octopus', 0.4);
        Recommender::addRating(1, 'nautilus', 0.2);
        Recommender::addRating(3, 'nautilus', 0.4);
        Recommender::addRating(4, 'nautilus', 0.5);
        $recomanded = Recommender::getItemBasedRecommender(RelationType::RATE)
            ->setSimilarityFunction(CosineWeighted::class, MissingValue::MEAN)
//            ->setSimilarityFunction(Pearson::class)
            ->train()
            ->recommendTo('squid');

        self::assertIsArray($recomanded);
    }
}
