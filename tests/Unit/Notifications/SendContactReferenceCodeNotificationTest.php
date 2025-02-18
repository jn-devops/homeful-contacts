<?php

use App\Notifications\SendContactReferenceCodeNotification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

beforeEach(function () {
    // Set up a test configuration for notification channels.
    Config::set('notifications.channels', [
        // Default channels for all notifications
        'default' => ['database'],
        // Allowed channels in the system
        'allowed' => ['database', 'mail', 'engage_spark'],
        // Specific channels for this notification class
        SendContactReferenceCodeNotification::class => ['mail', 'engage_spark'],
    ]);
});

it('resolves the correct notification channels via the trait', function () {
    $message = 'ABC123';
    $notification = new SendContactReferenceCodeNotification($message);
    $notifiable = new class {
        public $name = 'John Doe';
    };
    $channels = $notification->via($notifiable);
    expect($channels)->toBe(['database', 'mail', 'engage_spark']);
});

it('builds the correct mail message content', function () {
    $message = 'ABC123';
    $notification = new SendContactReferenceCodeNotification($message);
    $notifiable = new class {
        public $name = 'John Doe';
    };

    // Get the MailMessage
    $mailMessage = $notification->toMail($notifiable);
    $content = $mailMessage->render();
    expect(Str::contains($content, 'Dear John Doe,'))->toBeTrue();
    expect(Str::contains($content, 'ABC123'))->toBeTrue();
    expect(Str::contains($content, 'https://google.com'))->toBeTrue();
});
