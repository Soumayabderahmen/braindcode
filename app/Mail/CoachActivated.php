<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CoachActivated extends Mailable
{
    use Queueable, SerializesModels;

    public $coach;

    /**
     * Create a new message instance.
     *
     * @param $startup
     * @return void
     */
    public function __construct($coach)
    {
        $this->coach = $coach;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.startup_activated')
                    ->with([
                        'startupName' => $this->coach->name,
                    ]);
    }
}
