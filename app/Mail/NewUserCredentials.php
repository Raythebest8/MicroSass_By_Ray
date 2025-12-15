<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewUserCredentials extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * L'utilisateur nouvellement créé.
     * @var \App\Models\User
     */
    public $user;

    /**
     * Le mot de passe généré en clair.
     * @var string
     */
    public $rawPassword;

    /**
     * Crée une nouvelle instance de message.
     *
     * @param User $user
     * @param string $rawPassword
     * @return void
     */
    public function __construct(User $user, string $rawPassword)
    {
        $this->user = $user;
        $this->rawPassword = $rawPassword;
    }

    /**
     * Construit le message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Vos identifiants de connexion temporaires')
                    ->view('emails.new_user_credentials'); // La vue que nous allons créer
    }
}