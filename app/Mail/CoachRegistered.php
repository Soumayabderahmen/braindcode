<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CoachRegistered extends Mailable
{
    use Queueable, SerializesModels;
    public $coach;

    /**
     * Create a new message instance.
     */
    public function __construct(User $coach)
    {
        $this->coach = $coach;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Coach Registered',
        );
    }
    public function build()
    {
        return $this->subject('Un nouveau coach s\'est inscrit !')
                    ->from('jlidioumaima01@gmail.com', 'BraindCode startup Studio')
                    ->view('emails.coach_registered')
                    ->with([
                        'coachName' => $this->coach->name,
                        'coachEmail' => $this->coach->email,
                        'coachDocument' => $this->coach->document,
                        'coachSpecialite' => $this->coach->specialty,
                        'coachNumber' => $this->coach->phone_number,

                        'activationLink' => route('admin.activate_coach', ['id' => $this->coach->id]) // Lien d'activation

                    ])
                    ->attach(storage_path('app/public/' . $this->coach->document), [ // Attacher le fichier
                        'as' => 'Document-' . basename($this->coach->document),  // Nom du fichier joint
                        'mime' => 'application/pdf',  // Type MIME pour un PDF (modifier si ce n'est pas un PDF)
                    ]);
    }
    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.coach_registered', // Le chemin correct vers la vue
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
