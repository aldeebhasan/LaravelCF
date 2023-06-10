<?php

namespace Aldeebhasan\LaravelCF\Enums;

enum RelationType: string
{
    case PURCHASE = 'purchase';
    case RATE = 'rate';
    case CART_ACTION = 'cart-action';
    case BOOKMARK = 'bookmark';
}
