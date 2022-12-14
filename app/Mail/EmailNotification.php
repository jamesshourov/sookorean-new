<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mailData = $this->mailData;
        if ($mailData['attachment']) {
            return $this->markdown('emails.EmailNotification')
                ->with('mailData', $this->mailData)
                ->subject($this->mailData['subject'])
                ->attach($mailData['attachment']->getRealPath(),
                    [
                        'as' => $mailData['attachment']->getClientOriginalName(),
                        'mime' => $mailData['attachment']->getClientMimeType(),
                    ]);
        }else{
            return $this->markdown('emails.EmailNotification')
                ->with('mailData', $this->mailData)
                ->subject($this->mailData['subject']);
        }
    }
}
