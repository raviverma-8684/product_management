<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recieve_Stock extends Model
{
    use HasFactory;
    protected $table = 'rs_items';
    protected $fillable = [
        'id',
        'po_id',
        'order_id',
        'recieved_quantity',
        
        'created_at',
        'updated_at',
    ];
    
    public function Order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    public function PO()
    {
        return $this->belongsTo(PO::class, 'po_id');
    }

}
