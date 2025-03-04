<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\SendContactReferenceCodeNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ContactRegistered;
use App\Models\Reference;

class SendContactReferenceCodeListener
{
    public function handle(ContactRegistered $event): void
    {
        if (app()->runningInConsole()) return;

        $reference = $event->reference;
        if ($reference instanceof Reference) {
            $contact = $reference->getContact();
            $user = User::where('email', $contact->email)->firstOrFail();
            $user->notify(new SendContactReferenceCodeNotification($reference->code));
//            $contact->notify(new SendContactReferenceCodeNotification($reference->code));
        }
    }
}
