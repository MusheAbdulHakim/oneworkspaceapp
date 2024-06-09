<?php

namespace Modules\Lead\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendDealEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $dealEmail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($dealEmail)
    {
        $this->dealEmail = $dealEmail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('lead::email.dealemail')->subject($this->dealEmail->subject)->with(
            [
                'dealEmail' => $this->dealEmail,
                'mail_header' => company_setting('company_name'),
            ]
        );
    }
}
