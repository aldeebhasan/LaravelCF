<?php

namespace Aldeebhasan\LaravelCF\Test\Unit;

use Aldeebhasan\LaravelCF\Similarity\Jaccard;
use Aldeebhasan\LaravelCF\Test\TestCase;

class JaccardTest extends TestCase
{
    public function test_similarity()
    {
        $algorithm = new Jaccard([]);
        $xVector = [
            'item1' => 4,
            'item4' => 5,
            'item5' => 1,
        ];
        $yVector = [
            'item1' => 5,
            'item2' => 5,
            'item3' => 4,
        ];
        self::assertEquals($algorithm->getSimilarity($xVector, $yVector), 0.2);
        self::assertEquals($algorithm->getSimilarity($xVector, $xVector), 1);
        self::assertEquals($algorithm->getSimilarity($yVector, $yVector), 1);
    }
}
