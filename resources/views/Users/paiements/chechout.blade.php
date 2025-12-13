@extends('layouts.users')

@section('content')

<div class="max-w-3xl mx-auto my-8 p-6 bg-white dark:bg-gray-800 rounded-lg shadow-xl">
    
    <h3 class="text-3xl font-bold mb-6 text-gray-800 dark:text-white border-b pb-4 dark:border-gray-700 flex items-center">
        <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V9a3 3 0 00-3-3m3 3a3 3 0 003 3m-3-6a9 9 0 00-9 9 9 9 0 009 9 9 9 0 009-9 9 9 0 00-9-9z"></path></svg>
        Paiement en Ligne
    </h3> 

    <form action="{{ route('users.paiement.process') }}" method="POST">
        @csrf

        {{-- Section 1: Choix du Prêt à Payer --}}
        <div class="mb-6 p-4 border rounded-lg bg-gray-50 dark:bg-gray-700">
            <label for="demande_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sélectionnez le Prêt à Rembourser :</label>
            <select name="demande_id" id="demande_id" required 
                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                <option value="">-- Choisir un Prêt Actif --</option>
                {{-- REMPLACER PAR LA BOUCLE DES PRÊTS ACTIFS DE L'UTILISATEUR --}}
                {{-- @foreach ($demandesActives as $demande)
                    <option value="{{ $demande->id }}">{{ $demande->motif }} (Reste : {{ number_format($demande->solde_restant, 0, ',', ' ') }} FCFA)</option>
                @endforeach --}}
                <option value="1">Exemple Prêt N°1 (Reste : 500 000 FCFA)</option>
            </select>
            @error('demande_id')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        {{-- Section 2: Choix du Montant --}}
        <div class="mb-6 p-4 border rounded-lg bg-gray-50 dark:bg-gray-700">
            <label for="montant" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Montant du Paiement (FCFA) :</label>
            <input type="number" name="montant" id="montant" required min="1000" placeholder="Minimum 1 000 FCFA"
                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white @error('montant') border-red-500 @enderror"
                value="{{ old('montant') }}">
            @error('montant')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Section 3: Méthode de Paiement Mobile --}}
        <div class="mb-8 p-4 border rounded-lg bg-gray-50 dark:bg-gray-700">
            <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-3">Méthode de Paiement Mobile</h4>
            
            <div class="space-y-3">
                
                {{-- Option Mobile Money --}}
                <label class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg shadow cursor-pointer">
                    <input type="radio" name="methode" value="Mobile Money" required class="form-radio h-4 w-4 text-orange-600 border-gray-300 focus:ring-orange-500" checked>
                    <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/1a/Orange_money_logo.svg/100px-Orange_money_logo.svg.png" alt="Mobile Money" class="h-5 w-auto mr-2">
                        Orange Money / MoMo (Paiement Direct)
                    </span>
                </label>
                
                {{-- Option Carte Bancaire (si disponible) --}}
                <label class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg shadow cursor-pointer">
                    <input type="radio" name="methode" value="Carte Bancaire" class="form-radio h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                    <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        Carte Bancaire (VISA/MasterCard)
                    </span>
                </label>

                @error('methode')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <button type="submit" class="w-full px-4 py-3 bg-indigo-600 text-white font-semibold text-lg rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50 transition duration-150">
            Procéder au Paiement
        </button>
    </form>

</div>

@endsection