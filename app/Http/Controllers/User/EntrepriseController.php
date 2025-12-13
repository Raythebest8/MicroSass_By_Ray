<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Entreprise; 
use App\Models\User; 
use App\Models\Document; 
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Termwind\Components\Dd;
use App\Notifications\NewDemandePret;

class EntrepriseController extends Controller
{
    /**
     * Affiche le formulaire de demande de prêt pour les entreprises.
     */
    public function formEntreprise()
    {
        return view('users.demande.entreprise'); 
    }

    /**
     * Traite la soumission du formulaire et enregistre la demande d'entreprise.
     */
    public function submitEntreprise(Request $request)
    {
        // 1. VÉRIFICATION D'AUTHENTIFICATION
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour soumettre une demande.');
        }

        // 2. DÉFINITION ET EXÉCUTION DE LA VALIDATION
        try {
            $validatedData = $request->validate([
                // --- Étape 1: Informations Légales ---
                'nom_entreprise'    => ['required', 'string', 'max:255'],
                'forme_juridique'   => ['required', 'string', Rule::in(['sa', 'sarl', 'eurl', 'gics', 'autre'])], 
                'numero_rcm'        => ['required', 'string', 'max:50',],
                'date_creation'     => ['required', 'integer', 'min:1900', 'max:' . date('Y')],
                'secteur_activite'  => ['required', 'string', 'max:255'],
                'adresse_siege'     => ['required', 'string', 'max:255'],
                'contact_email'     => ['required', 'string', 'email', 'max:255'],
                'contact_tel'       => ['required', 'string', 'max:20'],

                // --- Étape 2: Informations Financières ---
                'ca_annuel'         => ['required', 'numeric', 'min:0'],
                'resultat_net'      => ['required', 'numeric'], 
                'capital_social'    => ['required', 'numeric', 'min:100000'], 
                'nombre_employes'   => ['required', 'integer', 'min:1'],
                'dettes_encours'    => ['required', 'numeric', 'min:0'],
                
                // --- Étape 3: Détails du Prêt ---
                'montant_souhaite'  => ['required', 'numeric', 'min:100000'], 
                'duree_mois'        => ['required', 'integer', 'min:12', 'max:120'],
                'motif'             => ['required', 'string', 'min:20'],
                'garanties_proposees' => ['nullable', 'string', 'max:500'],
                'apport_entreprise' => ['required', 'numeric', 'min:0'],

                // --- Étape 4: Documents Justificatifs (Uploads) ---
                'statuts_rcm'       => ['required', 'file', 'mimes:pdf,jpg,png', 'max:5120'], 
                'bilan_comptes.*'   => ['file', 'mimes:pdf', 'max:5120'], 
                'plan_tresorerie'   => ['required', 'file', 'mimes:pdf,jpg,png', 'max:5120'], 
                'releves_bancaires.*' => ['file', 'mimes:pdf,jpg,png', 'max:5120'], 
                'rib_entreprise'    => ['required', 'file', 'mimes:pdf,jpg,png', 'max:2048'],
            ]);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        $demande = null;
        
        // --- 3. TRANSACTION CRITIQUE ---
        try {
            DB::transaction(function () use ($validatedData, $request, &$demande) {
                
                // 3.A. CRÉATION DE L'ENREGISTREMENT INITIAL
                
                // Clés des fichiers à retirer avant la création de l'enregistrement dans la table 'entreprises'
                $fileKeys = ['statuts_rcm', 'bilan_comptes', 'plan_tresorerie', 'releves_bancaires', 'rib_entreprise'];
                
                $initialDataToCreate = array_merge(
                    array_diff_key($validatedData, array_flip($fileKeys)),
                    [
                        'user_id' => Auth::id(),
                        'statut' => 'en attente',
                        'admin_id' => null,
                        'date_traitement' => null,
                    ]
                );

                // Création initiale de l'enregistrement dans la base de données
                $demande = Entreprise::create($initialDataToCreate);
                $demandeId = $demande->id;
                $storagePath = "demandes/entreprises/{$demandeId}";

                // 3.B. UPLOAD ET CRÉATION DES ENREGISTREMENTS DANS LA TABLE 'documents'

                $singleFields = [
                    'statuts_rcm' => 'Statuts et RCM', 
                    'plan_tresorerie' => 'Plan de Trésorerie', 
                    'rib_entreprise' => 'RIB Entreprise'
                ];
                $multipleFields = [
                    'bilan_comptes' => 'Bilan et Comptes Annuels', 
                    'releves_bancaires' => 'Relevés Bancaires'
                ];
                
                // Traitement des fichiers UNQUES
                foreach ($singleFields as $field => $label) {
                    if ($request->hasFile($field)) {
                        $file = $request->file($field);
                        
                        // Stockage
                        $fileName = $field . '.' . $file->getClientOriginalExtension();
                        $path = $file->storeAs($storagePath, $fileName, 'public');
                        
                        // CRÉATION DE L'ENREGISTREMENT DANS LA TABLE 'documents'
                        Document::create([
                            'entreprise_id' => $demande->id, 
                            'type_document' => $field,      
                            'nom_afficher' => $label,       
                            'chemin_stockage' => $path,
                            'mime_type' => $file->getMimeType(),
                        ]);
                    }
                }

                // Traitement des fichiers MULTIPLES
                foreach ($multipleFields as $field => $label) {
                    if ($request->hasFile($field)) {
                        foreach ($request->file($field) as $index => $file) {
                            
                            // Stockage
                            $fileName = $field . '_' . ($index + 1) . '.' . $file->getClientOriginalExtension();
                            $path = $file->storeAs($storagePath, $fileName, 'public');
                            
                            // CRÉATION DE L'ENREGISTREMENT DANS LA TABLE 'documents'
                            Document::create([
                                'entreprise_id' => $demande->id, 
                                'type_document' => $field,      
                                'nom_afficher' => "{$label} (Fichier " . ($index + 1) . ")", 
                                'chemin_stockage' => $path,
                                'mime_type' => $file->getMimeType(),
                            ]);
                        }
                    }
                }
                
                // 3.C. MISE À JOUR : Plus aucune mise à jour des chemins sur le modèle Entreprise n'est nécessaire
                // puisque tout est dans la table `documents`.

            }); // Fin de la transaction

            if ($demande) { 
                // Notification de l'administrateur
                $admin = User::where('role', 'admin')->first(); 
                
                if ($admin) {
                    $admin->notify(new NewDemandePret($demande, 'entrepise')); 
                    Log::info("Notification de nouvelle demande entrepise (#{$demande->id}) envoyée.");
                } else {
                    Log::warning("Aucun utilisateur Administrateur trouvé.");
                }
            }

            // 4. REDIRECTION EN CAS DE SUCCÈS
            return redirect()
                ->route('users.pretactif')
                ->with('success', 'Votre demande de prêt pour entreprise a été soumise avec succès et est en cours d\'analyse.');

        } catch (\Exception $e) {
            // 5. GESTION DES ERREURS
            Log::error("Erreur critique lors de la soumission de la demande entreprise (ID User: " . Auth::id() . "): " . $e->getMessage(), ['exception' => $e]);
            
            // Si une erreur survient APRÈS l'upload mais avant la fin de la transaction, 
            // la transaction annule l'enregistrement en base, mais les fichiers restent sur le disque. 
            // C'est un compromis accepté en l'absence de gestion sophistiquée du nettoyage des fichiers.
            
            return back()
                ->withInput()
                ->with('error', 'Une erreur critique est survenue lors de l\'enregistrement de votre demande. Nos équipes sont alertées. Veuillez réessayer plus tard.'); 
        }
    }

    /**
     * Affiche l'historique des demandes de prêt pour entreprise de l'utilisateur.
     */
    public function pretactif()
    {
        $userId = Auth::id();

        // Récupérer les demandes de prêt de cet utilisateur (pour les entreprises)
        $demandes = Entreprise::where('user_id', $userId)
                             ->orderBy('created_at', 'desc')
                             ->get();
                                     
        // Je suppose que la vue 'Users.pretactif' est utilisée pour afficher l'historique.
        return view('Users.pretactif', compact('demandes'));
    }
}