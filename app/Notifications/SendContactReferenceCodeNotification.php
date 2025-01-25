<?php

namespace App\Notifications;

use LBHurtado\EngageSpark\Notifications\BaseNotification as Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;

class SendContactReferenceCodeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line("Your Homeful Id:")
                    ->line($this->getContactReferenceCode());
    }

    public function getContent($notifiable)
    {
        return __('Your Homeful Id is :contact_reference_code.', ['contact_reference_code' => $this->getContactReferenceCode()]);
    }

    public function getContactReferenceCode(): string
    {
        return $this->message;
    }
}
