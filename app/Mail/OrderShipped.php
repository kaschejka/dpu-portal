<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderShipped extends Mailable
{
    use Queueable, SerializesModels;

protected $model;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($model)
    {
      $this->model = $model;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this
        ->view('/mail/endrezerv7')
        ->subject('Резерв номеров по задаче '.$this->model);
    }
}
