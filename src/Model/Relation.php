<?php

namespace Aldeebhasan\FastRecommender\Model;

use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
    public const TYPE_PURCHASE = 'purchase';

    public const TYPE_RATE = 'rate';

    public const TYPE_SHOP = 'shop';

    public const TYPE_BOOKMARK = 'bookmark';

    protected $table = 'rs_relations';

    protected $fillable = ['source', 'target', 'type', 'value'];
}
