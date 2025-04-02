<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvestisseurActivated extends Mailable
{
    use Queueable, SerializesModels;

    public $investisseur;

    /**
     * Create a new message instance.
     *
     * @param $startup
     * @return void
     */
    public function __construct($investisseur)
    {
        $this->investisseur = $investisseur;
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
                        'startupName' => $this->investisseur->name,
                    ]);
    }
}
