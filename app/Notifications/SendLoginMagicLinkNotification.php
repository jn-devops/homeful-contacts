<?php

namespace App\Notifications;

use LBHurtado\EngageSpark\Notifications\BaseNotification as Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Classes\IsDomainNotification;
use App\Traits\HasDomainNotification;
use Illuminate\Bus\Queueable;

class SendLoginMagicLinkNotification extends Notification implements ShouldQueue, IsDomainNotification
{
    use HasDomainNotification;
    use Queueable;

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('Update your personal information.')
                    ->action('Login', url($this->getUrl()))
                    ->line('Thank you!');
    }

    public function getContent($notifiable)
    {
        return __('You may login: :url', ['url' => $this->getUrl()]);
    }

    public function getUrl(): string
    {
        return $this->message;
    }

    public function via($notifiable)
    {
        return $this->getNotificationChannelsVia($notifiable);
    }
}
