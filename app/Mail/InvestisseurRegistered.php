<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvestisseurRegistered extends Mailable
{
    use Queueable, SerializesModels;
    public $investisseur;

    /**
     * Create a new message instance.
     */
    public function __construct(User $investisseur)
    {
        $this->investisseur = $investisseur;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Investisseur Registered',
        );
    }
    public function build()
    {
        return $this->subject('Un nouveau investisseur s\'est inscrit !')
                    ->from('jlidioumaima01@gmail.com', 'BraindCode startup Studio')
                    ->view('emails.investis_registered')
                    ->with([
                        'investisName' => $this->investisseur->name,
                        'investisEmail' => $this->investisseur->email,
                        'investisNumber' => $this->investisseur->phone_number,

                        'investisVisibility' => $this->investisseur->visibility,
                        'activationLink' => route('admin.investisseurs', ['investisseur' => $this->investisseur->id]) // Lien d'activation

                    ]);
                  
    }
    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.investis_registered', // Le chemin correct vers la vue
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
