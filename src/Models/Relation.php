<?php

namespace Aldeebhasan\LaravelCF\Models;

use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
    protected $table = 'rs_relations';

    protected $fillable = ['group', 'source', 'target', 'type', 'value'];
}
