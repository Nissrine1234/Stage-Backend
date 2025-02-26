<?php 



use Illuminate\Mail\Mailable;

class SendPasswordMail extends Mailable
{
    public $user;
    public $password;

    public function __construct($user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('Votre compte a été accepté !')
                    ->view('emails.password')
                    ->with([
                        'email' => $this->user->email,
                        'password' => $this->password
                    ]);
    }
}
