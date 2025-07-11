<?php

namespace App\Mail;

use App\Models\Reference;
use FrittenKeeZ\Vouchers\Models\VoucherEntity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use MagicLink\Actions\LoginAction;
use MagicLink\MagicLink;

class LogoutMail extends Mailable
{
    use Queueable, SerializesModels;
    public string $homeful_id;
    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        $this->homeful_id = VoucherEntity::where('entity_id', auth()->user()->contact_id)->first()->voucher->code ?? '';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Logout Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.logout-mail',
            with: [
                'link' => $this->getUrl(),
                'email' => auth()->user()->email,
                'homeful_id' => $this->homeful_id,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    public function getUrl(): string
    {
        $user = auth()->user();
        $action = new LoginAction($user);
        $action->response(redirect('/review/personal'));
        return MagicLink::create($action)->url;
    }
}
