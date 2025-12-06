@extends('layouts.users')

@section('content')

<div class="max-w-6xl mx-auto my-8 p-6">

    <h3 class="text-3xl font-bold mb-6 text-gray-800 dark:text-white">Historique et Gestion des Prêts</h3>

    <div class="flex justify-between items-center mb-6 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
        <label for="loan-filter" class="text-sm font-medium text-gray-700 dark:text-gray-300">Filtrer par statut:</label>
        <select id="loan-filter" class="p-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg text-sm">
            <option value="all">Tous</option>
            <option value="active" selected>Actif</option>
            <option value="completed">Terminé</option>
        </select>
    </div>

    <div class="bg-white dark:bg-gray-800 p-0 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NOM DU PRÊT</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MONTANT TOTAL</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">RESTANT À PAYER</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STATUT</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ACTIONS</th>
                </tr>
            </thead>
            <tbody id="loan-table-body" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                
                {{-- LIGNE 1 : Prêt Immobilier (Actif) --}}
                <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800 dark:text-gray-100">Prêt Immobilier</td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600 dark:text-gray-300">120 000 €</td>
                    <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-800 dark:text-gray-100">75 000 €</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-xs font-bold px-2 py-1 rounded-full bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300">Actif</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="#" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-semibold">Voir</a>
                    </td>
                </tr>

                {{-- LIGNE 2 : Prêt Personnel (Actif) --}}
                <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800 dark:text-gray-100">Prêt Personnel</td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600 dark:text-gray-300">15 000 €</td>
                    <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-800 dark:text-gray-100">6 550 €</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-xs font-bold px-2 py-1 rounded-full bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300">Actif</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="#" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-semibold">Voir</a>
                    </td>
                </tr>

                {{-- LIGNE 3 : Crédit Auto (Terminé) --}}
                <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800 dark:text-gray-100">Crédit Auto</td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600 dark:text-gray-300">25 000 €</td>
                    <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-800 dark:text-gray-100">0 €</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-xs font-bold px-2 py-1 rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">Terminé</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="#" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-semibold">Voir</a>
                    </td>
                </tr>
                
            </tbody>
        </table>
    </div>
</div>

@endsection