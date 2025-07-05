<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dealer extends Model
{
    protected $casts = [
        'images' => 'array'
    ];
    protected $guarded = [];
}
