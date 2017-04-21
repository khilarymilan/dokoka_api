<?php

namespace App\Models;

class Product extends BaseModel
{
    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function branch() {
        return $this->belongsTo(Branch::class);
    }
}
