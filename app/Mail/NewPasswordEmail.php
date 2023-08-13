<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewPasswordEmail extends Mailable
{
  use Queueable, SerializesModels;

  public $newPassword;

  /**
   * Create a new message instance.
   *
   * @param string $newPassword
   */
  public function __construct($newPassword)
  {
    $this->newPassword = $newPassword;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    return $this->view('emails.new_password')
      ->subject('Password Reset')
      ->with([
        'newPassword' => $this->newPassword,
      ]);
  }
}