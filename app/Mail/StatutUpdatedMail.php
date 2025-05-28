<?php

namespace App\Mail;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StatutUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;
    public $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function build()
    {
        return $this->subject('Mise à jour de votre réservation')
            ->view('emails.reservation-status')
            ->with([
            'reservation' => $this->reservation,
            'meetingUrl' => $this->reservation->meeting_url,
            'startTime' => $this->reservation->meeting_time,
            'duration' => $this->reservation->duration,
        ]);
    }
}