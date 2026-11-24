<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelectedCategory extends Model
{
    protected $guarded = ["id"];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function activeProducts()
    {
        return $this->hasMany(Product::class, 'category_id')
            ->where('status', 'active');
    }
}