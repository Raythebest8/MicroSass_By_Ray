@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 min-h-screen bg-gray-50">
    <div class="max-w-2xl mx-auto">
        <a href="{{ route('admin.paiements.index') }}" class="text-gray-500 hover:text-gray-700 text-sm mb-4 inline-block transition hover:-translate-x-1">
            <i class="fas fa-arrow-left mr-1"></i> Retour à la liste
        </a>

        <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
            <div class="bg-indigo-600 p-4">
                <h1 class="text-white text-lg font-semibold flex items-center">
                    <i class="fas fa-plus-circle mr-2"></i> Enregistrer un nouveau paiement
                </h1>
            </div>

            <form action="{{ route('admin.paiements.store') }}" method="POST" class="p-6 space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Utilisateur / Client</label>
                    <select name="user_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" required>
                        <option value="">Sélectionner le client...</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->nom }} {{ $user->prenom }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Montant (FCFA)</label>
                        <input type="number" name="montant" placeholder="Ex: 50000" 
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date du paiement</label>
                        <input type="datetime-local" name="date_paiement" value="{{ now()->format('Y-m-d\TH:i') }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Moyen de paiement</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <label class="relative flex flex-col items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50 transition">
                            <input type="radio" name="methode" value="Guichet" class="absolute opacity-0" checked>
                            <i class="fas fa-money-bill-wave text-green-600 mb-1"></i>
                            <span class="text-xs font-medium">Guichet</span>
                        </label>
                        
                        <label class="relative flex flex-col items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50 transition">
                            <input type="radio" name="methode" value="Flooz" class="absolute opacity-0">
                            <span class="text-orange-500 font-bold text-xs mb-1">Flooz</span>
                            <span class="text-xs font-medium text-gray-500">Moov</span>
                        </label>

                        <label class="relative flex flex-col items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50 transition">
                            <input type="radio" name="methode" value="Mix by Yas" class="absolute opacity-0">
                            <span class="text-blue-400 font-bold text-xs mb-1">Mix</span>
                            <span class="text-xs font-medium text-gray-500">Yas</span>
                        </label>

                        <label class="relative flex flex-col items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50 transition">
                            <input type="radio" name="methode" value="Visa" class="absolute opacity-0">
                            <i class="fab fa-cc-visa text-blue-800 mb-1"></i>
                            <span class="text-xs font-medium text-gray-500">Visa</span>
                        </label>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg border border-dashed border-gray-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Référence de transaction</p>
                            <p class="text-sm text-indigo-600 font-mono mt-1 italic">
                                Générée automatiquement lors de l'enregistrement
                            </p>
                        </div>
                        <i class="fas fa-magic text-indigo-300 text-xl"></i>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100">
                    <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-indigo-700 transition duration-200 shadow-md active:scale-[0.98]">
                        Confirmer l'enregistrement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection