<?php

namespace Aldeebhasan\FastRecommender\Test\Unit;

use Aldeebhasan\FastRecommender\Model\Relation;
use Aldeebhasan\FastRecommender\RecommenderManager;
use Aldeebhasan\FastRecommender\Similarity\SlopeOne;
use Aldeebhasan\FastRecommender\Test\TestCase;

class test extends TestCase
{
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
        $manager->addRating(1, 2, 1);
        $manager->addRating(1, 4, 2);
        $manager->addRating(1, 5, 5);
        $manager->addRating(2, 5, 3);
        $manager->addRating(3, 2, 5);
        $manager->addRating(3, 4, 4);
        $manager->addRating(4, 5, 3);
        $recomanded = $manager->getItemBasedRecommender(Relation::TYPE_RATE)
            ->recommendTo(2);
        dump($recomanded);
        self::assertIsArray($recomanded);
    }

    public function test3()
    {
        $manager = RecommenderManager::make();
        $manager->addRating(1, 'squid', 1);
        $manager->addRating(2, 'squid', 1);
        $manager->addRating(3, 'squid', 0.2);
        $manager->addRating(1, 'cuttlefish', 0.5);
        $manager->addRating(3, 'cuttlefish', 0.4);
        $manager->addRating(4, 'cuttlefish', 0.9);
        $manager->addRating(1, 'octopus', 0.2);
        $manager->addRating(2, 'octopus', 0.5);
        $manager->addRating(3, 'octopus', 1);
        $manager->addRating(4, 'octopus', 0.4);
        $manager->addRating(1, 'nautilus', 0.2);
        $manager->addRating(3, 'nautilus', 0.4);
        $manager->addRating(4, 'nautilus', 0.5);
        $recomanded = $manager->getItemBasedRecommender(Relation::TYPE_RATE)
            ->setSimilarityFunction(SlopeOne::class, true)
            ->train()
            ->recommendTo('squid');
        self::assertIsArray($recomanded);
        dump($recomanded);
    }
}
