<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'sub_category_id',
        'name',
        'description',
        'base_price',
        'image_path',
        'is_available',
        'is_weekly_favorite',
        'is_popular',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function attributes()
    {
        return $this->hasMany(ItemAttribute::class);
    }
}
