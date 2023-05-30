<?php

namespace Aldeebhasan\FastRecommender\Model;

use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
    const TYPE_PURCHASE = 'purchase';
    const TYPE_RATE = 'rate';
    const TYPE_SHOP = 'shop';
    const TYPE_BOOKMARK = 'bookmark';

    protected $table = 'rs_relations';

    protected $fillable = ['source', 'target', 'type', 'value'];
}