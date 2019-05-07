<?php

namespace App\Mail;

use App\Candidate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CandidateRejection extends Mailable
{
    use Queueable, SerializesModels;

    public $candidate;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Candidate $candidate)
    {
        $this->candidate = $candidate;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.candidates.rejection');
    }
}
