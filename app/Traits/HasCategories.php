<?php

namespace App\Traits;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasCategories
{
    public function categories(): Collection
    {
        return $this->categoriesRelation;
    }

    public function categoriesRelation(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
