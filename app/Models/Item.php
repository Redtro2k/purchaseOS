<?php

namespace App\Models;

use App\Observers\ItemObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


#[ObservedBy([ItemObserver::class])]
class Item extends Model
{
    //
    protected $guarded = [];

    use Notifiable;

    public function purchaseRequisition(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PurchaseRequisition::class);
    }

    public function requester()
    {
        return $this->hasOneThrough(
            User::class,
            PurchaseRequisition::class,
            'id',
            'id',
            'purchase_requisition_id',
            'requester_id'
        );
    }

    public function dealer()
    {
        return $this->belongsTo(Dealer::class);
    }
}
