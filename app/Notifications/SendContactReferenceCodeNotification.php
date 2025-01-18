<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use LBHurtado\EngageSpark\EngageSparkMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Bus\Queueable;

class SendContactReferenceCodeNotification extends Notification
{
    use Queueable;

    public function __construct(protected string $contact_reference_Code){}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
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
                    ->line("Your Homeful Id:")
                    ->line($this->contact_reference_Code);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function toEngageSpark(object $notifiable): EngageSparkMessage
    {
        $message = __('Your Homeful Id is :contact_reference_code.', ['contact_reference_code' => $this->contact_reference_Code]);

        return (new EngageSparkMessage())
            ->content($message);
    }

    public function getContactReferenceCode(): string
    {
        return $this->contact_reference_Code;
    }
}
