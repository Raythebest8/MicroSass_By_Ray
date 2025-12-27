@extends('layouts.app')
@section('content')
    <div class="p-6 bg-gray-100 min-h-screen">
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <h1 class="text-2xl font-bold text-gray-800">Gestion de prêt</h1>

            <div class="flex bg-white rounded-full p-1 shadow-sm border">
                <a href="{{ route('admin.pret.index', ['filter' => 'tous']) }}"
                    class="px-6 py-2 rounded-full font-medium transition {{ ($filter ?? 'tous') === 'tous' ? 'bg-orange-500 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}">
                    Tous
                </a>

                <a href="{{ route('admin.pret.index', ['filter' => 'actifs']) }}"
                    class="px-6 py-2 rounded-full font-medium transition {{ ($filter ?? 'tous') === 'actifs' ? 'bg-orange-500 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}">
                    Actifs
                </a>

                <a href="{{ route('admin.pret.index', ['filter' => 'termine']) }}"
                    class="px-6 py-2 rounded-full font-medium transition {{ ($filter ?? 'tous') === 'termine' ? 'bg-orange-500 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}">
                    Terminé
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($demandesActives as $demande)
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 relative">
                    <div class="absolute top-6 right-6">
                        <span class="bg-green-600 text-white px-6 py-2 rounded-2xl text-sm font-semibold">
                            {{ ucfirst($demande->statut ?? 'Actif') }}
                        </span>
                    </div>

                    <div class="mb-4">
                        {{-- Ajout du petit badge de type au-dessus du nom --}}
                        <div class="mb-1">
                            <span
                                class="text-[10px] font-bold uppercase px-2 py-0.5 rounded {{ $demande->type_label === 'Entreprise' ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-purple-50 text-purple-600 border border-purple-100' }}">
                                {{ $demande->type_label }}
                            </span>
                        </div>

                        {{-- Utilisation de display_name pour gérer Particulier ET Entreprise --}}
                        <h3 class="text-lg font-bold text-gray-700">{{ $demande->display_name }}</h3>
                        <p class="text-xl font-black text-gray-900">
                            {{ number_format($demande->montant_accorde ?? $demande->montant_souhaite, 0, ',', ' ') }} Fcfa
                        </p>
                    </div>

                    <div class="mb-2">
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-green-500 h-2.5 rounded-full" style="width: {{ $demande->pourcentage }}%"></div>
                        </div>
                        <p class="text-right text-sm text-gray-500 mt-1 font-medium">{{ $demande->pourcentage }}% remboursé
                        </p>
                    </div>

                    <div class="space-y-3 mt-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 font-bold">Montant total:</span>
                            <span
                                class="font-bold text-gray-800">{{ number_format($demande->montant_accorde ?? $demande->montant_souhaite, 0, ',', ' ') }}
                                Fcfa</span>
                        </div>
                        <div class="flex justify-between text-sm text-green-600 font-bold">
                            <span>Payé:</span>
                            <span>{{ number_format($demande->paye, 0, ',', ' ') }} Fcfa</span>
                        </div>
                        <div class="flex justify-between text-sm text-red-500 font-bold">
                            <span>Restant:</span>
                            <span>{{ number_format($demande->restant, 0, ',', ' ') }} Fcfa</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 font-bold">Paiement mensuel:</span>
                            <span
                                class="text-gray-800 font-medium">{{ number_format($demande->mensualite ?? 0, 0, ',', ' ') }}
                                Fcfa</span>
                        </div>
                        <div class="flex justify-between text-sm items-center">
                            <span class="text-gray-600 font-bold">Prochain paiement:</span>

                            @if ($demande->en_retard)
                                <span class="flex items-center gap-1 text-red-600 font-black animate-pulse">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    {{ $demande->prochaine_echeance ? $demande->prochaine_echeance->format('d M. Y') : 'N/A' }}
                                </span>
                            @else
                                <span class="text-gray-800 font-medium">
                                    {{ $demande->prochaine_echeance ? $demande->prochaine_echeance->format('d M. Y') : 'N/A' }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Optionnel: Un bouton discret pour voir les détails --}}
                    <div class="mt-4 pt-4 border-t border-gray-50">
                        <a href="{{ route('admin.remboursement.details', ['id' => $demande->id, 'type' => $demande->type === 'Entreprise' ? 'entreprise' : 'particulier']) }}"
                            class="text-sm font-bold text-orange-500 hover:text-orange-600 flex items-center justify-center gap-2">
                            Voir les détails <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-2 text-center py-12 bg-white rounded-3xl shadow-sm">
                    <p class="text-gray-500">Aucun prêt trouvé pour ce filtre.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
