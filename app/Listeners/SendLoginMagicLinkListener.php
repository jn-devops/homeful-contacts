<?php

namespace App\Listeners;

use App\Notifications\SendLoginMagicLinkNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Registered;
use MagicLink\Actions\LoginAction;
use MagicLink\MagicLink;
use App\Models\User;

class SendLoginMagicLinkListener
{
    public function handle(Registered $event): void
    {
        // if (app()->runningInConsole()) return;

        // $user = $event->user;
        // if ($user instanceof User) {
        //     $action = new LoginAction($user);
        //     $action->response(redirect('/dashboard'));
        //     $urlToDashBoard = MagicLink::create($action)->url;
        //     $user->notify(new SendLoginMagicLinkNotification($urlToDashBoard));
        // }
        return;
    }
}
