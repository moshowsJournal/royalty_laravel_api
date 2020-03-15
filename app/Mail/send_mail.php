<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class send_mail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->data['notification_type'] === 'Verify Email'){
            return $this->from('support@royaltychurchapp.com')->subject($this->data['subject'])->view('elements.verification')->with(['data',$this->data]);
        }else{
            return $this->from('support@theestatgenie.com')->subject($this->data['subject'])->view('elements.send_email')->with(['data',$this->data]);
        }
    }
}
