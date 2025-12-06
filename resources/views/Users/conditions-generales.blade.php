@extends('layouts.users')

@section('content')

<div class="max-w-6xl mx-auto my-8 p-4 md:p-8 bg-white dark:bg-gray-900 rounded-xl shadow-2xl">

    <h2 class="text-3xl font-extrabold mb-4 text-indigo-700 dark:text-indigo-400">
        Conditions d'Éligibilité et Constitution du Dossier
    </h2>
    <p class="text-gray-600 dark:text-gray-300 mb-8">
        Veuillez préparer l'ensemble des documents listés ci-dessous pour assurer un traitement rapide et complet de votre demande. Les pièces varient selon que vous soyez un particulier ou une entreprise.
    </p>

    <hr class="mb-8 border-indigo-200 dark:border-indigo-800">

    <div class="mb-12">
        <h3 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100 flex items-center">
            <i class="fas fa-user-circle mr-3 text-2xl text-emerald-500"></i>
            Dossier Particulier : Prêt Personnel ou Immobilier
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-lg shadow-md">
                <h4 class="text-lg font-semibold mb-3 border-b pb-2 border-gray-200 dark:border-gray-700 text-indigo-600 dark:text-indigo-400">
                    Pièces d'Identité et Domicile
                </h4>
                <ul class="space-y-3 text-gray-700 dark:text-gray-300">
                    <li class="flex items-start"><i class="fas fa-id-card text-emerald-500 mr-3 mt-1"></i> Justificatif d'identité en cours de validité (copie recto-verso de la carte nationale d'identité, passeport, etc.).</li>
                    <li class="flex items-start"><i class="fas fa-home text-emerald-500 mr-3 mt-1"></i> Justificatif de domicile de moins de trois mois (facture d'électricité, de gaz, d'eau, de téléphone fixe, quittance de loyer, avis de taxe foncière, etc.).</li>
                    <li class="flex items-start"><i class="fas fa-university text-emerald-500 mr-3 mt-1"></i> Relevé d'Identité Bancaire (RIB) ou IBAN.</li>
                </ul>
            </div>

            <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-lg shadow-md">
                <h4 class="text-lg font-semibold mb-3 border-b pb-2 border-gray-200 dark:border-gray-700 text-indigo-600 dark:text-indigo-400">
                    Justificatifs de Revenus et Situation Financière
                </h4>
                <ul class="space-y-3 text-gray-700 dark:text-gray-300">
                    <li class="flex items-start"><i class="fas fa-file-invoice-dollar text-emerald-500 mr-3 mt-1"></i> Les **trois derniers bulletins de salaire** (pour les salariés).</li>
                    <li class="flex items-start"><i class="fas fa-file-alt text-emerald-500 mr-3 mt-1"></i> Le dernier ou les deux derniers avis d'imposition ou de non-imposition.</li>
                    <li class="flex items-start"><i class="fas fa-money-check-alt text-emerald-500 mr-3 mt-1"></i> Les **trois derniers relevés de comptes** bancaires personnels.</li>
                    <li class="flex items-start"><i class="fas fa-chart-pie text-emerald-500 mr-3 mt-1"></i> Les tableaux d'amortissement de tous les prêts en cours (immobilier, consommation).</li>
                    <li class="flex items-start"><i class="fas fa-piggy-bank text-emerald-500 mr-3 mt-1"></i> Justificatif de l'apport personnel (relevé d'épargne) si le projet en nécessite un.</li>
                    <li class="flex items-start"><i class="fas fa-users text-emerald-500 mr-3 mt-1"></i> Justificatifs de la situation familiale (livret de famille, contrat de mariage ou PACS, jugement de divorce, etc.).</li>
                </ul>
            </div>
            
            <div class="md:col-span-2 p-6 bg-gray-50 dark:bg-gray-800 rounded-lg shadow-md">
                <h4 class="text-lg font-semibold mb-3 border-b pb-2 border-gray-200 dark:border-gray-700 text-indigo-600 dark:text-indigo-400">
                    Justificatifs du Projet
                </h4>
                <ul class="space-y-3 text-gray-700 dark:text-gray-300">
                    <li class="flex items-start"><i class="fas fa-clipboard-list text-emerald-500 mr-3 mt-1"></i> **Devis ou bon de commande** daté et signé (pour un achat de voiture, des travaux, etc.).</li>
                    <li class="flex items-start"><i class="fas fa-house-damage text-emerald-500 mr-3 mt-1"></i> Compromis de vente ou offre d'achat (pour un achat immobilier).</li>
                </ul>
            </div>
        </div>
    </div>
    
    <hr class="mb-8 border-indigo-200 dark:border-indigo-800">

    <div class="mb-12">
        <h3 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100 flex items-center">
            <i class="fas fa-building mr-3 text-2xl text-amber-500"></i>
            Dossier Entreprise : Financement Professionnel
        </h3>
        <p class="text-gray-600 dark:text-gray-300 mb-6 border-l-4 border-amber-500 pl-3">
            Le dossier d'entreprise est plus complexe, car il doit présenter en détail le projet, la structure juridique et la santé financière de la société.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-lg shadow-md">
                <h4 class="text-lg font-semibold mb-3 border-b pb-2 border-gray-200 dark:border-gray-700 text-amber-600 dark:text-amber-400">
                    Pièces d'Identité et Juridiques
                </h4>
                <ul class="space-y-3 text-gray-700 dark:text-gray-300">
                    <li class="flex items-start"><i class="fas fa-certificate text-amber-500 mr-3 mt-1"></i> **Extrait Kbis** de moins de trois mois (si l'entreprise est déjà créée).</li>
                    <li class="flex items-start"><i class="fas fa-book text-amber-500 mr-3 mt-1"></i> Projets de statuts de la société (pour une création).</li>
                    <li class="flex items-start"><i class="fas fa-user-tie text-amber-500 mr-3 mt-1"></i> Justificatifs d'identité et de domicile des **dirigeants et associés** principaux.</li>
                    <li class="flex items-start"><i class="fas fa-graduation-cap text-amber-500 mr-3 mt-1"></i> Curriculum Vitae (CV) du ou des porteurs de projet, mettant en avant leurs compétences.</li>
                    <li class="flex items-start"><i class="fas fa-balance-scale text-amber-500 mr-3 mt-1"></i> Justificatifs de la situation financière personnelle des dirigeants (garantie).</li>
                </ul>
            </div>

            <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-lg shadow-md">
                <h4 class="text-lg font-semibold mb-3 border-b pb-2 border-gray-200 dark:border-gray-700 text-amber-600 dark:text-amber-400">
                    Pièces Comptables et Financières
                </h4>
                <ul class="space-y-3 text-gray-700 dark:text-gray-300">
                    <li class="flex items-start font-bold"><i class="fas fa-chart-line text-amber-500 mr-3 mt-1"></i> **Business Plan** (Plan d'affaires) détaillé et structuré (élément clé).</li>
                    <li class="flex items-start"><i class="fas fa-chart-bar text-amber-500 mr-3 mt-1"></i> Les **trois derniers bilans** et comptes de résultats (pour une entreprise existante).</li>
                    <li class="flex items-start"><i class="fas fa-wallet text-amber-500 mr-3 mt-1"></i> Justificatif de l'apport personnel ou en fonds propres de l'entreprise.</li>
                    <li class="flex items-start"><i class="fas fa-file-contract text-amber-500 mr-3 mt-1"></i> Justificatif du besoin de financement (devis des machines, coût d'acquisition, etc.).</li>
                </ul>
            </div>

            <div class="md:col-span-2 p-6 bg-indigo-50 dark:bg-indigo-900 rounded-lg shadow-inner">
                <h4 class="text-lg font-bold mb-3 text-indigo-800 dark:text-indigo-200">
                    Contenu Indispensable du Business Plan
                </h4>
                <ul class="space-y-2 text-gray-700 dark:text-gray-300 list-disc ml-5">
                    <li>Présentation du projet et de l'équipe.</li>
                    <li>Étude de marché et stratégie commerciale.</li>
                    <li>Prévisions financières sur plusieurs années (compte de résultat, bilan, trésorerie).</li>
                    <li>Plan de financement initial (besoins, ressources et apport personnel).</li>
                </ul>
            </div>
        </div>

        <div class="mt-8 pt-4 border-t border-gray-200 dark:border-gray-700">
            <h4 class="text-xl font-semibold mb-3 text-gray-800 dark:text-gray-100">Cas Spécifiques</h4>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                <span class="font-bold">Reprise d'entreprise :</span> Ajouter les trois derniers bilans de la société rachetée, promesse de cession ou d'achat des titres, etc.<br>
                <span class="font-bold">Reprise de fonds de commerce :</span> Ajouter les trois derniers bilans du fonds, promesse de bail commercial ou titre de propriété.
            </p>
        </div>

    </div>
    
</div>

@endsection