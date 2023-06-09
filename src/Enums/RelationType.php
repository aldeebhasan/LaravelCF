<?php

namespace Aldeebhasan\LaravelCF\Enums;

enum RelationType: string
{
    case PURCHASE = 'purchase';
    case RATE = 'rate';
    case SHOP = 'shop';
    case BOOKMARK = 'bookmark';
}
