<?php

namespace App\Http\Traits;

use App\Models\Scopes\SortScope;

trait Sortable
{
    protected static function booted(): void {
        static::addGlobalScope(new SortScope);
    }
}
