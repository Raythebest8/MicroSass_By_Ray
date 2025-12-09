@extends('layouts.users')

@section('content')

<div class="max-w-4xl mx-auto my-12 p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-indigo-100 dark:border-indigo-900">
  
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 dark:bg-green-900 dark:text-green-300 dark:border-green-700" role="alert">
            <strong class="font-bold">Succès!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6 dark:bg-red-900 dark:text-red-300 dark:border-red-700" role="alert">
            <strong class="font-bold">Erreur!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif
    
   
    @if ($errors->any())
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-6 dark:bg-yellow-900 dark:text-yellow-300 dark:border-yellow-700" role="alert">
            <strong class="font-bold">Attention!</strong>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-8 text-center border-b pb-4 border-gray-200 dark:border-gray-700">
        <h3 class="text-3xl font-extrabold text-indigo-700 dark:text-indigo-400">
            Demande de Prêt Particulier
        </h3>
        <p class="text-md text-gray-500 dark:text-gray-400 mt-2">
            Veuillez compléter les 4 étapes pour soumettre votre demande.
        </p>
    </div>

    <div class="mb-8">
        <div class="flex justify-between items-center text-sm font-medium text-gray-500 dark:text-gray-400">
            <span id="step-name">Étape 1: Informations Personnelles</span>
            <span id="step-count">1 / 4</span>
        </div>
        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mt-2">
            <div id="progress-bar" class="bg-indigo-600 h-2.5 rounded-full transition-all duration-500" style="width: 25%"></div>
        </div>
    </div>

    <form id="loan-form" action="{{ route('users.demande.submitParticulier') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div id="step-1" class="step-content">
            <h4 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100 border-l-4 border-indigo-500 pl-3">
                1. Informations Personnelles
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 dark:bg-gray-700 p-6 rounded-lg mb-8">
                
                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom</label>
                    <input type="text" name="nom" id="nom" required placeholder="Votre nom"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white">
                </div>
                <div>
                    <label for="prenom" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prénom(s)</label>
                    <input type="text" name="prenom" id="prenom" required placeholder="Votre prénom"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                    <input type="email" name="email" id="email" required placeholder="exemple@mail.com"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white">
                </div>
                <div>
                    <label for="telephone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Téléphone</label>
                    <input type="telephone" name="telephone" id="telephone" required placeholder="Ex: 00 00 00 00"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white">
                </div>

                <div class="md:col-span-2">
                    <label for="adresse" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Adresse Complète</label>
                    <input type="text" name="adresse" id="adresse" required placeholder="Rue, quartier, boîte postale..."
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white">
                </div>

                <div>
                    <label for="ville" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ville</label>
                    <input type="text" name="ville" id="ville" required placeholder="Ex: Lomé"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white">
                </div>
                <div>
                    <label for="code_postal" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Code Postal</label>
                    <input type="text" name="code_postal" id="code_postal" placeholder="Code postal (optionnel)"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white">
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="button" onclick="nextStep(1)" class="px-6 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                    Suivant <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>

        <div id="step-2" class="step-content hidden">
            <h4 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100 border-l-4 border-indigo-500 pl-3">
                2. Situation Financière et Revenus
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 dark:bg-gray-700 p-6 rounded-lg mb-8">

                <div class="md:col-span-2">
                    <label for="nom_employeur" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom de l'Employeur / Entreprise</label>
                    <input type="text" name="nom_employeur" id="nom_employeur"  placeholder="Nom de votre lieu de travail"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white">
                </div>
                
                <div>
                    <label for="secteur_activite" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Secteur d'Activité</label>
                    <input type="text" name="secteur_activite" id="secteur_activite" required placeholder="Ex: Finance, Éducation, BTP..."
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white">
                </div>

                <div>
                    <label for="type_emploi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type d'Emploi</label>
                    <select name="type_emploi" id="type_emploi" required
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white">
                        <option value="">Sélectionner</option>
                        <option value="CDI">CDI</option>
                        <option value="CDD">CDD</option>
                        <option value="Fonctionnaire">Fonctionnaire</option>
                        <option value="Indépendant">Indépendant/Libéral</option>
                        <option value="Autre">Autre</option>
                    </select>
                </div>

                <div>
                    <label for="revenu_mensuel" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Revenu Mensuel Net (FCFA)</label>
                    <input type="number" name="revenu_mensuel" id="revenu_mensuel" required placeholder="Ex: 200000"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white">
                </div>
            </div>
            
            <div class="flex justify-between">
                <button type="button" onclick="prevStep(2)" class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-base font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                    <i class="fas fa-arrow-left mr-2"></i> Précédent
                </button>
                <button type="button" onclick="nextStep(2)" class="px-6 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                    Suivant <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>

        <div id="step-3" class="step-content hidden">
            <h4 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100 border-l-4 border-indigo-500 pl-3">
                3. Détails du Prêt et du Projet
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 dark:bg-gray-700 p-6 rounded-lg mb-8">
                
                <div>
                    <label for="montant_souhaite" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Montant souhaité (FCFA)</label>
                    <input type="number" name="montant_souhaite" id="montant_souhaite" required
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                           min="10000" placeholder="Ex: 1400000">
                </div>

                <div>
                    <label for="duree_mois" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Durée (en mois)</label>
                    <input type="number" name="duree_mois" id="duree_mois" required
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                           min="6" max="84" placeholder="Ex: 36">
                </div>
                
                <div class="md:col-span-2">
                    <label for="motif" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Motif du Prêt</label>
                    <textarea name="motif" id="motif" rows="3" required placeholder="Décrivez brièvement le projet ou l'objectif du prêt"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"></textarea>
                </div>

            </div>
            
            <div class="flex justify-between">
                <button type="button" onclick="prevStep(3)" class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-base font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                    <i class="fas fa-arrow-left mr-2"></i> Précédent
                </button>
                <button type="button" onclick="nextStep(3)" class="px-6 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                    Suivant <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>
        
        <div id="step-4" class="step-content hidden">
            <h4 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100 border-l-4 border-indigo-500 pl-3">
                4. Documents Justificatifs (Uploads)
            </h4>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                Veuillez télécharger les documents requis. Les formats acceptés sont PDF, JPG ou PNG.
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 dark:bg-gray-700 p-6 rounded-lg mb-8">

                <div>
                    <label for="justificatif_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pièce d'Identité (Recto-verso)</label>
                    <input type="file" name="justificatif_id" id="justificatif_id" required
                           class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:py-2 file:px-4 file:rounded-full file:border-0 file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-900 dark:file:text-indigo-300">
                </div>

                <div>
                    <label for="justificatif_domicile" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Justificatif de Domicile (-3 mois)</label>
                    <input type="file" name="justificatif_domicile" id="justificatif_domicile" required
                           class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:py-2 file:px-4 file:rounded-full file:border-0 file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-900 dark:file:text-indigo-300">
                </div>

                <div>
                    <label for="preuves_revenu" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bulletins de Salaire / Preuves de Revenu</label>
                    <input type="file" name="preuves_revenu" id="preuves_revenu" required
                           class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:py-2 file:px-4 file:rounded-full file:border-0 file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-900 dark:file:text-indigo-300">
                </div>

                <div>
                    <label for="rib" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Relevé d'Identité Bancaire (RIB/IBAN)</label>
                    <input type="file" name="rib" id="rib" required
                           class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:py-2 file:px-4 file:rounded-full file:border-0 file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-900 dark:file:text-indigo-300">
                </div>

            </div>

            <div class="flex justify-between mt-10">
                <button type="button" onclick="prevStep(4)" class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-base font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                    <i class="fas fa-arrow-left mr-2"></i> Précédent
                </button>
                <button type="submit"
                        class="px-6 py-2 flex justify-center border border-transparent rounded-md shadow-sm text-lg font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                    Soumettre la Demande <i class="fas fa-check ml-2"></i>
                </button>
            </div>
        </div>
        
    </form>
    
