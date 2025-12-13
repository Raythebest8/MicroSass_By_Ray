<?php

namespace App\Http\Controllers\User; // Assurez-vous que le namespace est correct (User ou user)

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Particulier; 
use App\Models\Document; // <-- NÉCESSAIRE : Pour enregistrer les documents
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Log; 
use App\Models\User; 
use App\Notifications\NewDemandePret;
use Illuminate\Validation\ValidationException; // Ajouté pour une meilleure gestion des erreurs

class ParticulierController extends Controller
{
    /**
     * Affiche le formulaire de demande de prêt pour les particuliers.
     */
    public function formParticulier()
    {
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
        try {
            $validatedData = $request->validate([
                // --- 1. Informations Personnelles et de Contact ---
                'nom'                   => ['required', 'string', 'max:255'],
                'prenom'                => ['required', 'string', 'max:255'],
                'email'                 => ['required', 'string', 'email', 'max:255'],
                'telephone'             => ['required', 'string', 'max:20'],
                'adresse'               => ['required', 'string', 'max:255'],
                'ville'                 => ['required', 'string', 'max:100'],
                'code_postal'           => ['required', 'string', 'max:10'],
                
                // --- 2. Informations Professionnelles et Financières ---
                'nom_employeur'         => ['nullable', 'string', 'max:255'],
                'secteur_activite'      => ['required', 'string', 'max:255'],
                'type_emploi'           => ['required', 'string', Rule::in(['CDI', 'CDD', 'Indépendant', 'Autre', 'Fonctionnaire'])], 
                'revenu_mensuel'        => ['required', 'numeric', 'min:50000'],
                
                // --- 3. Détails du Prêt ---
                'montant_souhaite'      => ['required', 'numeric', 'min:100000', 'max:10000000'], 
                'duree_mois'            => ['required', 'integer', 'min:3', 'max:84'],
                'motif'                 => ['required', 'string', 'min:4'],

                // --- 4. Documents Justificatifs (Uploads) ---
                'justificatif_id'       => ['required', 'file', 'mimes:pdf,jpg,png', 'max:3072'], 
                'justificatif_domicile' => ['required', 'file', 'mimes:pdf,jpg,png', 'max:3072'], 
                'preuves_revenu'        => ['required', 'file', 'mimes:pdf', 'max:5120'], 
                'rib'                   => ['required', 'file', 'mimes:pdf,jpg,png', 'max:2048'],
            ]);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
        
        $demande = null;
        
        // --- 3. TRANSACTION CRITIQUE ---
        try {
            DB::transaction(function () use ($validatedData, $request, &$demande) { 
                
                // Clés des fichiers à EXCLURE de la création de l'enregistrement Particulier
                $fileKeys = ['justificatif_id', 'justificatif_domicile', 'preuves_revenu', 'rib'];
                
                // Construction des données à insérer dans la table `particuliers` (sans les chemins de fichiers)
                $initialDataToCreate = array_merge(
                    array_diff_key($validatedData, array_flip($fileKeys)), // <-- C'est cette ligne qui corrige l'erreur SQL 1364
                    [
                        'user_id' => Auth::id(),
                        'statut' => 'en attente',
                        'admin_id' => null,
                        'date_traitement' => null,
                    ]
                );

                // 3.A. CRÉATION DE L'ENREGISTREMENT INITIAL 
                $demande = Particulier::create($initialDataToCreate);
                $demandeId = $demande->id;
                $storagePath = "demandes/particuliers/{$demandeId}";

                // 3.B. UPLOAD ET CRÉATION DES ENREGISTREMENTS DANS LA TABLE 'documents'
                
                $documentsToUpload = [
                    'justificatif_id'       => 'Pièce d\'Identité',
                    'justificatif_domicile' => 'Justificatif de Domicile',
                    'preuves_revenu'        => 'Preuves de Revenu (PDF)',
                    'rib'                   => 'RIB Personnel'
                ];

                foreach ($documentsToUpload as $field => $label) {
                    if ($request->hasFile($field)) {
                        $file = $request->file($field);
                        
                        // Stockage
                        $fileName = $field . '.' . $file->getClientOriginalExtension();
                        $path = $file->storeAs($storagePath, $fileName, 'public');
                        
                        // CRÉATION D'UNE ENTRÉE DANS LA TABLE 'documents'
                        Document::create([
                            'particulier_id' => $demande->id, // Clé étrangère
                            'type_document' => $field,      
                            'nom_afficher' => $label,       
                            'chemin_stockage' => $path,
                            'mime_type' => $file->getMimeType(),
                        ]);
                    }
                }

                // 3.C. MISE À JOUR : Supprimé car inutile, tout est dans `documents`.

            }); // Fin de la transaction

            if ($demande) { 
                // Notification de l'administrateur
                $admin = User::where('role', 'admin')->first(); 
                
                if ($admin) {
                    $admin->notify(new NewDemandePret($demande, 'particulier')); 
                    Log::info("Notification de nouvelle demande particulier (#{$demande->id}) envoyée.");
                } else {
                    Log::warning("Aucun utilisateur Administrateur trouvé.");
                }
            }

            // 4. REDIRECTION EN CAS DE SUCCÈS
            return redirect()
                ->route('users.pretactif')
                ->with('success', 'Votre demande de prêt personnel a été soumise avec succès et est en cours de traitement.');

        } catch (\Exception $e) {
            // 5. GESTION DES ERREURS
            Log::error("Erreur lors de la soumission de la demande particulier: " . $e->getMessage(), ['exception' => $e]);
            
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors du traitement de votre demande. Veuillez réessayer. (Détail: ' . $e->getMessage() . ')');
        }
    }

    /**
     * Affiche l'historique des demandes de prêt pour particulier de l'utilisateur.
     */
    public function pretactif()
    {
        $userId = Auth::id();
        
        $demandes = Particulier::where('user_id', $userId)
                             ->whereIn('statut', ['validée', 'en cours', 'en attente']) 
                             ->orderBy('created_at', 'desc')
                             ->get();

        return view('Users.pretactif', compact('demandes'));
    }
}