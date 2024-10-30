<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sell_Stock_Items extends Model
{
    use HasFactory;
    protected $table = 'sell_stock_items';
    protected $fillable = [
        'id',
        'sell_stock_id',
        'product_id',
        'ordered_quantity',
        'total_selling_price',
        'discount_type',
        'discount',
        'created_at',
        'updated_at',

    ];
    public function Product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    

    
}
 