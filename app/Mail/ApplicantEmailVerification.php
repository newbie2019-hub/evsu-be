<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicantEmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    public $details;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->details = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Email Verification')
        ->markdown('emails.verifyApplicantEmail', [
            'url' => 'localhost:8081', 
            'first_name' => $this->details['first_name'],
            'middle_name' => $this->details['middle_name'],
            'email' => $this->details['email'],
            'last_name' => $this->details['last_name'],
            'token' => $this->details['token'],
            'id' => $this->details['id'],
            'type' => $this->details['type'],
        ]);
    }
}
