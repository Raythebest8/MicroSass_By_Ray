@extends('layouts.users')

@section('content')
<div class="max-w-7xl mx-auto my-8 p-6 bg-white dark:bg-gray-800 rounded-lg shadow-xl">

    <h3 class="text-3xl font-bold mb-8 text-gray-800 dark:text-white border-b pb-4 dark:border-gray-700">
        Historique de Mes Demandes de Prêt
    </h3>

    @if($demandes->isEmpty())
        <div class="p-6 text-center bg-gray-50 dark:bg-gray-700 rounded-lg text-gray-700 dark:text-gray-300">
            Vous n'avez soumis aucune demande de prêt pour l'instant.
        </div>
    @else
        <div class="overflow-x-auto rounded-lg shadow-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type / N° Demande</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motif / Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant Souhaité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de Soumission</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($demandes as $demande)
                        @php
                            // Identifie si c'est Entreprise ou Particulier
                            $type = strtolower(class_basename($demande));
                            $status = $demande->statut;
                            
                            $statusClass = [
                                'validée' => 'bg-green-100 text-green-700',
                                'approuvé' => 'bg-green-100 text-green-700',
                                'en attente' => 'bg-yellow-100 text-yellow-800',
                                'en cours d\'examen' => 'bg-blue-100 text-blue-800',
                                'rejetée' => 'bg-red-100 text-red-800',
                            ][$status] ?? 'bg-gray-100 text-gray-600';
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                {{ ucfirst($type) }} (N° {{ $demande->id }})
                            </td>
                            <td class="px-6 py-4 max-w-xs truncate text-sm text-gray-600 dark:text-gray-300">
                                {{ \Illuminate\Support\Str::limit($demande->motif, 40) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                {{ number_format($demande->montant_souhaite, 0, ',', ' ') }} FCFA
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                {{ $demande->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 text-xs font-bold rounded-full {{ $statusClass }} dark:bg-opacity-20">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if(in_array($status, ['validée', 'approuvé']))
                                    <a href="{{ route('users.demande.details', ['type' => $type, 'id' => $demande->id]) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-bold">
                                        Voir Échéancier
                                    </a>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">En attente</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection