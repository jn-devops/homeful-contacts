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
        return ['engage_spark'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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
