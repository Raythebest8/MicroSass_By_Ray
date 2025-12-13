@extends('layouts.users')

@section('content')

<div class="max-w-7xl mx-auto my-8 p-6 bg-white dark:bg-gray-800 rounded-lg shadow-xl">

    <div class="flex justify-between items-center mb-8 border-b pb-4 dark:border-gray-700">
        <h3 class="text-3xl font-bold text-gray-800 dark:text-white">
            Historique de Paiements
        </h3> 
        
        {{-- NOUVEAU BOUTON DE PAIEMENT --}}
        <a href="#" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-800 focus:ring focus:ring-green-300 disabled:opacity-25 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
            Effectuer un Paiement
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 p-0 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PRÊT LIÉ (MOTIF)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MONTANT PAYÉ</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DATE DE PAIEMENT</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ÉCHÉANCE COUVERTE</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MÉTHODE</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">RÉFÉRENCE</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                
                @forelse ($paiements as $paiement)
                    @php
                        $echeance = $paiement->echeance;
                        $demande = $echeance->demande ?? null;
                        $motif = $demande ? $demande->motif : 'Prêt Supprimé';
                        $typeDemande = $demande ? class_basename($demande) : 'N/A';
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        {{-- PRÊT LIÉ (MOTIF) --}}
                        <td class="px-6 py-4 max-w-xs truncate text-sm font-medium text-gray-800 dark:text-gray-100" title="{{ $motif }}">
                            {{ \Illuminate\Support\Str::limit($motif, 30) }} 
                            <span class="block text-xs text-gray-500 dark:text-gray-400">({{ $typeDemande }} ID: {{ $demande->id ?? '?' }})</span>
                        </td>
                        
                        {{-- MONTANT PAYÉ --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600 dark:text-green-400">
                            {{ number_format($paiement->montant, 0, ',', ' ') }} FCFA
                        </td>
                        
                        {{-- DATE DE PAIEMENT --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                            {{ $paiement->date_paiement->format('d/m/Y H:i') }}
                        </td>

                        {{-- ÉCHÉANCE COUVERTE --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                            @if($echeance)
                                Mois N° **{{ $echeance->mois_numero }}** (Prévu le {{ $echeance->date_prevue->format('d/m/Y') }})
                            @else
                                N/A
                            @endif
                        </td>

                        {{-- MÉTHODE --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                            {{ $paiement->methode_paiement }}
                        </td>
                        
                        {{-- RÉFÉRENCE --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $paiement->reference_transaction }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            Aucune transaction de paiement trouvée.
                        </td>
                    </tr>
                @endforelse
                
            </tbody>
        </table>
    </div>
</div>

@endsection