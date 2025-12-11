<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue; 
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage; 

class NewDemandePret extends Notification implements ShouldQueue
{
    use Queueable;

    protected $demande;
    protected $demandeType; 

    public function __construct($demande, $demandeType)
    {
        $this->demande = $demande;
        $this->demandeType = ucfirst($demandeType);
    }

    public function via(object $notifiable): array
    {
        // Ajout de 'database' pour l'affichage dans l'espace admin
        return ['slack', 'mail', 'database']; 
    }

    public function toSlack(object $notifiable): SlackMessage
    {
        $adminUrl = url("/admin/demandes/{$this->demande->id}/details?type={$this->demandeType}");
        $clientName = $this->demande->user?->name ?? 'Utilisateur Inconnu';
        $montant = $this->demande->montant_souhaite ?? 0;
        $duree = $this->demande->duree_mois ?? 0;

        return (new SlackMessage)
            ->from('Système de Prêt', ':money_with_wings:')
            ->to('#demandes-pret') 
            ->content("🚨 Nouvelle Demande de Prêt Reçue : *{$this->demandeType}* 🚨")
            ->attachment(function ($attachment) use ($clientName, $adminUrl, $montant, $duree) {
                $attachment->title("Demande #{$this->demande->id} - Client : {$clientName}", $adminUrl)
                           ->fields([
                                'Montant' => number_format($montant, 0, ',', ' ') . ' FCFA', 
                                'Durée' => "{$duree} mois", 
                                'Statut Initial' => 'En attente',
                           ])
                           ->markdown(['fields']);
            });
    }

    public function toMail(object $notifiable): MailMessage
    {
        $adminUrl = url("/admin/demandes/{$this->demande->id}/details?type={$this->demandeType}");
        $montant = $this->demande->montant_souhaite ?? 0;
        $userName = $this->demande->user?->name ?? 'un utilisateur inconnu';
        
        return (new MailMessage)
                    ->subject("Nouvelle Demande de Prêt: {$this->demandeType} #{$this->demande->id}")
                    ->greeting('Bonjour Administrateur,')
                    ->line("Une nouvelle demande de prêt ({$this->demandeType}) a été soumise par {$userName}.")
                    ->line('Montant souhaité : ' . number_format($montant, 0, ',', ' ') . ' FCFA') 
                    ->action('Examiner la Demande', $adminUrl)
                    ->line('Veuillez la traiter dans les plus brefs délais.');
    }
    
    public function toArray(object $notifiable): array
    {
        $montant = $this->demande->montant_souhaite ?? 0;
        
        return [
            'type' => 'Nouvelle Demande de Prêt',
            'demande_type' => $this->demandeType, 
            'demande_id' => $this->demande->id,
            'client_name' => $this->demande->user?->name ?? 'Utilisateur Inconnu',
            'montant' => number_format($montant, 0, ',', ' ') . ' FCFA',
            'url' => url("/admin/demandes/{$this->demande->id}/details?type={$this->demandeType}"),
        ];
    }
}