<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PO extends Model
{
    use HasFactory;
    protected $table = 'po';
    protected $fillable = [
        'id',
        'status',
        'total_quantity',
        'total_price',
        'created_at',
        'updated_at',
    ];
}
