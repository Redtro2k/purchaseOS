<?php

namespace App\Observers;

use App\Models\User;
use Filament\Notifications\Notification;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        //
        $recipient = auth()->user();

        $recipient->notify(
            Notification::make()
                ->title('Updated successfully '. $user->name)
                ->sendToDatabase($recipient)
                ->toBroadcast(),
        );
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
        $recipient = auth()->user();

        $recipient->notify(
            Notification::make()
                ->title('Updated successfully '. $user->name)
                ->sendToDatabase($recipient)
                ->toBroadcast(),
        );
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
        $recipient = auth()->user();

        $recipient->notify(
            Notification::make()
                ->title('Deleted successfully '. $user->name)
                ->sendToDatabase($recipient)
                ->toBroadcast(),
        );
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
