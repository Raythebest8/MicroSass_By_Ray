<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Entreprise; 
use App\Models\User; 
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Termwind\Components\Dd;

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
                
                // Champs multiples
                // 'bilan_comptes'     => ['required', 'array', 'min:1'],
                'bilan_comptes.*'   => ['file', 'mimes:pdf', 'max:5120'], 

                'plan_tresorerie'   => ['required', 'file', 'mimes:pdf,jpg,png', 'max:5120'], 
                
                // Champs multiples
                // 'releves_bancaires' => ['required', 'array', 'min:1'],
                'releves_bancaires.*' => ['file', 'mimes:pdf,jpg,png', 'max:5120'], 
                
                'rib_entreprise'    => ['required', 'file', 'mimes:pdf,jpg,png', 'max:2048'],
            ]);
        } catch (ValidationException $e) {
            // Laisse Laravel gérer la redirection avec les erreurs
            return back()->withErrors($e->errors())->withInput();
        }

        $paths = [];
        $demande = null;
        
        // --- 3. TRANSACTION CRITIQUE ---
        try {
            DB::transaction(function () use ($validatedData, $request, &$demande, &$paths) {
                
                // 3.A. CRÉATION DE L'ENREGISTREMENT INITIAL (SANS les chemins de fichiers)
                $initialData = array_merge($validatedData, [
                    'user_id' => Auth::id(),
                    'statut' => 'en attente',
                    'admin_id' => null,
                    'date_traitement' => null,
                ]);
                
                // Retirer les clés des fichiers de l'ensemble de données initiales avant 'create'
                $fileKeys = ['statuts_rcm', 'bilan_comptes', 'plan_tresorerie', 'releves_bancaires', 'rib_entreprise'];
                $initialDataToCreate = array_diff_key($initialData, array_flip($fileKeys));

                // Création initiale de l'enregistrement dans la base de données
                $demande = Entreprise::create($initialDataToCreate);
                $demandeId = $demande->id;
                $storagePath = "demandes/entreprises/{$demandeId}";

                // 3.B. UPLOAD ET ENREGISTREMENT DES CHEMINS

                $singleFields = ['statuts_rcm', 'plan_tresorerie', 'rib_entreprise'];
                $multipleFields = ['bilan_comptes', 'releves_bancaires'];
                
                // Traitement des fichiers UNQUES
                foreach ($singleFields as $field) {
                    if ($request->hasFile($field)) {
                        $file = $request->file($field);
                        // Utilisation du nom de champ comme préfixe pour le nom de fichier
                        $fileName = $field . '.' . $file->getClientOriginalExtension();
                        $path = $file->storeAs($storagePath, $fileName, 'public');
                        $paths[$field] = $path;
                    }
                }

                // Traitement des fichiers MULTIPLES (Tableau de chemins)
                foreach ($multipleFields as $field) {
                    if ($request->hasFile($field)) {
                        $currentPaths = [];
                        foreach ($request->file($field) as $index => $file) {
                            $fileName = $field . '_' . ($index + 1) . '.' . $file->getClientOriginalExtension();
                            $path = $file->storeAs($storagePath, $fileName, 'public');
                            $currentPaths[] = $path;
                        }
                        // STOCKAGE EN TANT QUE TABLEAU PHP - Laravel (avec casts) le convertira en JSON
                        $paths[$field] = $currentPaths; 
                    }
                }
                
                // 3.C. MISE À JOUR DE L'ENREGISTREMENT AVEC LES CHEMINS RÉELS
                // N'oublie pas : les colonnes 'bilan_comptes' et 'releves_bancaires' DOIVENT être castées en 'array' dans le modèle.
                $demande->update($paths);

            }); // Fin de la transaction

            // 4. REDIRECTION EN CAS DE SUCCÈS
            return redirect()
                ->route('users.pretactif') // Assurez-vous que cette route existe
                ->with('success', 'Votre demande de prêt pour entreprise a été soumise avec succès et est en cours d\'analyse.');

        } catch (\Exception $e) {
            // 5. GESTION DES ERREURS
            Log::error("Erreur critique lors de la soumission de la demande entreprise (ID User: " . Auth::id() . "): " . $e->getMessage(), ['exception' => $e]);
            
            // Retourne à l'utilisateur un message générique
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