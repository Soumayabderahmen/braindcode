<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StartupActivated extends Mailable
{
    use Queueable, SerializesModels;

    public $startup;

    /**
     * Create a new message instance.
     *
     * @param $startup
     * @return void
     */
    public function __construct($startup)
    {
        $this->startup = $startup;
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
                        'startupName' => $this->startup->name,
                    ]);
    }
}
