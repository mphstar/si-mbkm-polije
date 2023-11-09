<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class pesertauploadlaporan extends Mailable
{
    use Queueable, SerializesModels;
private $datamhs;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($datamhs)
    {
    $this->datamhs=$datamhs;    //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.pesertauploadlaporan',[
            "datamhs"=>$this->datamhs
        ]);
    }
}
