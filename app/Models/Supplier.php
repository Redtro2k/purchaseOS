<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Supplier extends Pivot
{
    //
    protected $casts = [
        'files' => 'array',
    ];

    protected $guarded = [];

    public function dealer(){
        return $this->belongsTo(Dealer::class, 'dealer_id');
    }

    public function getRouteKeyName(): string
    {
        return 'id'; // or 'slug' if using slug
    }
}
