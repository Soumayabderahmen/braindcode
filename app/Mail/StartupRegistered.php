<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StartupRegistered extends Mailable
{
    use Queueable, SerializesModels;
    public $startup;

    /**
     * Create a new message instance.
     */
    public function __construct(User $startup)
    {
        $this->startup = $startup;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'startup Registered',
        );
    }
    public function build()
    {
        return $this->subject('Un nouveau startup s\'est inscrit !')
                    ->from('jlidioumaima01@gmail.com', 'BraindCode startup Studio')
                    ->view('emails.startup_registered')
                    ->with([
                        'startupName' => $this->startup->name,
                        'startupEmail' => $this->startup->email,
                        'startupNumber' => $this->startup->phone_number,

                        'startupDomain' => $this->startup->domain_name,
                        'activationLink' => route('admin.startups', ['id' => $this->startup->id]) // Lien d'activation

                    ]);
                  
    }
    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.startup_registered', // Le chemin correct vers la vue
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
}
