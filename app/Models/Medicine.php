<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = ['name', 'category', 'stock_quantity', 'expiry_date', 'price'];

    public function scopeLowStock($query)
    {
        return $query->where('stock_quantity', '<', 10);
    }
}
