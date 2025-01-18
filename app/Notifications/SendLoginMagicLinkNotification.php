<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use LBHurtado\EngageSpark\EngageSparkMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Bus\Queueable;

class SendLoginMagicLinkNotification extends Notification
{
    use Queueable;

    public function __construct(protected string $url){}

    public function via(object $notifiable): array
    {
        return ['mail', 'engage_spark'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('Update your personal information.')
                    ->action('Login', url($this->url))
                    ->line('Thank you!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function toEngageSpark(object $notifiable): EngageSparkMessage
    {
        $message = __('You may login: :url', ['url' => $this->url]);

        return (new EngageSparkMessage())
            ->content($message);
    }
}
