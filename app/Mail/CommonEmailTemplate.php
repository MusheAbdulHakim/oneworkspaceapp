<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CommonEmailTemplate extends Mailable
{
    use Queueable, SerializesModels;
    public $template;
    public $user_id;
    public $workspace_id;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($template,$user_id,$workspace_id)
    {
        $this->template = $template;
        $this->user_id = $user_id;
        $this->workspace_id = $workspace_id;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return  $this->from(company_setting('mail_from_address',$this->user_id,$this->workspace_id), $this->template->from)
                ->markdown('email.common_email_template')
                ->subject($this->template->subject)
                ->with('content', $this->template->content);
    }
}
