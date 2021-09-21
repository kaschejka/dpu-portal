<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderShipped1 extends Mailable
{
    use Queueable, SerializesModels;
protected $order;
    /**
     * Create a new message instance.
     *
     * @return void
     */
     public function __construct($order)
     {
       $this->order = $order;
     }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      return $this
      ->view('/mail/endrezerv1')
      ->subject('Резерв номеров по задаче '.$this->order);
    }
}
