<?php

namespace Modules\Lead\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendLeadEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $leadEmail;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($leadEmail)
    {
        $this->leadEmail = $leadEmail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('lead::email.lead_email')->subject($this->leadEmail->subject)->with(
            [
                'leadEmail' => $this->leadEmail,
                'mail_header' => company_setting('company_name'),
            ]
        );
    }
}
