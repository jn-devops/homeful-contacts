<?php

use App\Notifications\SendLoginMagicLinkNotification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

beforeEach(function () {
    // Set up test configuration for notification channels.
    Config::set('notifications.channels', [
        // Default channels for all notifications.
        'default' => ['database'],
        // Allowed channels in the system.
        'allowed' => ['database', 'mail', 'engage_spark'],
        // Specific channels for SendLoginMagicLinkNotification.
        SendLoginMagicLinkNotification::class => ['mail', 'engage_spark'],
    ]);
});

it('resolves the correct notification channels via the trait', function () {
    // Use a URL as the message, which will be returned by getUrl()
    $message = 'https://google.com';
    $notification = new SendLoginMagicLinkNotification($message);
    // Create a dummy notifiable object with a name property.
    $notifiable = new class {
        public $name = 'John Doe';
    };

    // Invoke the via() method and assert that the channels are as configured.
    $channels = $notification->via($notifiable);
    expect($channels)->toBe(['database', 'mail', 'engage_spark']);
});

it('builds the correct mail message content', function () {
    $message = 'https://google.com';
    $notification = new SendLoginMagicLinkNotification($message);
    $notifiable = new class {
        public $name = 'John Doe';
    };

    // Get the MailMessage instance.
    $mailMessage = $notification->toMail($notifiable);
    // Render the MailMessage into a string.
    $content = $mailMessage->render();

    // Assert that the rendered content includes expected substrings.
    expect(Str::contains($content, 'Update your personal information.'))->toBeTrue();
    expect(Str::contains($content, 'Login'))->toBeTrue();
    expect(Str::contains($content, 'Thank you!'))->toBeTrue();
    expect(Str::contains($content, $message))->toBeTrue();
});