</div>

<script>
    let currentStep = 1;
    const totalSteps = 4;
    
    // Noms des étapes pour l'affichage
    const stepNames = {
        1: "Informations Personnelles",
        2: "Situation Financière et Revenus",
        3: "Détails du Prêt et du Projet",
        4: "Documents Justificatifs"
    };

    /**
     * Affiche l'étape spécifiée et met à jour la barre de progression.
     */
    function showStep(step) {
        // Masquer toutes les étapes
        document.querySelectorAll('.step-content').forEach(element => {
            element.classList.add('hidden');
        });

        // Afficher l'étape actuelle
        document.getElementById(`step-${step}`).classList.remove('hidden');
        
        // Mettre à jour l'affichage de progression
        const percentage = (step / totalSteps) * 100;
        document.getElementById('progress-bar').style.width = `${percentage}%`;
        document.getElementById('step-name').textContent = `Étape ${step}: ${stepNames[step]}`;
        document.getElementById('step-count').textContent = `${step} / ${totalSteps}`;
        
        currentStep = step;
        window.scrollTo(0, 0); // Remonter en haut de la page lors du changement d'étape
    }
    
    /**
     * Tente de passer à l'étape suivante après validation des champs requis.
     */
    function nextStep(step) {
        const currentStepElement = document.getElementById(`step-${step}`);
        const requiredInputs = currentStepElement.querySelectorAll('[required]');
        let allValid = true;

        // Validation simple: Vérifier si les champs requis sont remplis
        requiredInputs.forEach(input => {
            if (!input.value.trim() && input.type !== 'file') {
                 allValid = false;
                 // Vous pouvez ajouter ici un style visuel d'erreur
            }
            // Validation de base pour les fichiers (non parfait mais empêche de sauter l'étape)
            if (input.type === 'file' && input.files.length === 0) {
                 allValid = false;
            }
        });
        
        if (allValid && currentStep < totalSteps) {
            showStep(step + 1);
        } else if (!allValid) {
            alert("Veuillez remplir tous les champs obligatoires de cette section.");
            // Logique d'affichage d'erreurs plus fine ici
        }
    }
    
    /**
     * Retourne à l'étape précédente.
     */
    function prevStep(step) {
        if (currentStep > 1) {
            showStep(step - 1);
        }
    }

    // Assurez-vous que seule la première étape est visible au chargement
    document.addEventListener('DOMContentLoaded', () => {
        showStep(1);
    });

    // Optionnel: Empêcher la soumission du formulaire par ENTER
    document.getElementById('loan-form').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            // Tente de passer à l'étape suivante au lieu de soumettre
            nextStep(currentStep); 
        }
    });
</script>

@endsection