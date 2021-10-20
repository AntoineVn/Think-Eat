<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aliment extends Model
{
    use HasFactory;

    protected $table = 'kitchen';

    protected $fillable = [
        'name',
        'image',
        'expire_date',
        'status',
        'quantity',
        'price',
        'category_id',
        'user_id',
    ];
}
