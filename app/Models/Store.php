<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'contact_number',
        'is_active',
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
