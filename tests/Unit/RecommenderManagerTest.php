<?php

namespace Aldeebhasan\LaravelCF\Test\Unit;

use Aldeebhasan\LaravelCF\Enums\RelationType;
use Aldeebhasan\LaravelCF\Models\Relation;
use Aldeebhasan\LaravelCF\RecommenderManager;
use Aldeebhasan\LaravelCF\Similarity\Cosine;
use Aldeebhasan\LaravelCF\Similarity\CosineWeighted;
use Aldeebhasan\LaravelCF\Test\TestCase;

class RecommenderManagerTest extends TestCase
{
    private RecommenderManager $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = RecommenderManager::make();
    }

    public function test_add_item()
    {
        $this->manager->addRating(1, 1, 5);
        self::assertEquals(1, Relation::count());
    }

    public function test_user_to_user_recommendation()
    {
        $this->manager->addRating('user_1', 2, 1);
        $this->manager->addRating('user_1', 4, 2);
        $this->manager->addRating('user_1', 5, 5);
        $this->manager->addRating('user_2', 5, 3);
        $this->manager->addRating('user_3', 7, 5);
        $this->manager->addRating('user_3', 8, 4);
        $this->manager->addRating('user_4', 5, 4);
        $this->manager->addRating('user_4', 1, 4);
        $recomanded = $this->manager->getUserBasedRecommender(RelationType::RATE)
            ->setSimilarityFunction(Cosine::class)
            ->train()
            ->recommendTo('user_1');
        self::assertIsArray($recomanded);
    }

    public function test_item_to_item_recommendation()
    {
        $this->manager->addRating(1, 'squid', 1);
        $this->manager->addRating(2, 'squid', 1);
        $this->manager->addRating(3, 'squid', 0.2);
        $this->manager->addRating(1, 'cuttlefish', 0.5);
        $this->manager->addRating(3, 'cuttlefish', 0.4);
        $this->manager->addRating(4, 'cuttlefish', 0.9);
        $this->manager->addRating(1, 'octopus', 0.2);
        $this->manager->addRating(2, 'octopus', 0.5);
        $this->manager->addRating(3, 'octopus', 1);
        $this->manager->addRating(4, 'octopus', 0.4);
        $this->manager->addRating(1, 'nautilus', 0.2);
        $this->manager->addRating(3, 'nautilus', 0.4);
        $this->manager->addRating(4, 'nautilus', 0.5);
        $recomanded = $this->manager->getItemBasedRecommender(RelationType::RATE)
            ->setSimilarityFunction(CosineWeighted::class, true)
            ->train()
            ->recommendTo('squid');
        self::assertIsArray($recomanded);
    }
}
