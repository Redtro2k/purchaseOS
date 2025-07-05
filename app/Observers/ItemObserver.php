<?php

namespace App\Observers;

use App\Models\Item;
use Filament\Notifications\Notification;

class ItemObserver
{
    /**
     * Handle the Item "created" event.
     */
    public function created(Item $item): void
    {
        $recipient = auth()->user();

        $recipient->notify(
            Notification::make()
                ->title('Create successfully  PR #'. $item->purchaseRequisition->pr_number.' - '. $item->title)
                ->sendToDatabase($recipient)
                ->toBroadcast(),
        );
    }

    /**
     * Handle the Item "updated" event.
     */
    public function updated(Item $item): void
    {
        $recipient = auth()->user();

        $recipient->notify(
            Notification::make()
                ->title('Updated successfully  PR #'. $item->purchaseRequisition->pr_number.' - '. $item->title)
                ->sendToDatabase($recipient, true)
                ->toBroadcast(),
        );
    }

    /**
     * Handle the Item "deleted" event.
     */
    public function deleted(Item $item): void
    {
        //
        $recipient = auth()->user();

        $recipient->notify(
            Notification::make()
                ->title('Delete successfully PR #'. $item->purchaseRequisition->pr_number.' - '. $item->title)
                ->sendToDatabase($recipient)
                ->toBroadcast(),
        );
    }

    /**
     * Handle the Item "restored" event.
     */
    public function restored(Item $item): void
    {
        //
    }

    /**
     * Handle the Item "force deleted" event.
     */
    public function forceDeleted(Item $item): void
    {
        //
    }
}
