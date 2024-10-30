<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;
    protected $table = 'vendor';
    protected $fillable = [
        'id',
        'name',
        'address',
        'state',
        'city',
        'pincode',
        'gst_no',
        'status',
        'created_at',
        'updated_at',

          ];

}
