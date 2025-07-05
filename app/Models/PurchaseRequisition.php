<?php

namespace App\Models;

use App\Observers\PurchaseRequisitionObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


#[ObservedBy([PurchaseRequisitionObserver::class])]
class PurchaseRequisition extends Model
{
    //
    use Notifiable;

    protected $guarded = [];

    protected $casts = [
        'attachments' => 'array'
    ];

    public function requester(){
        return $this->belongsTo(User::class, 'requester_id');
    }
    public function preparer(){
        return $this->belongsTo(User::class, 'prepared_by_id');
    }
    public function gm(){
        return $this->belongsTo(User::class, 'gm_by_id');
    }
    public function mansor(){
        return $this->belongsTo(User::class, 'mansor_by_id');
    }
    public function executive(){
        return $this->belongsTo(User::class, 'executive_by_id');
    }
    public function dealer(){
        return $this->belongsTo(Dealer::class, 'dealer_id');
    }
    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Item::class, 'purchase_requisition_id');
    }
}
