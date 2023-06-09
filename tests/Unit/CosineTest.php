<?php

namespace Aldeebhasan\LaravelCF\Test\Unit;

use Aldeebhasan\LaravelCF\Similarity\Cosine;
use Aldeebhasan\LaravelCF\Similarity\CosineCentered;
use Aldeebhasan\LaravelCF\Similarity\CosineWeighted;
use Aldeebhasan\LaravelCF\Test\TestCase;

class CosineTest extends TestCase
{
    public function test_similarity()
    {
        $algorithm = new Cosine([]);
        $xVector = [
            'item1' => 1,
            'item2' => 1,
            'item3' => 0.2,
        ];
        $yVector = [
            'item1' => 0.2,
            'item2' => 0.5,
            'item3' => 1,
        ];
        self::assertEquals(0.55, $algorithm->getSimilarity($xVector, $yVector));
        self::assertEquals(1, $algorithm->getSimilarity($xVector, $xVector));
        self::assertEquals(1, $algorithm->getSimilarity($yVector, $yVector));
    }

    public function test_centered_similarity()
    {
        $algorithm = new CosineCentered([]);
        $xVector = [
            'item1' => 1,
            'item2' => 1,
            'item3' => 0.2,
            'item4' => 0.1,
        ];
        $yVector = [
            'item1' => 1,
            'item2' => 1,
            'item3' => 0.5,
        ];
        self::assertEquals(0.79, $algorithm->getSimilarity($xVector, $yVector));
        self::assertEquals(1, $algorithm->getSimilarity($xVector, $xVector));
        self::assertEquals(1, $algorithm->getSimilarity($yVector, $yVector));
    }

    public function test_weighted_similarity()
    {
        $dataset = [
            'Item1' => [
                'user1' => 1,
                'user2' => 1,
            ],
            'Item2' => [
                'user1' => 0.2,
                'user2' => 0.5,
                'user3' => 0.5,
            ],
            'Item3' => [
                'user2' => 0.5,
                'user3' => 0.2,
            ],
        ];

        $algorithm = new CosineWeighted($dataset);
        $xVector = $dataset['Item1'];
        $yVector = $dataset['Item2'];
        self::assertEquals($algorithm->getSimilarity($xVector, $yVector), 0.8);
    }
}
