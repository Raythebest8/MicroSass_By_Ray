<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Paiement;

class PaiementRecuNotification extends Notification
{
    protected $paiement;

    public function __construct(Paiement $paiement)
    {
        $this->paiement = $paiement;
    }

    public function via($notifiable) {
        return ['database', 'mail']; // Notification via interface admin et email
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nouveau paiement reçu - ' . $this->paiement->reference_transaction)
            ->line('Un paiement vient d\'être effectué pour une échéance.')
            ->line('Montant : ' . number_format($this->paiement->montant, 0, ',', ' ') . ' FCFA')
            ->action('Voir le détail', url('/admin/paiements'))
            ->line('Merci d\'utiliser notre plateforme !');
    }

    public function toArray($notifiable)
    {
        return [
            'paiement_id' => $this->paiement->id,
            'montant' => $this->paiement->montant_paye,
            'message' => 'Nouveau paiement de ' . $this->paiement->montant_paye . ' FCFA reçu.',
        ];
    }
}