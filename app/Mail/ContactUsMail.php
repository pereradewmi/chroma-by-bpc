<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactUsMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $contactData;

    public function __construct(array $contactData)
    {
        $this->contactData = $contactData;
    }

    public function build()
    {
        return $this
            ->subject('Website Contact: ' . $this->contactData['subject'])
            ->replyTo($this->contactData['email'], $this->contactData['name'])
            ->view('emails.contact-us');
    }
}
