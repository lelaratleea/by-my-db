<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['code', 'name', 'price', 'description'])]
class Product extends Model
{
    function shops(): BelongsToMany {
        return $this
            ->belongsToMany(Shop::class)
            ->withTimestamps();
    }
}