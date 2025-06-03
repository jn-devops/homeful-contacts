<?php

namespace App\Notifications;

use App\Models\Reference;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistrationWelcomeNotificationForSellerApp extends Notification
{
    use Queueable;

    public Reference $reference;
    public string $password;

    /**
     * Create a new notification instance.
     */
    public function __construct(Reference $reference, string $password)
    {
        $this->reference = $reference;
        $this->password = $password;
    }
    
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
        return (new MailMessage)
            ->line('Dear ' . $notifiable->name . ',')
            ->line('We\'re super excited to welcome you to Homeful Shop.')
            ->line('Not only do we have amazing properties and amenities, but our service is all about the little things that make a big difference. We think that even the smallest gestures can really show how much we care.')
            ->line('For us, true luxury is all about feeling like you belong, and every visit is a chance for us to help you experience that. Can\'t wait to have you with us!')
            ->line('Please find your temporary credentials:')
            ->line('Email: ' . $notifiable->email)
            ->line('Password: ' .$this->password)
            ->line('Client Code:')
            ->line($this->reference->code)
            ->action('Login', url($this->getUrl($notifiable)))
            ->line('Cheers,')
            ->line('Homeful Shop');
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

    public function getContactReferenceCode(): string
    {
        return $this->message;
    }
}
