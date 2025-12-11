<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DemandeStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $demande;
    protected $demandeType;
    protected $nouveauStatut;

    public function __construct($demande, $demandeType, $nouveauStatut)
    {
        $this->demande = $demande;
        $this->demandeType = ucfirst($demandeType);
        $this->nouveauStatut = $nouveauStatut;
    }

    public function via(object $notifiable): array
    {
        return ['mail']; 
    }

    public function toMail(object $notifiable): MailMessage
    {
        $detailsUrl = url("/users/historique/details/{$this->demandeType}/{$this->demande->id}");
        $statusKey = $this->nouveauStatut; 
        
        $subject = ($statusKey == 'validée') 
            ? '✅ Votre Demande de Prêt a été VALIDÉE !' 
            : '❌ Mise à jour du Statut de Votre Demande de Prêt';
            
        $actionText = ($statusKey == 'validée') 
            ? 'Voir le Contrat'
            : 'Voir les Détails de la Décision';

        return (new MailMessage)
                    ->subject($subject)
                    ->greeting('Bonjour ' . $notifiable->name . ',')
                    ->line("Votre demande de prêt ({$this->demandeType}) #{$this->demande->id} a été examinée et est maintenant : *" . strtoupper($statusKey) . "*.")
                    ->line(($statusKey == 'validée') 
                        ? 'Le montant accordé est de ' . number_format($this->demande->montant_accorde, 0, ',', ' ') . ' FCFA. Rendez-vous sur votre espace.'
                        : 'Malheureusement, votre demande a été rejetée. Consultez les détails en ligne.')
                    ->action($actionText, $detailsUrl)
                    ->line('Merci de faire confiance à notre plateforme.');
    }
}