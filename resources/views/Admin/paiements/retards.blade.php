@extends('layouts.app') 

@section('content')

<h1 class="text-3xl font-bold mb-6 text-red-700"> Échéances en Retard</h1>

<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="p-6 bg-red-50 border-b border-gray-200">
        <p class="text-sm text-red-800">
            {{ $echeances->count() }} échéance(s) sont actuellement en statut 'retard'.
            Ces statuts ont été mis à jour par la tâche planifiée quotidienne.
        </p>
    </div>

    @if($echeances->isEmpty())
        <div class="p-6 text-center text-gray-500">
            Félicitations ! Aucune échéance en retard pour le moment.
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prêt ID / Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client (User ID)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date d'Échéance</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant Total</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($echeances as $echeance)
                        @php
                            // Accéder à la demande polymorphique
                            $demande = $echeance->demande;
                            $demandeType = class_basename($demande);
                        @endphp
                        <tr class="hover:bg-red-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $demandeType }} #{{ $demande->id ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $demande->user_id ?? 'N/A' }}
                                {{-- Vous pouvez charger ici le nom ou l'email de l'utilisateur si la relation User est ajoutée au modèle de demande --}}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-red-600">
                                {{ $echeance->date_prevue->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($echeance->montant_total, 0, ',', ' ') }} FCFA
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900">Contacter / Pénaliser</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection