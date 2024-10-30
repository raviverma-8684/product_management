<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sell_Stock extends Model
{
    use HasFactory;
    protected $table = 'sell_stock';
    protected $fillable = [
        'id',
        'total_ordered_quantity',
        'total_ordered_price',
        'created_at',
        'updated_at',

    ];
}
