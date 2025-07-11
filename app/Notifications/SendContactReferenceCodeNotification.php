<?php

namespace App\Notifications;

use LBHurtado\EngageSpark\Notifications\BaseNotification as Notification;
use Homeful\Common\Interfaces\IsDomainNotification;
use Illuminate\Notifications\Messages\MailMessage;
use Homeful\Common\Traits\HasDomainNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use MagicLink\Actions\LoginAction;
use MagicLink\MagicLink;
use App\Models\User;


class SendContactReferenceCodeNotification extends Notification implements ShouldQueue, IsDomainNotification
{
    use HasDomainNotification;
    use Queueable;

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('Dear ' . $notifiable->name . ',')
            ->line('We\'re super excited to welcome you to Homeful Shop.')
            ->line('Not only do we have amazing properties and amenities, but our service is all about the little things that make a big difference. We think that even the smallest gestures can really show how much we care.')
            ->line('For us, true luxury is all about feeling like you belong, and every visit is a chance for us to help you experience that. Can\'t wait to have you with us!')
            ->line('Please find your temporary credentials:')
            ->line('Email: ' . $notifiable->email)
            ->line('Password: ' .'password')
            ->line('Client Code:')
            ->line($this->getContactReferenceCode())
            ->action('Login', url($this->getUrl($notifiable)));
    }

    public function getContent($notifiable)
    {
        return __('Dear :name, welcome to Homeful Shop! We are pleased to have you with us and look forward to providing you with exceptional service.
Log in here: :url
Temporary Password: :password
Client Code: :contact_reference_code.
Please check your email for more details.
Best Regards,
Homeful Shop', [
            'name' => $notifiable->name, // Anaïs - you missed this part :-)
            'url' => $this->getUrl($notifiable),
            'password' => context('password'),
            'contact_reference_code' => $this->getContactReferenceCode()
        ]);
    }

    public function getContactReferenceCode(): string
    {
        return $this->message;
    }

    public function via($notifiable)
    {
        return $this->getNotificationChannelsVia($notifiable);
    }

    public function getUrl($notifiable): string
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
