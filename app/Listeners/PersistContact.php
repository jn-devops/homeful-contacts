<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Registered;
use App\Models\Contact;
use App\Data\UserData;

class PersistContact
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
    public function handle(Registered $event): void
    {
        $user = $event->user;
        $contact = Contact::create(UserData::from($user)->toArray());
        $user->contact()->associate($contact);
        $user->save();
    }
}
