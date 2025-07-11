<?php

namespace App\Helper;

use App\Actions\SendSMS;
use App\Models\User;
use Homeful\Contacts\Models\Contact;
use Homeful\Contacts\Models\Customer;
use Homeful\References\Models\Reference;
use Illuminate\Support\Facades\Http;
use MagicLink\Actions\LoginAction;
use MagicLink\MagicLink;

class WelcomeSMS
{
    public static function send(Reference $reference, $password)
    {
        $contact = $reference->getContact();
        $link = self::getUrl(User::where('contact_id', $contact->id)->first());
        $mobile = '63'.substr($contact->mobile, 1);
        $message = "Dear {$contact->name}, welcome to Homeful Shop! We are pleased to have you with us and look forward to providing you with exceptional service. Log in here:{$link} \nTemporary Password: {$password}\nClient Code: {$reference->code}.\nPlease check your email for more details.";
        $response = SendSMS::run($mobile, $message);
    }

    public static function getUrl($notifiable): string
    {
        $user = $notifiable;
        if ($user instanceof User) {
            $action = new LoginAction($user);
            $action->response(redirect('/review/personal'));
            return MagicLink::create($action)->url;
        }

        return "https://google.com";
    }
}