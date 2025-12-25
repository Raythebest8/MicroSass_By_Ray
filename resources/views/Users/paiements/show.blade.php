@extends('layouts.users')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Détails du paiement</h2>
        <a href="{{ route('users.paiements.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Retour à l'historique
        </a>
    </div>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Référence Transaction</span>
                <p class="text-lg font-mono font-bold text-gray-900">{{ $paiement->reference_transaction }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $paiement->statut === 'effectué' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                {{ strtoupper($paiement->statut) }}
            </span>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                
                <div class="space-y-6">
                    <div>
                        <h3 class="text-sm font-bold text-blue-600 uppercase mb-4 border-b pb-2">Résumé du paiement</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Montant réglé :</span>
                                <span class="text-xl font-black text-gray-900">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-red-500 font-medium italic">Dont Intérêts :</span>
                                <span class="text-red-600 font-bold">+ {{ number_format($paiement->echeance->montant_interet, 0, ',', ' ') }} FCFA</span>
                            </div>
                            <div class="flex justify-between text-sm pt-2 border-t">
                                <span class="text-gray-500">Date :</span>
                                <span class="text-gray-900">{{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="{{ $montantRestant <= 0 ? 'bg-green-50 border-green-200' : 'bg-orange-50 border-orange-200' }} p-6 rounded-xl border shadow-inner">
                    @if($montantRestant <= 0)
                        <div class="text-center py-2">
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-green-100 rounded-full mb-3">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <h3 class="text-lg font-black text-green-800 uppercase">Prêt Soldé !</h3>
                            <p class="text-sm text-green-700 font-medium">Félicitations, votre prêt est entièrement remboursé.</p>
                        </div>
                    @else
                        <h3 class="text-sm font-bold text-orange-700 uppercase mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Reste à payer
                        </h3>
                        <div class="text-3xl font-black text-orange-600 mb-4 font-mono">
                            {{ number_format($montantRestant, 0, ',', ' ') }} <small class="text-xs uppercase">FCFA</small>
                        </div>

                        @php
                            $totalDu = $paiement->echeance->demande->echeances()->sum('montant_total');
                            $pourcentage = $totalDu > 0 ? (($totalDu - $montantRestant) / $totalDu) * 100 : 0;
                        @endphp
                        
                        <div class="w-full bg-orange-200 rounded-full h-3">
                            <div class="bg-orange-600 h-3 rounded-full transition-all duration-700" style="width: {{ $pourcentage }}%"></div>
                        </div>
                        <p class="text-[10px] text-orange-700 mt-2 font-bold uppercase text-right">Progression : {{ round($pourcentage) }}%</p>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                    <h4 class="text-xs font-bold text-blue-600 uppercase mb-2">Échéance d'origine</h4>
                    <p class="text-sm">Date prévue : <span class="font-bold">{{ \Carbon\Carbon::parse($paiement->echeance->date_prevue)->format('d/m/Y') }}</span></p>
                    <p class="text-sm font-medium">Réglé via {{ $paiement->methode_paiement }}</p>
                </div>

                <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                    <h4 class="text-xs font-bold text-blue-700 uppercase mb-2">Dossier de prêt</h4>
                    <p class="text-sm">Type : <span class="font-bold">{{ class_basename($paiement->echeance->demande_type) }}</span></p>
                    <p class="text-sm">Motif : <span class="font-bold text-blue-900">{{ $paiement->echeance->demande->motif ?? '-' }}</span></p>
                </div>
            </div>

            <div class="mt-10 flex justify-center">
                <a href="{{ route('users.paiements.download', $paiement->id) }}" class="inline-flex items-center px-10 py-4 bg-blue-600 text-white font-bold rounded-full shadow-xl hover:bg-blue-700 transition duration-200 transform hover:-translate-y-1">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    TÉLÉCHARGER LE REÇU (PDF)
                </a>
            </div>
        </div>
    </div>
</div>
@endsection