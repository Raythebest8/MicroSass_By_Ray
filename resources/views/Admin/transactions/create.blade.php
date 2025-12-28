@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-3xl mx-auto px-4">
        
        <a href="{{ route('admin.transactions.index') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-gray-900 mb-6 transition-colors group">
            <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span class="text-[10px] font-black uppercase tracking-widest">Retour à l'historique</span>
        </a>

        <div class="bg-white rounded-[3rem] shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
            <div class="bg-gray-900 p-10 text-white">
                <h2 class="text-2xl font-black tracking-tight">Nouvelle Opération</h2>
                <p class="text-gray-400 text-sm font-medium italic">Enregistrez un mouvement de fonds sécurisé.</p>
            </div>

            <form action="{{ route('admin.transactions.store') }}" method="POST" class="p-10 space-y-8">
                @csrf

                <div class="space-y-3">
                    <label class="text-[10px] font-black text-gray-400 uppercase ml-2 tracking-widest italic">Type de transaction</label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="cursor-pointer group">
                            <input type="radio" name="type" value="depot" class="hidden peer" checked onchange="toggleReceiver(this.value)">
                            <div class="p-4 rounded-2xl border-2 border-gray-100 peer-checked:border-blue-500 peer-checked:bg-blue-50 text-center transition-all group-hover:bg-gray-50">
                                <span class="block text-xs font-black uppercase text-gray-400 peer-checked:text-blue-600">Dépôt</span>
                            </div>
                        </label>
                        <label class="cursor-pointer group">
                            <input type="radio" name="type" value="retrait" class="hidden peer" onchange="toggleReceiver(this.value)">
                            <div class="p-4 rounded-2xl border-2 border-gray-100 peer-checked:border-green-500 peer-checked:bg-green-50 text-center transition-all group-hover:bg-gray-50">
                                <span class="block text-xs font-black uppercase text-gray-400 peer-checked:text-green-600">Retrait</span>
                            </div>
                        </label>
                        <label class="cursor-pointer group">
                            <input type="radio" name="type" value="transfert" class="hidden peer" onchange="toggleReceiver(this.value)">
                            <div class="p-4 rounded-2xl border-2 border-gray-100 peer-checked:border-purple-500 peer-checked:bg-purple-50 text-center transition-all group-hover:bg-gray-50">
                                <span class="block text-xs font-black uppercase text-gray-400 peer-checked:text-purple-600">Transfert</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase ml-2 tracking-widest italic">Client concerné</label>
                    <select name="user_id" required class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:bg-white focus:border-orange-500 focus:ring-0 transition-all font-bold text-gray-900">
                        <option value="">Sélectionnez un client...</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->prenom }} {{ $user->nom }} (Solde: {{ number_format($user->solde, 0, ',', ' ') }} FCFA)</option>
                        @endforeach
                    </select>
                </div>

                <div id="receiver_block" class="space-y-2 hidden animate-in slide-in-from-top-4 duration-300">
                    <label class="text-[10px] font-black text-purple-400 uppercase ml-2 tracking-widest italic font-black">Bénéficiaire du transfert</label>
                    <select name="receiver_id" class="w-full px-6 py-4 rounded-2xl bg-purple-50 border-2 border-purple-100 focus:bg-white focus:border-purple-500 focus:ring-0 transition-all font-bold text-gray-900">
                        <option value="">Sélectionnez le destinataire...</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->prenom }} {{ $user->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase ml-2 tracking-widest italic">Montant à traiter</label>
                    <div class="relative">
                        <input type="number" name="montant" placeholder="Ex: 50000" required class="w-full px-6 py-5 rounded-2xl bg-gray-50 border-2 border-transparent focus:bg-white focus:border-orange-500 transition-all font-black text-3xl text-gray-900">
                        <span class="absolute right-6 top-1/2 -translate-y-1/2 font-black text-gray-300 text-xl tracking-tighter uppercase">FCFA</span>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase ml-2 tracking-widest italic">Note / Motif (Optionnel)</label>
                    <input type="text" name="libelle" placeholder="Ex: Paiement facture, Dépôt hebdomadaire..." class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:bg-white focus:border-orange-500 transition-all font-medium">
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full bg-gray-900 text-white py-6 rounded-3xl font-black text-sm uppercase tracking-[0.3em] hover:bg-orange-600 transition-all shadow-2xl shadow-gray-200">
                        Confirmer l'opération
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleReceiver(type) {
        const receiverBlock = document.getElementById('receiver_block');
        if (type === 'transfert') {
            receiverBlock.classList.remove('hidden');
        } else {
            receiverBlock.classList.add('hidden');
        }
    }
</script>
@endsection