<?php

namespace App\Listeners;

use App\Notifications\SendContactReferenceCodeNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ContactRegistered;
use App\Models\Reference;

class SendContactReferenceCodeListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ContactRegistered $event): void
    {
        $reference = $event->reference;
        if ($reference instanceof Reference) {
            $contact = $reference->getContact();
            $contact->notify(new SendContactReferenceCodeNotification($reference->code));
        }
    }
}
