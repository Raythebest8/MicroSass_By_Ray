@extends('layouts.users')

@section('content')

<div class="max-w-4xl mx-auto my-12 p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-indigo-100 dark:border-indigo-900">

    {{-- Gestion des Messages d'Erreur et de Succès --}}
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if (session('error'))
    <div class="mb-4 p-4 text-sm text-red-800 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
        {{ session('error') }}
    </div>
    @endif

    @if (session('success'))
    <div class="mb-4 p-4 text-sm text-green-800 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
        {{ session('success') }}
    </div>
    @endif

    <div class="mb-8 text-center border-b pb-4 border-gray-200 dark:border-gray-700">
        <h3 class="text-3xl font-extrabold text-indigo-700 dark:text-indigo-400">
            Demande de Prêt Entreprise
        </h3>
        <p class="text-md text-gray-500 dark:text-gray-400 mt-2">
            Veuillez compléter les 4 étapes en fournissant les informations légales et financières de votre entreprise.
        </p>
    </div>

    <div class="mb-8">
        <div class="flex justify-between items-center text-sm font-medium text-gray-500 dark:text-gray-400">
            <span id="step-name">Étape 1: Informations Légales de l'Entreprise</span>
            <span id="step-count">1 / 4</span>
        </div>
        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mt-2">
            <div id="progress-bar" class="bg-indigo-600 h-2.5 rounded-full transition-all duration-500" style="width: 25%"></div>
        </div>
    </div>

    {{-- DÉBUT DU FORMULAIRE : L'attribut enctype est correct --}}
    <form id="loan-form" action="{{ route('users.demande.submitEntreprise') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- ========================================================================= --}}
        {{-- ÉTAPE 1: Informations Légales de l'Entreprise --}}
        {{-- ========================================================================= --}}
        <div id="step-1" class="step-content">
            <h4 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100 border-l-4 border-indigo-500 pl-3">
                1. Informations Légales de l'Entreprise
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 dark:bg-gray-700 p-6 rounded-lg mb-8">

                {{-- Nom et Forme Juridique --}}
                <div class="md:col-span-2">
                    <label for="nom_entreprise" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Raison Sociale de l'Entreprise</label>
                    <input type="text" name="nom_entreprise" id="nom_entreprise" required placeholder="Ex: ABC Technologies S.A."
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                        value="{{ old('nom_entreprise') }}">
                    @error('nom_entreprise')<p class="text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="forme_juridique" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Forme Juridique</label>
                    <select name="forme_juridique" id="forme_juridique" required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white">
                        <option value="">Sélectionner</option>
                        <option value="sa" {{ old('forme_juridique') == 'sa' ? 'selected' : '' }}>S.A.</option>
                        <option value="sarl" {{ old('forme_juridique') == 'sarl' ? 'selected' : '' }}>S.A.R.L.</option>
                        <option value="eurl" {{ old('forme_juridique') == 'eurl' ? 'selected' : '' }}>E.U.R.L.</option>
                        <option value="gics" {{ old('forme_juridique') == 'gics' ? 'selected' : '' }}>G.I.C.S.</option>
                        <option value="autre" {{ old('forme_juridique') == 'autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                    @error('forme_juridique')<p class="text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Numéro d'enregistrement --}}
                <div>
                    <label for="numero_rcm" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Numéro R.C.M. (Registre de Commerce)</label>
                    <input type="text" name="numero_rcm" id="numero_rcm" required placeholder="Ex: TOGO-LOME-..."
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                        value="{{ old('numero_rcm') }}">
                    @error('numero_rcm')<p class="text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Date de Création --}}
                <div>
                    <label for="date_creation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de Création (Année)</label>
                    <input type="number" name="date_creation" id="date_creation" required placeholder="Ex: 2018" min="1900" max="{{ date('Y') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                        value="{{ old('date_creation') }}">
                    @error('date_creation')<p class="text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Secteur d'Activité --}}
                <div>
                    <label for="secteur_activite" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Secteur d'Activité Principal</label>
                    <input type="text" name="secteur_activite" id="secteur_activite" required placeholder="Ex: Bâtiment et Travaux Publics"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                        value="{{ old('secteur_activite') }}">
                    @error('secteur_activite')<p class="text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Adresse et Contact --}}
                <div class="md:col-span-2">
                    <label for="adresse_siege" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Adresse du Siège Social</label>
                    <input type="text" name="adresse_siege" id="adresse_siege" required placeholder="Adresse complète"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                        value="{{ old('adresse_siege') }}">
                    @error('adresse_siege')<p class="text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="contact_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email de Contact</label>
                    <input type="email" name="contact_email" id="contact_email" required placeholder="contact@entreprise.com"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                        value="{{ old('contact_email') }}">
                    @error('contact_email')<p class="text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="contact_tel" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Téléphone de l'Entreprise</label>
                    <input type="tel" name="contact_tel" id="contact_tel" required placeholder="Ex: 00 00 00 00"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                        value="{{ old('contact_tel') }}">
                    @error('contact_tel')<p class="text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

            </div>

            <div class="flex justify-end">
                <button type="button" onclick="nextStep(1)" class="px-6 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                    Suivant <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>

        {{-- ========================================================================= --}}
        {{-- ÉTAPE 2: Informations Financières Clés --}}
        {{-- ========================================================================= --}}
        <div id="step-2" class="step-content hidden">
            <h4 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100 border-l-4 border-indigo-500 pl-3">
                2. Informations Financières Clés
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 dark:bg-gray-700 p-6 rounded-lg mb-8">

                {{-- Chiffre d'Affaires Annuel --}}
                <div>
                    <label for="ca_annuel" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Chiffre d'Affaires Annuel (Dernière année, FCFA)</label>
                    <input type="number" name="ca_annuel" id="ca_annuel" required placeholder="Ex: 50000000"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                        value="{{ old('ca_annuel') }}">
                    @error('ca_annuel')<p class="text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Résultat Net --}}
                <div>
                    <label for="resultat_net" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Résultat Net (Dernière année, FCFA)</label>
                    <input type="number" name="resultat_net" id="resultat_net" required placeholder="Ex: 5000000 (Peut être négatif)"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                        value="{{ old('resultat_net') }}">
                    @error('resultat_net')<p class="text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Capital Social --}}
                <div>
                    <label for="capital_social" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Capital Social (FCFA)</label>
                    <input type="number" name="capital_social" id="capital_social" required placeholder="Ex: 1000000"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                        value="{{ old('capital_social') }}">
                    @error('capital_social')<p class="text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Nombre d'employés --}}
                <div>
                    <label for="nombre_employes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre d'Employés (CDI/CDD)</label>
                    <input type="number" name="nombre_employes" id="nombre_employes" required placeholder="Ex: 8"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                        value="{{ old('nombre_employes') }}">
                    @error('nombre_employes')<p class="text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Dettes/Emprunts en cours --}}
                <div class="md:col-span-2">
                    <label for="dettes_encours" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Montant total des emprunts ou dettes en cours (FCFA)</label>
                    <input type="number" name="dettes_encours" id="dettes_encours" value="{{ old('dettes_encours', 0) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white">
                    @error('dettes_encours')<p class="text-sm text-red-500">{{ $message }}</p>@enderror
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

        {{-- ========================================================================= --}}
        {{-- ÉTAPE 3: Détails du Prêt et du Projet d'Investissement --}}
        {{-- ========================================================================= --}}
        <div id="step-3" class="step-content hidden">
            <h4 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100 border-l-4 border-indigo-500 pl-3">
                3. Détails du Prêt et du Projet d'Investissement
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 dark:bg-gray-700 p-6 rounded-lg mb-8">

                <div>
                    <label for="montant_souhaite" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Montant du Financement souhaité (FCFA)</label>
                    <input type="number" name="montant_souhaite" id="montant_souhaite" required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                        min="100000" placeholder="Ex: 5000000" value="{{ old('montant_souhaite') }}">
                    @error('montant_souhaite')<p class="text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="duree_mois" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Durée souhaitée (en mois)</label>
                    <input type="number" name="duree_mois" id="duree_mois" required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                        min="12" max="120" placeholder="Ex: 60" value="{{ old('duree_mois') }}">
                    @error('duree_mois')<p class="text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label for="motif" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Motif du Prêt / Description du Projet</label>
                    <textarea name="motif" id="motif" rows="4" required placeholder="Décrivez en détail l'utilisation des fonds (ex: Achat d'une nouvelle machine, extension du bâtiment, fonds de roulement...)"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white">{{ old('motif') }}</textarea>
                    @error('motif')<p class="text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="garanties_proposees" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Garanties proposées</label>
                    <input type="text" name="garanties_proposees" id="garanties_proposees" placeholder="Hypothèque, caution personnelle, nantissement..."
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                        value="{{ old('garanties_proposees') }}">
                    @error('garanties_proposees')<p class="text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="apport_entreprise" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Apport de l'Entreprise au Projet (FCFA)</label>
                    <input type="number" name="apport_entreprise" id="apport_entreprise" value="{{ old('apport_entreprise', 0) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white">
                    @error('apport_entreprise')<p class="text-sm text-red-500">{{ $message }}</p>@enderror
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

        {{-- ========================================================================= --}}
        {{-- ÉTAPE 4: Documents Justificatifs (Uploads) --}}
        {{-- ========================================================================= --}}
        <div id="step-4" class="step-content hidden">
            <h4 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100 border-l-4 border-indigo-500 pl-3">
                4. Documents Justificatifs (Uploads)
            </h4>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                Veuillez télécharger les documents obligatoires (formats PDF, JPG recommandés).
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 dark:bg-gray-700 p-6 rounded-lg mb-8">

                {{-- Statuts/RCM --}}
                <div>
                    <label for="statuts_rcm" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Statuts de l'entreprise et RCM</label>
                    <input type="file" name="statuts_rcm" id="statuts_rcm" required
                        class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:py-2 file:px-4 file:rounded-full file:border-0 file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-900 dark:file:text-indigo-300">
                    @error('statuts_rcm')<p class="text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Bilan et Comptes de Résultat --}}
                <div>
                    <label for="bilan_comptes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bilan et Comptes de Résultat (2 dernières années)</label>
                    {{-- Note: name="bilan_comptes[]" pour les fichiers multiples --}}
                    <input type="file" name="bilan_comptes[]" id="bilan_comptes" multiple required
                        class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:py-2 file:px-4 file:rounded-full file:border-0 file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-900 dark:file:text-indigo-300">
                    @error('bilan_comptes')<p class="text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Plan de Trésorerie / Prévisionnel --}}
                <div>
                    <label for="plan_tresorerie" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Plan de Trésorerie et/ou Business Plan</label>
                    <input type="file" name="plan_tresorerie" id="plan_tresorerie" required
                        class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:py-2 file:px-4 file:rounded-full file:border-0 file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-900 dark:file:text-indigo-300">
                    @error('plan_tresorerie')<p class="text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Relevés Bancaires --}}
                <div>
                    <label for="releves_bancaires" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Relevés Bancaires de l'Entreprise (3 derniers mois)</label>
                    {{-- Note: name="releves_bancaires[]" pour les fichiers multiples --}}
                    <input type="file" name="releves_bancaires[]" id="releves_bancaires" multiple required
                        class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:py-2 file:px-4 file:rounded-full file:border-0 file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-900 dark:file:text-indigo-300">
                    @error('releves_bancaires')<p class="text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- RIB/IBAN --}}
                <div class="md:col-span-2">
                    <label for="rib_entreprise" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Relevé d'Identité Bancaire (RIB) de l'Entreprise</label>
                    <input type="file" name="rib_entreprise" id="rib_entreprise" required
                        class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:py-2 file:px-4 file:rounded-full file:border-0 file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-900 dark:file:text-indigo-300">
                    @error('rib_entreprise')<p class="text-sm text-red-500">{{ $message }}</p>@enderror
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

    const stepNames = {
        1: "Informations Légales de l'Entreprise",
        2: "Informations Financières Clés",
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
        const currentStepElement = document.getElementById(`step-${step}`);
        if (currentStepElement) {
            currentStepElement.classList.remove('hidden');
        } else {
            console.error(`L'étape ${step} n'existe pas.`);
            return;
        }

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

        requiredInputs.forEach(input => {
            if (input.type === 'file') {
                // Pour les fichiers, vérifions si au moins un fichier est sélectionné (gère multiple ou non)
                if (input.files.length === 0) {
                    allValid = false;
                    input.style.borderColor = 'red';
                } else {
                    input.style.borderColor = '';
                }
            } else if (!input.value.trim()) {
                allValid = false;
                input.style.borderColor = 'red'; // Marquer visuellement l'erreur
            } else {
                input.style.borderColor = '';
            }
        });

        if (allValid && currentStep < totalSteps) {
            showStep(step + 1);
        } else if (!allValid) {
            alert("Veuillez remplir tous les champs obligatoires (*) de cette section.");
            // Focus sur le premier champ invalide pour aider l'utilisateur
            const firstInvalid = currentStepElement.querySelector('[required]:invalid, [required].border-red-500');
            if (firstInvalid) {
                firstInvalid.focus();
            }
        }
        // Si allValid est vrai et currentStep == totalSteps (étape 4), le bouton type="submit" fera le travail.
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
        // Afficher la bonne étape si l'utilisateur revient après une erreur de validation Laravel
        const errors = JSON.parse('@json($errors->keys())');
        let stepToShow = 1;

        // Mapping simple pour déterminer l'étape à afficher en cas d'erreur de validation
        if (errors.length > 0) {
            const step1Fields = ['nom_entreprise', 'forme_juridique', 'numero_rcm', 'date_creation', 'secteur_activite', 'adresse_siege', 'contact_email', 'contact_tel'];
            const step2Fields = ['ca_annuel', 'resultat_net', 'capital_social', 'nombre_employes', 'dettes_encours'];
            const step3Fields = ['montant_souhaite', 'duree_mois', 'motif', 'garanties_proposees', 'apport_entreprise'];
            const step4Fields = ['statuts_rcm', 'bilan_comptes', 'plan_tresorerie', 'releves_bancaires', 'rib_entreprise'];

            if (errors.some(error => step4Fields.includes(error) || error.startsWith('bilan_comptes.') || error.startsWith('releves_bancaires.'))) {
                stepToShow = 4;
            } else if (errors.some(error => step3Fields.includes(error))) {
                stepToShow = 3;
            } else if (errors.some(error => step2Fields.includes(error))) {
                stepToShow = 2;
            }
        }

        showStep(stepToShow);
    });

    // *******************************************************************
    // ** CORRECTION CRITIQUE : Cette fonction bloquait la soumission. **
    // ** Elle est commentée ou retirée pour permettre au bouton SUBMIT de fonctionner. **
    // *******************************************************************
    // document.getElementById('loan-form').addEventListener('keydown', function(e) {
    //     if (e.key === 'Enter') {
    //         e.preventDefault(); 
    //         if (currentStep < totalSteps) {
    //             nextStep(currentStep); 
    //         }
    //     }
    // });
</script>

@endsection