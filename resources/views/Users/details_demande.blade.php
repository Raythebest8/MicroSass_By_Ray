@extends('layouts.users')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[80vh] px-4 py-12">
    
    <div class="w-full max-w-md">
        
        <div class="mb-4">
            <a href="{{ route('users.pretactif') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-blue-600 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Retour à mes prêts
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100">
            
            <div class="h-2 bg-blue-600"></div>

            <div class="px-8 pt-8 pb-4 text-center">
                <h2 class="text-2xl font-bold text-gray-800">Détails du Dossier</h2>
                <p class="text-xs font-semibold text-gray-400 mt-1 uppercase tracking-widest">Référence {{ $demande->id }}</p>
            </div>

            <div class="px-8 pb-8">
                <div class="flex justify-center mb-6">
                    @php $status = strtolower($demande->statut); @endphp
                    @if($status == 'validée')
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-green-50 text-green-700 border border-green-200">
                            <span class="w-2 h-2 mr-2 bg-green-500 rounded-full"></span> Approuvée
                        </span>
                    @elseif($status == 'rejetée')
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-red-50 text-red-700 border border-red-200">
                            <span class="w-2 h-2 mr-2 bg-red-500 rounded-full"></span> Rejetée
                        </span>
                    @else
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-amber-50 text-amber-700 border border-amber-200">
                            <span class="w-2 h-2 mr-2 bg-amber-500 rounded-full animate-pulse"></span> Étude en cours
                        </span>
                    @endif
                </div>

                <div class="bg-gray-50 rounded-2xl p-6 text-center mb-6 border border-gray-100">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wide">Montant Souhaité</span>
                    <div class="text-3xl font-black text-blue-600 mt-1">
                        {{ number_format($demande->montant_souhaite, 0, ',', ' ') }} <span class="text-sm font-normal text-gray-500">FCFA</span>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500 font-medium">Type de prêt</span>
                        <span class="text-gray-900 font-bold">{{ ucfirst($type) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500 font-medium">Date de dépôt</span>
                        <span class="text-gray-900 font-bold">{{ $demande->created_at->format('d/m/Y') }}</span>
                    </div>

                    @if($status == 'validée')
                        @php
                            $montant = $demande->montant_souhaite;
                            $taux = $demande->taux_interet;
                            $total = $montant + ($montant * ($taux / 100));
                        @endphp
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 font-medium">Durée</span>
                            <span class="text-gray-900 font-bold">{{ $demande->duree_mois }} mois</span>
                        </div>
                        <div class="flex justify-between items-center text-sm border-b border-gray-100 pb-4">
                            <span class="text-gray-500 font-medium">Taux</span>
                            <span class="text-blue-600 font-bold">{{ $taux }} %</span>
                        </div>

                        <div class="flex justify-between items-center pt-2">
                            <span class="text-base font-bold text-gray-800">Total à payer</span>
                            <span class="text-xl font-black text-gray-900">{{ number_format($total, 0, ',', ' ') }} <small class="text-xs font-normal">FCFA</small></span>
                        </div>

                        <a href="{{ route('users.demande.download', ['type' => $type, 'id' => $demande->id]) }}" 
                           class="mt-6 w-full flex items-center justify-center px-6 py-3.5 border border-transparent text-base font-bold rounded-xl text-white bg-blue-600 hover:bg-blue-700 shadow-lg hover:shadow-blue-200 transition-all duration-300">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"></path></svg>
                            Télécharger l'échéancier
                        </a>
                    @elseif($status == 'rejetée')
                        <div class="mt-4 p-4 bg-red-50 rounded-xl border border-red-100">
                            <p class="text-xs font-bold text-red-600 uppercase mb-1">Motif du rejet</p>
                            <p class="text-sm text-red-800 leading-relaxed">{{ $demande->raison_rejet ?? 'Dossier non éligible.' }}</p>
                        </div>
                    @else
                        <div class="mt-4 flex flex-col items-center justify-center p-6 border-2 border-dashed border-gray-100 rounded-2xl">
                            <div class="w-8 h-8 border-4 border-amber-400 border-t-transparent rounded-full animate-spin"></div>
                            <p class="text-sm text-gray-500 mt-3 font-medium text-center">Traitement en cours par nos agents...</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-gray-50 px-8 py-4 flex justify-center">
                <!-- <svg class="w-8 h-8 text-gray-200" fill="currentColor" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 6c1.4 0 2.5 1.1 2.5 2.5S13.4 12 12 12s-2.5-1.1-2.5-2.5S10.6 7 12 7zm0 10c-2.1 0-4-1.1-5-2.7.03-1.7 3.3-2.6 5-2.6s4.97.9 5 2.6c-1 1.6-2.9 2.7-5 2.7z"/></svg> -->
            </div>
        </div>
    </div>
</div>
@endsection