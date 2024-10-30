<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model 
{
    
    protected $table = 'products';
    protected $fillable = [
        'id',
        'name',
        'sku',
        'quantity',
        'is_in_stock',
        'selling_price',
        'status',
        'photo',
        'created_at',
        'updated_at',

          ];


    
}
