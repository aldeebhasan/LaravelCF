<?php

namespace Aldeebhasan\FastRecommender\Test\Unit;

use Aldeebhasan\FastRecommender\Model\Relation;
use Aldeebhasan\FastRecommender\RecommenderManager;
use Aldeebhasan\FastRecommender\Test\TestCase;

class test extends TestCase
{
    public function test_recommender()
    {
        //item_id => [users' ids how purchased this item]
        $data = [
            1 => [1, 2, 3],
            2 => [1, 2],
            3 => [1],
            4 => [1, 2, 3, 4, 5],
            5 => [1, 2, 3, 4, 5, 7, 8, 9, 10],
        ];

        $recommender = RecommenderManager::make()->getItemBasedRecommender();
        $recommender->train($data);
        echo json_encode($recommender->recommendTo([5, 2], 4), JSON_PRETTY_PRINT);
        self::assertEquals(1, 1);
    }

    public function test()
    {
        $manager = RecommenderManager::make();
        $manager->addRating(1, 1, 5);
        self::assertEquals(1, Relation::count());
    }

    public function test2()
    {
        $manager = RecommenderManager::make();
        $manager->addBookmark(1, 2, 5);
        $manager->addBookmark(2, 3, 5);
        $manager->addRating(1, 3, 5);
        $manager->addRating(1, 4, 5);
        $manager->addRating(1, 5, 5);
        $manager->addRating(2, 5, 5);
        $manager->addRating(3, 5, 5);
        $manager->addRating(3, 4, 5);
        $manager->addRating(4, 5, 5);
        $recomanded = $manager->getItemBasedRecommender(Relation::TYPE_BOOKMARK)
        ->recommendTo(2);
        dump($recomanded);
        self::assertIsArray($recomanded);
    }


}
