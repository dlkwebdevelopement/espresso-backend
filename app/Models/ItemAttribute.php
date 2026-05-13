<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemAttribute extends Model
{
    use HasFactory;

    protected $fillable = ['item_id', 'name'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function options()
    {
        return $this->hasMany(ItemAttributeOption::class);
    }
}
