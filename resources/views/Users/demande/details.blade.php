@extends('layouts.users')

@section('content')

<div class="max-w-7xl mx-auto my-8 p-6 bg-white dark:bg-gray-800 rounded-lg shadow-xl">

    <h3 class="text-3xl font-bold mb-6 text-gray-800 dark:text-white">
        Détails du Prêt ({{ ucfirst($type) }})
    </h3>

    {{-- Bloc d'Information du Prêt --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 p-4 border-b dark:border-gray-700">
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Motif</p>
            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $demande->motif }}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Montant Total</p>
            <p class="text-lg font-semibold text-indigo-600 dark:text-indigo-400">{{ number_format($demande->montant_souhaite, 0, ',', ' ') }} FCFA</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Statut Actuel</p>
            @php
                $statusClass = ['validée' => 'bg-green-100 text-green-700', 'en attente' => 'bg-yellow-100 text-yellow-800'][$demande->statut] ?? 'bg-gray-100 text-gray-600';
            @endphp
            <span class="text-lg font-bold px-3 py-1 rounded-full {{ $statusClass }} dark:bg-opacity-20">{{ ucfirst($demande->statut) }}</span>
        </div>
    </div>
    
    {{-- Affichage du Montant Dû et Formulaire de Paiement --}}
    @if($demande->statut == 'validée')
        <h4 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">Montant Restant Dû : 
            <span class="text-red-600 dark:text-red-400">{{ number_format($montantRestantDu, 0, ',', ' ') }} FCFA</span>
        </h4>

        <div class="p-6 bg-gray-50 dark:bg-gray-700 rounded-lg mb-8 shadow-inner">
            <h5 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white">Effectuer un Paiement</h5>
            <form action="{{-- route('users.paiement.store') --}}" method="POST">
                @csrf
                {{-- Champ caché pour l'ID de la demande --}}
                <input type="hidden" name="demande_id" value="{{ $demande->id }}">
                <input type="hidden" name="type" value="{{ $type }}">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="montant_paiement" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Montant du Paiement (minimum : {{ number_format($demande->echeances->where('statut', 'à payer')->first()->montant_total ?? 0, 0, ',', ' ') }} FCFA)</label>
                        <input type="number" name="montant_paiement" id="montant_paiement" required min="{{ $demande->echeances->where('statut', 'à payer')->first()->montant_total ?? 0 }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label for="methode_paiement" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Méthode de Paiement</label>
                        <select name="methode_paiement" id="methode_paiement" required 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                            <option value="mobile_money">Mobile Money (Orange/Moov)</option>
                            <option value="virement">Virement Bancaire</option>
                        </select>
                    </div>
                </div>
                
                <button type="submit" class="mt-4 px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600">
                    Payer Maintenant
                </button>
            </form>
        </div>
    @endif

    {{-- Tableau de l'Échéancier --}}
    <h4 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">Échéancier de Remboursement</h4>

    @if($demande->echeances->isEmpty())
        <div class="p-4 bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg text-yellow-800 dark:text-yellow-300">
            Le tableau d'amortissement n'a pas encore été généré (le statut du prêt n'est peut-être pas encore 'validée').
        </div>
    @else
        <div class="overflow-x-auto rounded-lg shadow-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mois</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Prévue</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Principal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Intérêts</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($demande->echeances as $echeance)
                        @php
                            $echeanceStatus = [
                                'payée' => 'text-green-600 font-semibold',
                                'retard' => 'text-red-600 font-bold',
                                'à payer' => 'text-yellow-600',
                            ][$echeance->statut] ?? 'text-gray-600';
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $echeance->mois_numero }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">{{ $echeance->date_prevue->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">{{ number_format($echeance->montant_principal, 0, ',', ' ') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">{{ number_format($echeance->montant_interet, 0, ',', ' ') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">{{ number_format($echeance->montant_total, 0, ',', ' ') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm {{ $echeanceStatus }}">
                                {{ ucfirst($echeance->statut) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection