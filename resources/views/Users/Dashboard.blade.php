@extends('layouts.users')
@section('content')
          
<div class="px-4 md:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border-l-4 border-indigo-500 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase dark:text-gray-400">Total Emprunté</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1 dark:text-white" id="stat-total">150 000 Fcfa</p>
                    </div>
                    <div class="bg-indigo-50 dark:bg-indigo-900 p-3 rounded-full text-indigo-600 dark:text-indigo-400 text-xl">💰</div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border-l-4 border-emerald-500 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase dark:text-gray-400">Remboursé</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1 dark:text-white" id="stat-paid">28 450 Fcfa</p>
                    </div>
                    <div class="bg-emerald-50 dark:bg-emerald-900 p-3 rounded-full text-emerald-600 dark:text-emerald-400 text-xl">✅</div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border-l-4 border-amber-500 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase dark:text-gray-400">Prochaine Échéance</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1 dark:text-white">15 Oct</p>
                        <p class="text-xs text-amber-600 font-medium dark:text-amber-400">8320 Fcfa </p>
                    </div>
                    <div class="bg-amber-50 dark:bg-amber-900 p-3 rounded-full text-amber-600 dark:text-amber-400 text-xl">📅</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">Progression Globale</h3>
                        <button class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium" onclick="router('loans')">Détails →</button>
                    </div>
                    <div class="chart-container">
                        <canvas id="globalProgressChart"></canvas>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center mt-4">Proportion du capital remboursé vs restant dû.</p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm flex flex-col">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Dernières Activités</h3>
                    <div class="flex-1 overflow-y-auto custom-scroll pr-2 max-h-[300px]">
                        <ul class="space-y-4">
                            <li class="flex items-start pb-4 border-b border-gray-100 dark:border-gray-700 last:border-0">
                                <div class="bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400 rounded-full h-8 w-8 flex items-center justify-center mr-3 mt-1 text-xs">✓</div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white">Paiement reçu</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Prêt Immobilier - 15 Septembre</p>
                                    <p class="text-sm font-bold text-green-600 dark:text-green-400 mt-1">- 450 000 Fcfa</p>
                                </div>
                            </li>
                            <li class="flex items-start pb-4 border-b border-gray-100 dark:border-gray-700 last:border-0">
                                <div class="bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 rounded-full h-8 w-8 flex items-center justify-center mr-3 mt-1 text-xs">i</div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white">Nouveau document disponible</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Relevé annuel 2024</p>
                                </div>
                            </li>
                            <li class="flex items-start pb-4 border-b border-gray-100 dark:border-gray-700 last:border-0">
                                <div class="bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-400 rounded-full h-8 w-8 flex items-center justify-center mr-3 mt-1 text-xs">!</div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white">Rappel d'échéance</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Prêt Personnel - Échéance dans 5 jours</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
        </div>
        </div>

        <div id="view-loans" class="fade-in hidden px-4 md:px-8">
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 dark:text-white">Mes Services</h2>
                    <p class="text-gray-500 mt-1 dark:text-gray-400">Gérez vos prêts actifs et consultez les détails.</p>
                </div>
                <div class="flex gap-2">
                    <select id="loan-filter" onchange="filterLoans()" class="bg-white dark:bg-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 text-gray-700 py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="all">Tous les statuts</option>
                        <option value="active">Actifs</option>
                        <option value="completed">Terminés</option>
                    </select>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden mb-8">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                <th class="p-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nom du Service</th>
                                <th class="p-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Montant Total</th>
                                <th class="p-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Restant</th>
                                <th class="p-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Statut</th>
                                <th class="p-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody id="loan-table-body" class="text-sm text-gray-700 dark:text-gray-300">
                            </tbody>
                    </table>
                </div>
            </div>

            <div id="loan-details-panel" class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm hidden border border-gray-100 dark:border-gray-700">
                <div class="flex justify-between items-start mb-6 border-b border-gray-100 dark:border-gray-700 pb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white" id="detail-title">Prêt Immobilier</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-sm" id="detail-id">#LOAN-001</p>
                    </div>
                    <button onclick="closeDetails()" class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300">✕ Fermer</button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h4 class="font-semibold text-gray-700 dark:text-gray-200 mb-4">Répartition du Remboursement</h4>
                        <div class="chart-container" style="height: 250px;">
                            <canvas id="loanDetailChart"></canvas>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Taux d'intérêt</p>
                            <p class="text-lg font-bold text-indigo-600 dark:text-indigo-400" id="detail-rate">3.5%</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Mensualité</p>
                            <p class="text-lg font-bold text-indigo-600 dark:text-indigo-400" id="detail-monthly">450.00 €</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Prochaine Échéance</p>
                            <p class="text-lg font-bold text-amber-600 dark:text-amber-400" id="detail-next">15 Oct 2025</p>
                        </div>
                        <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-lg shadow-md transition-colors mt-4">
                            Effectuer un Paiement
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div id="view-analytics" class="fade-in hidden px-4 md:px-8">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white">Analytique Financière</h2>
                <p class="text-gray-500 mt-1 dark:text-gray-400">Évolution de votre santé financière et score de crédit.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Évolution du Score de Crédit</h3>
                    <div class="chart-container">
                        <canvas id="creditScoreChart"></canvas>
                    </div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-2 text-center">Score mis à jour mensuellement.</p>
                </div>
                
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Dépenses Mensuelles (Prêts)</h3>
                    <div class="chart-container">
                        <canvas id="monthlyExpensesChart"></canvas>
                    </div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-2 text-center">Historique des 6 derniers mois.</p>
                </div>
            </div>

            <div class="bg-indigo-900 text-white p-8 rounded-xl shadow-lg relative overflow-hidden">
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-indigo-700 rounded-full opacity-50"></div>
                <div class="relative z-10">
                    <h3 class="text-xl font-bold mb-2">Conseil IA MicroSaaS</h3>
                    <p class="text-indigo-100 max-w-2xl">
                        Basé sur votre rythme de remboursement actuel, vous pourriez économiser <span class="font-bold text-white">450 €</span> d'intérêts en augmentant votre mensualité de 50 € sur le "Prêt Personnel".
                    </p>
                    <button class="mt-4 bg-white text-indigo-900 px-4 py-2 rounded-lg text-sm font-bold hover:bg-indigo-50 transition">
                        Voir la simulation
                    </button>
                </div>
            </div>
        </div>

        <div id="view-profile" class="fade-in hidden px-4 md:px-8">
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-800 dark:text-white">Mon Profil</h2>
                    <p class="text-gray-500 mt-1 dark:text-gray-400">Gérez vos informations personnelles.</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden max-w-2xl mx-auto border border-gray-100 dark:border-gray-700">
                    <div class="bg-indigo-50 dark:bg-indigo-900 p-6 border-b border-indigo-100 dark:border-indigo-700 flex items-center gap-4">
                        <div class="h-16 w-16 bg-indigo-200 dark:bg-indigo-700 rounded-full flex items-center justify-center text-2xl font-bold text-indigo-700 dark:text-white">RK</div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white">Raymond Kokodako</h3>
                            <p class="text-indigo-600 dark:text-indigo-400 text-sm font-medium">Compte Utilisateur Standard</p>
                        </div>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Nom</label>
                                <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg text-gray-800 dark:text-white border border-gray-200 dark:border-gray-600">Kokodako</div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Prénom</label>
                                <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg text-gray-800 dark:text-white border border-gray-200 dark:border-gray-600">Raymond</div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Email</label>
                                <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg text-gray-800 dark:text-white border border-gray-200 dark:border-gray-600 flex justify-between items-center">
                                    <span>kokodakoraymond@gmail.com</span>
                                    <span class="text-green-500 text-xs font-bold bg-green-50 dark:bg-green-900 dark:text-green-400 px-2 py-1 rounded">Vérifié</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-100 dark:border-gray-700 pt-6">
                            <h4 class="font-bold text-gray-800 dark:text-white mb-4">Sécurité</h4>
                            <button class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 text-sm font-medium flex items-center">
                                <span class="mr-2">🔒</span> Changer le mot de passe
                            </button>
                        </div>

                        <div class="flex justify-end gap-3 pt-4">
                            <button class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-600 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm">Annuler</button>
                            <button class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-bold shadow-md">Sauvegarder</button>
                        </div>
             </div> 
</div>


<!-- <section id="partenaires" class="py-16 md:py-24 bg-white dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        
        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white sm:text-4xl mb-4">
            Nos institutions partenaires
        </h2>
        <p class="text-xl text-gray-600 dark:text-gray-400 mb-12">
            Comparez et choisissez la meilleure offre pour votre projet
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-6 flex flex-col">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 dark:bg-purple-900/50 rounded-lg mr-4">
                            <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-2M5 21H3m2 0h2m4 0V9m7 12V9m0 3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-2a1 1 0 011-1h3a1 1 0 011 1v2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Banque Nationale</h3>
                            <p class="text-sm text-yellow-500 dark:text-yellow-400">4.8 /5</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                        Offre spéciale
                    </span>
                </div>
                
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                    De 1 000 € à 50 000 €
                </p>

                <div class="flex justify-between items-center mt-auto mb-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                    
                    <div class="flex items-center space-x-2">
                        <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                        </svg>
                        <span class="text-lg font-bold text-green-600 dark:text-green-400">2.9%</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Taux dès</span>
                    </div>

                    <div class="flex items-center space-x-2">
                        <svg class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-lg font-bold text-gray-800 dark:text-white">24h</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Réponse</span>
                    </div>
                </div>

                <a href="#" class="w-full text-center py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-300">
                    Demander un prêt
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-6 flex flex-col">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/50 rounded-lg mr-4">
                            <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Crédit Plus</h3>
                            <p class="text-sm text-yellow-500 dark:text-yellow-400">4.6 /5</p>
                        </div>
                    </div>
                    </div>
                
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                    De 2 000 € à 40 000 €
                </p>

                <div class="flex justify-between items-center mt-auto mb-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                    
                    <div class="flex items-center space-x-2">
                        <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                        </svg>
                        <span class="text-lg font-bold text-green-600 dark:text-green-400">3.2%</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Taux dès</span>
                    </div>

                    <div class="flex items-center space-x-2">
                        <svg class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-lg font-bold text-gray-800 dark:text-white">48h</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Réponse</span>
                    </div>
                </div>

                <a href="#" class="w-full text-center py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-300">
                    Demander un prêt
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-6 flex flex-col">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 dark:bg-yellow-900/50 rounded-lg mr-4">
                            <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Finance Facile</h3>
                            <p class="text-sm text-yellow-500 dark:text-yellow-400">4.5 /5</p>
                        </div>
                    </div>
                    </div>
                
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                    De 1 500 € à 35 000 €
                </p>

                <div class="flex justify-between items-center mt-auto mb-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                    
                    <div class="flex items-center space-x-2">
                        <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                        </svg>
                        <span class="text-lg font-bold text-green-600 dark:text-green-400">3.5%</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Taux dès</span>
                    </div>

                    <div class="flex items-center space-x-2">
                        <svg class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-lg font-bold text-gray-800 dark:text-white">24h</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Réponse</span>
                    </div>
                </div>

                <a href="#" class="w-full text-center py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-300">
                    Demander un prêt
                </a>
            </div>
            
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-6 flex flex-col">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center">
                        <div class="p-3 bg-red-100 dark:bg-red-900/50 rounded-lg mr-4">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Prêt Express</h3>
                            <p class="text-sm text-yellow-500 dark:text-yellow-400">4.7 /5</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                        Réponse rapide
                    </span>
                </div>
                
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                    De 1 000 € à 30 000 €
                </p>

                <div class="flex justify-between items-center mt-auto mb-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                    
                    <div class="flex items-center space-x-2">
                        <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                        </svg>
                        <span class="text-lg font-bold text-green-600 dark:text-green-400">3.8%</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Taux dès</span>
                    </div>

                    <div class="flex items-center space-x-2">
                        <svg class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-lg font-bold text-gray-800 dark:text-white">12h</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Réponse</span>
                    </div>
                </div>

                <a href="#" class="w-full text-center py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-300">
                    Demander un prêt
                </a>
            </div>
            
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-6 flex flex-col">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 dark:bg-yellow-900/50 rounded-lg mr-4">
                            <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Banque Solidaire</h3>
                            <p class="text-sm text-yellow-500 dark:text-yellow-400">4.4 /5</p>
                        </div>
                    </div>
                    </div>
                
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                    De 3 000 € à 45 000 €
                </p>

                <div class="flex justify-between items-center mt-auto mb-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                    
                    <div class="flex items-center space-x-2">
                        <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                        </svg>
                        <span class="text-lg font-bold text-green-600 dark:text-green-400">3.1%</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Taux dès</span>
                    </div>

                    <div class="flex items-center space-x-2">
                        <svg class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-lg font-bold text-gray-800 dark:text-white">48h</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Réponse</span>
                    </div>
                </div>

                <a href="#" class="w-full text-center py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-300">
                    Demander un prêt
                </a>
            </div>
            
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-6 flex flex-col">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center">
                        <div class="p-3 bg-pink-100 dark:bg-pink-900/50 rounded-lg mr-4">
                            <svg class="h-6 w-6 text-pink-600 dark:text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Crédit Liberté</h3>
                            <p class="text-sm text-yellow-500 dark:text-yellow-400">4.6 /5</p>
                        </div>
                    </div>
                    </div>
                
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                    De 2 000 € à 40 000 €
                </p>

                <div class="flex justify-between items-center mt-auto mb-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                    
                    <div class="flex items-center space-x-2">
                        <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                        </svg>
                        <span class="text-lg font-bold text-green-600 dark:text-green-400">3.3%</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Taux dès</span>
                    </div>

                    <div class="flex items-center space-x-2">
                        <svg class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-lg font-bold text-gray-800 dark:text-white">36h</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Réponse</span>
                    </div>
                </div>

                <a href="#" class="w-full text-center py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-300">
                    Demander un prêt
                </a>
            </div>
            
        </div>
    </div>
</section> -->

@endsection