@extends('layouts.users')

@section('content')

<div class="max-w-7xl mx-auto my-8 p-6 bg-white dark:bg-gray-800 rounded-lg shadow-xl">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 border-b pb-4 dark:border-gray-700 gap-4">
        <h3 class="text-3xl font-bold text-gray-800 dark:text-white">
            Historique de Paiements
        </h3>

        <div class="flex flex-wrap gap-3">
            {{-- BOUTON CALENDRIER --}}
            <a href="{{ route('users.calendar') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Voir le Calendrier
            </a>

            {{-- BOUTON EFFECTUER PAIEMENT --}}
            <a href="{{ route('users.paiements.checkout') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-800 focus:ring focus:ring-green-300 disabled:opacity-25 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
                Effectuer un Paiement
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 p-0 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PRÊT LIÉ (MOTIF)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MONTANT PAYÉ</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DATE</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ÉCHÉANCE</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MÉTHODE</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ACTION</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">

                @forelse ($paiements as $paiement)
                @php
                    $echeance = $paiement->echeance;
                    $demande = $echeance->demande ?? null;
                    $motif = $demande ? $demande->motif : 'Prêt Supprimé';
                @endphp
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    {{-- PRÊT LIÉ --}}
                    <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-gray-100">
                        {{ \Illuminate\Support\Str::limit($motif, 25) }}
                    </td>

                    {{-- MONTANT --}}
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600 dark:text-green-400">
                        {{ number_format($paiement->montant, 0, ',', ' ') }} FCFA
                    </td>

                    {{-- DATE --}}
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                        {{ $paiement->date_paiement->format('d/m/Y') }}
                    </td>

                    {{-- ÉCHÉANCE --}}
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                        Mois {{ $echeance->mois_numero ?? 'N/A' }}
                    </td>

                    {{-- MÉTHODE --}}
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                        <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded text-xs uppercase">
                            {{ $paiement->methode_paiement }}
                        </span>
                    </td>

                    {{-- ACTION : TÉLÉCHARGER LE REÇU --}}
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('users.paiements.download', $paiement->id) }}" 
                           class="inline-flex items-center text-indigo-600 hover:text-indigo-900 bg-indigo-50 dark:bg-indigo-900/30 dark:text-indigo-400 p-2 rounded-lg transition">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Reçu PDF
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400 italic">
                        Aucun paiement enregistré pour le moment.
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>
    </div>
</div>

@endsection