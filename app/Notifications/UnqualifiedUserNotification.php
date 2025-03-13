<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Arr;

class UnqualifiedUserNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected array $attribs){}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $name = Arr::get($this->attribs, 'name', 'John Doe');
        $mobile = Arr::get($this->attribs, 'mobile', '09171234567');
        $message = Arr::get($this->attribs, 'message', 'The quick brown fox...');

        return (new MailMessage)
            ->line('Name: ' . $name)
            ->line('Mobile: ' . $mobile)
            ->line('Message: ' . $message)
            ->line('Thank you for using our application!');
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
}
