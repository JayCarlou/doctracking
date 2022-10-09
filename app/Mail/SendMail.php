<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $data2;
    public $data3;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data,$data2,$data3)
    {
        //
        $this->data = $data;
        $this->data2 = $data2;
        $this->data3 = $data3;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Document Tracking System')->view('email');
    }
}
