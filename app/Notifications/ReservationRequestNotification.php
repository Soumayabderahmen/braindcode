<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use App\Models\Reservation;

class ReservationRequestNotification extends Notification
{
    use Queueable;

    public $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast']; // Tu peux aussi ajouter 'mail' si besoin
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'reservation_created',
            'reservation_id' => $this->reservation->id,
            'startup_name' => $this->reservation->startup->user->name ?? 'Startup',
            'meeting_time' => $this->reservation->meeting_time,
            'statut'=>$this->reservation->statut,
            'message' => 'Une nouvelle réservation a été créée.',

        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}
