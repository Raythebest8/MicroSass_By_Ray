<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Particulier; 
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB; // Pour la transaction
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; // Pour l'utilisateur authentifié
use Illuminate\Support\Facades\Log; // Pour le débogage en cas d'erreur
use App\Models\User; 
use App\Notifications\NewDemandePret;

class ParticulierController extends Controller
{
    /**
     * Affiche le formulaire de demande de prêt pour les particuliers.
     */
    public function formParticulier()
    {
        // Retourne la vue du formulaire
        return view('users.demande.particulier');
    }   

    /**
     * Traite la soumission du formulaire et enregistre la demande.
     */
    public function submitParticulier(Request $request)
    {
        // 1. VÉRIFICATION D'AUTHENTIFICATION
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour soumettre une demande.');
        }

        // 2. DÉFINITION ET EXÉCUTION DE LA VALIDATION
        $validatedData = $request->validate([
            // --- 1. Informations Personnelles et de Contact ---
            'nom'               => ['required', 'string', 'max:255'],
            'prenom'            => ['required', 'string', 'max:255'],
            'email'             => ['required', 'string', 'email', 'max:255'],
            'telephone'         => ['required', 'string', 'max:20'],
            'adresse'           => ['required', 'string', 'max:255'],
            'ville'             => ['required', 'string', 'max:100'],
            'code_postal'       => ['required', 'string', 'max:10'],
            
            // --- 2. Informations Professionnelles et Financières ---
            'nom_employeur'     => ['nullable', 'string', 'max:255'],
            'secteur_activite'  => ['required', 'string', 'max:255'],
            'type_emploi'       => ['required', 'string', Rule::in(['CDI', 'CDD', 'Indépendant', 'Autre', 'Fonctionnaire'])], 
            'revenu_mensuel'    => ['required', 'numeric', 'min:50000'],
            
            // --- 3. Détails du Prêt ---
            'montant_souhaite'  => ['required', 'numeric', 'min:100000', 'max:10000000'], 
            'duree_mois'        => ['required', 'integer', 'min:3', 'max:84'],
            'motif'             => ['required', 'string', 'min:4'],

            // --- 4. Documents Justificatifs (Uploads) ---
            'justificatif_id'       => ['required', 'file', 'mimes:pdf,jpg,png', 'max:3072'], // 3MB
            'justificatif_domicile' => ['required', 'file', 'mimes:pdf,jpg,png', 'max:3072'], // 3MB
            'preuves_revenu'        => ['required', 'file', 'mimes:pdf', 'max:5120'], // 5MB
            'rib'                   => ['required', 'file', 'mimes:pdf,jpg,png', 'max:2048'], // 2MB
        ]);

        $paths = [];
        $demande = null;
        
        // --- 3. TRANSACTION CRITIQUE : Assure l'atomicité de l'opération ---
        try {
            DB::transaction(function () use ($validatedData, $request, &$demande, &$paths) {
                
                // 3.A. CRÉATION DE L'ENREGISTREMENT INITIAL (avec chemins vides)
                $demandeData = array_merge($validatedData, [
                    'user_id' => Auth::id(),
                    'statut' => 'en attente',
                    'admin_id' => null,
                    'date_traitement' => null,
                    // Initialisation des chemins pour l'insertion
                    'justificatif_id' => '', 
                    'justificatif_domicile' => '',
                    'preuves_revenu' => '',
                    'rib' => '',
                ]);
                
                $demande = Particulier::create($demandeData);
                $demandeId = $demande->id;
                $storagePath = "demandes/particuliers/{$demandeId}";

                // 3.B. UPLOAD ET ENREGISTREMENT DES CHEMINS
                $fields = [
                    'justificatif_id',
                    'justificatif_domicile',
                    'preuves_revenu',
                    'rib'
                ];

                foreach ($fields as $field) {
                    if ($request->hasFile($field)) {
                        $file = $request->file($field);
                        // Nom de fichier standardisé : nom_du_champ.extension_originale
                        $fileName = $field . '.' . $file->getClientOriginalExtension();
                        
                        // Stockage : utilise le disque 'public' par défaut
                        $path = $file->storeAs($storagePath, $fileName, 'public');
                        $paths[$field] = $path;
                    }
                }

                // 3.C. MISE À JOUR DE L'ENREGISTREMENT AVEC LES CHEMINS RÉELS
                $demande->update($paths);

            }); // Fin de la transaction

            if ($demande) { 
            // 🚨 Trouve l'administrateur à notifier 🚨
            $admin = User::where('role', 'admin')->first(); 
            
            if ($admin) {
                // Déclenche la notification Slack/Mail
                $admin->notify(new NewDemandePret($demande, 'particulier')); 
                Log::info("Notification de nouvelle demande particulier (#{$demande->id}) envoyée.");
            } else {
                Log::warning("Aucun utilisateur Administrateur trouvé.");
            }
        }

            // 4. REDIRECTION EN CAS DE SUCCÈS
            return redirect()
                ->route('users.pretactif') // Assurez-vous que cette route existe
                ->with('success', 'Votre demande de prêt personnel a été soumise avec succès et est en cours de traitement.');

        } catch (\Exception $e) {
            // 5. GESTION DES ERREURS (Rollback si l'une des étapes a échoué)
            Log::error("Erreur lors de la soumission de la demande particulier: " . $e->getMessage(), ['exception' => $e]);

            // Suppression des fichiers temporaires ou partiels (si l'erreur a lieu APRES l'upload)
            // L'utilisation de DB::transaction garantit que les données DB sont annulées, 
            // mais les fichiers uploadés doivent être gérés manuellement si l'erreur survient après leur store.
            // Pour une solution simple, on se contente de l'annulation DB et de l'affichage d'un message générique.
            
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors du traitement de votre demande. Veuillez réessayer. (Détail: ' . $e->getMessage() . ')');
        }
    }

   

public function pretactif()
{
    // 1. Récupérer l'ID de l'utilisateur connecté
    $userId = Auth::id();

   
    $demandes = Particulier::where('user_id', $userId)
                             // Pour le débogage, on affiche tout d'abord :
                           ->whereIn('statut', ['validée', 'en cours', 'en attente']) 
                            ->orderBy('created_at', 'desc')
                            ->get();

    // 3. Passer la variable $demandes à la vue
    return view('Users.pretactif', compact('demandes'));
}
}