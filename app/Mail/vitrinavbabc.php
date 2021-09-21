<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class vitrinavbabc extends Mailable
{
    use Queueable, SerializesModels;



    /**
     * Create a new message instance.
     *
     * @return void
     */

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this
        ->view('/mail/otchet')
        ->subject('Отчет витрина Voice Box ABC')
        ->attach('C:\xampp\htdocs\work\storage\app\отчет_витрина_VB_ABC.xlsx');
    }
}
