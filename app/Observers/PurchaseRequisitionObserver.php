<?php

namespace App\Observers;

use App\Models\PurchaseRequisition;
use Filament\Notifications\Notification;

class PurchaseRequisitionObserver
{
    /**
     * Handle the PurchaseRequisition "created" event.
     */
    public function created(PurchaseRequisition $purchaseRequisition): void
    {
        //
        $recipient = auth()->user();

        $recipient->notify(
            Notification::make()
                ->title('Create successfully '. $purchaseRequisition->pr_number)
                ->sendToDatabase($recipient)
                ->toBroadcast(),
        );
    }

    /**
     * Handle the PurchaseRequisition "updated" event.
     */
    public function updated(PurchaseRequisition $purchaseRequisition): void
    {
        //
        $recipient = auth()->user();

        $recipient->notify(
            Notification::make()
                ->title('Updated successfully '. $purchaseRequisition->pr_number)
                ->sendToDatabase($recipient)
                ->toBroadcast(),
        );
    }

    /**
     * Handle the PurchaseRequisition "deleted" event.
     */
    public function deleted(PurchaseRequisition $purchaseRequisition): void
    {
        //
        $recipient = auth()->user();

        $recipient->notify(
            Notification::make()
                ->title('Deleted successfully ')
                ->sendToDatabase($recipient, true)
                ->toBroadcast(),
        );
    }

    /**
     * Handle the PurchaseRequisition "restored" event.
     */
    public function restored(PurchaseRequisition $purchaseRequisition): void
    {
        //
    }

    /**
     * Handle the PurchaseRequisition "force deleted" event.
     */
    public function forceDeleted(PurchaseRequisition $purchaseRequisition): void
    {
        //
    }
}
