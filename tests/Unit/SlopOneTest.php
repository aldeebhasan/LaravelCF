<?php

namespace Aldeebhasan\LaravelCF\Test\Unit;

use Aldeebhasan\LaravelCF\Similarity\SlopeOne;
use Aldeebhasan\LaravelCF\Test\TestCase;

class SlopOneTest extends TestCase
{
    public function test_similarity()
    {
        $algorithm = new SlopeOne([]);
        $xVector = [
            'item1' => 1,
            'item2' => 0.2,
        ];
        $yVector = [
            'item1' => 0.5,
            'item2' => 0.4,
        ];
        self::assertEquals($algorithm->getSimilarity($xVector, $yVector), 0.15);
        self::assertEquals($algorithm->getSimilarity($xVector, $xVector), 0);
        self::assertEquals($algorithm->getSimilarity($yVector, $yVector), 0);
    }
}
