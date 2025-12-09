@extends('layouts.users')

@section('content')

{{-- BLOC DE PROTECTION DE LA VARIABLE $DEMANDES --}}
@php
    if (!isset($demandes)) {
        // Définit $demandes comme une collection vide si elle n'a pas été passée par le contrôleur
        $demandes = new \Illuminate\Support\Collection(); 
    }

    // Fonction d'aide pour formatter le statut et la classe CSS
    function getStatusInfo($statut) {
        $statusText = ucfirst($statut);
        $statusClass = [
            'en attente' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            'validée' => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
            'rejetée' => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
            'remboursé' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
        ][$statut] ?? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300';
        return compact('statusText', 'statusClass');
    }

    // SIMULATION : Définir le montant restant à payer (Ce champ doit venir de la DB)
    // Pour l'exemple, nous allons créer un montant dû fictif si le statut est validé.
    function getMontantDu($demande) {
        if ($demande->statut == 'validée') {
            // Remplacez cette logique par le calcul ou la lecture du champ réel dans votre DB
            // Exemple : Montant dû = Montant Souhaité * 1.15 (Intérêts) - Montant déjà payé
            return round($demande->montant_souhaite * 1.15, -3); // Exemple fictif
        }
        return null;
    }
@endphp

<div class="max-w-6xl mx-auto my-8 p-6">

    <h3 class="text-3xl font-bold mb-6 text-gray-800 dark:text-white">Historique de mes Demandes de Prêt</h3>

    {{-- Bloc de filtrage --}}
    <div class="flex justify-between items-center mb-6 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
        <label for="loan-filter" class="text-sm font-medium text-gray-700 dark:text-gray-300">Filtrer par statut:</label>
        <select id="loan-filter" class="p-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg text-sm">
            <option value="all">Tous</option>
            <option value="en attente">En Attente</option>
            <option value="validee">Actif</option>
            <option value="rejetee">Rejetée</option>
            <option value="rembourse">Remboursé (Terminé)</option>
        </select>
    </div>

    @if($demandes->isEmpty())
        <div class="p-8 text-center bg-gray-50 dark:bg-gray-700 rounded-lg shadow-md">
            <p class="text-lg text-gray-600 dark:text-gray-300">
                Aucune demande de prêt trouvée. Soumettez une nouvelle demande.
            </p>
            <a href="{{ route('users.demande.index') }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                Faire une nouvelle demande
            </a>
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 p-0 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MOTIF / TYPE</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MONTANT SOLLICITÉ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MONTANT DÛ</th> {{-- NOUVELLE COLONNE --}}
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DATE DE SOUMISSION</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STATUT</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ACTIONS</th>
                    </tr>
                </thead>
                <tbody id="loan-table-body" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    
                    @foreach ($demandes as $demande)
                        @php
                            $statusInfo = getStatusInfo($demande->statut);
                            $montantDu = getMontantDu($demande); // Calcul ou lecture du montant dû
                        @endphp

                        <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" data-status="{{ $demande->statut }}">
                            
                            {{-- MOTIF / TYPE DE DEMANDE --}}
                            <td class="px-6 py-4 max-w-xs truncate font-medium text-gray-800 dark:text-gray-100" title="{{ $demande->motif }}">
                                {{ \Illuminate\Support\Str::limit($demande->motif, 4) }}
                                <span class="block text-xs text-gray-500">ID: {{ $demande->id }}</span>
                            </td>
                            
                            {{-- MONTANT SOLLICITÉ --}}
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600 dark:text-gray-300 font-semibold">
                                {{ number_format($demande->montant_souhaite, 0, ',', ' ') }} FCFA
                            </td>
                            
                            {{-- MONTANT DÛ / RESTANT À PAYER --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($montantDu !== null)
                                    <span class="text-green-600 dark:text-green-400 font-bold">
                                        {{ number_format($montantDu, 0, ',', ' ') }} FCFA
                                    </span>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">—</span>
                                @endif
                            </td>
                            
                            {{-- DATE DE SOUMISSION --}}
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600 dark:text-gray-300">
                                {{ $demande->created_at->format('d/m/Y') }}
                            </td>

                            {{-- STATUT --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-xs font-bold px-2 py-1 rounded-full {{ $statusInfo['statusClass'] }}">
                                    {{ $statusInfo['statusText'] }}
                                </span>
                            </td>
                            
                            {{-- ACTIONS --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{-- route('users.demande.details', $demande->id) --}}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-semibold">Détails</a>
                                @if($demande->statut == 'validée')
                                    <a href="#" class="ml-4 text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 font-semibold">Contrat / Échéancier</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    
                </tbody>
            </table>
        </div>
        
        {{-- Ajout du script JavaScript pour le filtrage --}}
        <script>
            document.getElementById('loan-filter').addEventListener('change', function() {
                var filterValue = this.value;
                var rows = document.querySelectorAll('#loan-table-body tr');

                rows.forEach(function(row) {
                    var status = row.getAttribute('data-status');
                    var isVisible = false;

                    if (filterValue === 'all') {
                        isVisible = true;
                    } else if (filterValue === 'validee' && status === 'validée') {
                        isVisible = true;
                    } else if (filterValue === 'rembourse' && status === 'remboursé') {
                        isVisible = true;
                    } else if (filterValue === 'en attente' && status === 'en attente') {
                        isVisible = true;
                    } else if (filterValue === 'rejetee' && status === 'rejetée') {
                        isVisible = true;
                    }

                    row.style.display = isVisible ? '' : 'none';
                });
            });
        </script>
        
    @endif
</div>

@endsection