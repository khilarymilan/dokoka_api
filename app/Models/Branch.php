<?php

namespace App\Models;

class Branch extends BaseModel
{
    public function store() {
        return $this->belongsTo(Store::class);
    }
}
