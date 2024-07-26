<?php

namespace App\Http\Traits;

trait HasIsActive
{

    public function scopeActive($query) {
        $query->where('is_active', true);
    }

}
