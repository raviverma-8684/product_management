<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'po_items';
    protected $fillable = [
        'id',
        'po_id',
        'product_id',
        'vendor_id',
        'quantity',
        'price',
        'total_price',
        'status',
        'created_at',
        'updated_at',
    ];
    public function Product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function Vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
    public function PO()
    {
        return $this->belongsTo(PO::class, 'po_id');
    }


}
