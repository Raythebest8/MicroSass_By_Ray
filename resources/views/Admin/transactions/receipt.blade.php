@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-xl mx-auto px-4">
        
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('admin.transactions.index') }}" class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-gray-900 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Retour
            </a>
            <button onclick="window.print()" class="bg-white p-3 rounded-xl shadow-sm border border-gray-100 hover:bg-orange-50 hover:text-orange-600 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            </button>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-gray-200/50 border border-gray-100 overflow-hidden relative" id="printable-receipt">
            
            @php
                $colors = [
                    'depot' => 'bg-blue-600',
                    'retrait' => 'bg-green-600',
                    'transfert' => 'bg-purple-600',
                ];
                $color = $colors[$transaction->type] ?? 'bg-gray-900';
            @endphp
            <div class="h-2 w-full {{ $color }}"></div>

            <div class="p-10">
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gray-50 mb-4 shadow-inner">
                        <span class="text-2xl font-black text-gray-900">G.</span>
                    </div>
                    <h2 class="text-sm font-black uppercase tracking-[0.3em] text-gray-900">Reçu de Transaction</h2>
                    <p class="text-[10px] font-bold text-gray-400 mt-1 uppercase tracking-tighter italic">ID Transaction: {{ $transaction->reference }}</p>
                </div>

                <div class="text-center py-8 border-y border-dashed border-gray-200 mb-8">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Montant Total</p>
                    <h1 class="text-4xl font-black text-gray-900 tracking-tighter">
                        {{ number_format($transaction->montant, 0, ',', ' ') }} <span class="text-lg font-bold">FCFA</span>
                    </h1>
                </div>

                <div class="space-y-6">
                    <div class="flex justify-between items-start">
                        <span class="text-[10px] font-black text-gray-400 uppercase italic">Type</span>
                        <span class="text-xs font-black uppercase text-gray-900">{{ $transaction->type }}</span>
                    </div>

                    <div class="flex justify-between items-start">
                        <span class="text-[10px] font-black text-gray-400 uppercase italic">Client</span>
                        <div class="text-right">
                            <p class="text-xs font-black text-gray-900">{{ $transaction->user->prenom }} {{ $transaction->user->nom }}</p>
                            <p class="text-[9px] text-gray-400">ID: #{{ $transaction->user_id }}</p>
                        </div>
                    </div>

                    @if($transaction->type === 'transfert')
                    <div class="flex justify-between items-start">
                        <span class="text-[10px] font-black text-purple-400 uppercase italic">Bénéficiaire</span>
                        <div class="text-right">
                            <p class="text-xs font-black text-purple-600">{{ $transaction->receiver->prenom }} {{ $transaction->receiver->nom }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="flex justify-between items-start">
                        <span class="text-[10px] font-black text-gray-400 uppercase italic">Date & Heure</span>
                        <span class="text-xs font-bold text-gray-900">{{ $transaction->created_at->format('d/m/Y à H:i') }}</span>
                    </div>

                    <div class="flex justify-between items-start">
                        <span class="text-[10px] font-black text-gray-400 uppercase italic">Libellé</span>
                        <span class="text-xs font-medium text-gray-600 max-w-[200px] text-right italic">{{ $transaction->libelle ?? 'Opération standard' }}</span>
                    </div>
                </div>

                <div class="mt-12 text-center">
                    <div class="inline-block p-4 bg-gray-50 rounded-2xl mb-4">
                        {{-- Un faux QR Code ou Code barre pour le style --}}
                        <svg class="w-12 h-12 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M3 3h4v4H3V3zm0 7h4v4H3v-4zm0 7h4v4H3v-4zm7-14h4v4h-4V3zm0 7h4v4h-4v-4zm0 7h4v4h-4v-4zm7-14h4v4h-4V3zm0 7h4v4h-4v-4zm0 7h4v4h-4v-4zM5 5h2v2H5V5zm0 7h2v2H5v-2zm0 7h2v2H5v-2zm7-14h2v2h-2V5zm0 7h2v2h-2v-2zm0 7h2v2h-2v-2zm7-14h2v2h-2V5zm0 7h2v2h-2v-2zm0 7h2v2h-2v-2z"/></svg>
                    </div>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-[0.2em]">Merci pour votre confiance</p>
                </div>
            </div>

            <div class="absolute -left-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-gray-50 rounded-full border border-gray-100"></div>
            <div class="absolute -right-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-gray-50 rounded-full border border-gray-100"></div>
        </div>
    </div>
</div>

<style>
    @media print {
        body * { visibility: hidden; background: white; }
        #printable-receipt, #printable-receipt * { visibility: visible; }
        #printable-receipt { position: absolute; left: 0; top: 0; width: 100%; border: none; shadow: none; }
    }
</style>
@endsection