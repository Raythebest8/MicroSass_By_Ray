@extends('layouts.users')

@section('content')

<div class="max-w-3xl mx-auto my-8 p-6 bg-white dark:bg-gray-800 rounded-lg shadow-xl">
    
    <h3 class="text-3xl font-bold mb-6 text-gray-800 dark:text-white border-b pb-4 dark:border-gray-700 flex items-center">
        <!-- <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V9a3 3 0 00-3-3m3 3a3 3 0 003 3m-3-6a9 9 0 00-9 9 9 9 0 009 9 9 9 0 009-9 9 9 0 00-9-9z"></path>
        </svg> -->
        Paiement en Ligne
    </h3> 

    @if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <form action="{{ route('users.paiements.process') }}" method="POST" id="paymentForm">
        @csrf

        {{-- Section 1: Choix du Prêt à Payer --}}
        <div class="mb-6 p-4 border rounded-lg bg-gray-50 dark:bg-gray-700">
            <label for="demande_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sélectionnez le Prêt à Rembourser :</label>
            
            <select name="demande_id" id="demande_id" required 
                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white @if($demandesActives->isEmpty()) bg-gray-100 cursor-not-allowed @endif"
                @if($demandesActives->isEmpty()) disabled @endif>
                
                @forelse ($demandesActives as $demande)
                    <option value="{{ $demande->id }}" data-type="{{ $demande->type }}" {{ old('demande_id') == $demande->id ? 'selected' : '' }}>
                        {{ $demande->libelle }}
                    </option>
                @empty
                    <option value="">🚫 Aucun prêt actif trouvé</option>
                @endforelse
            </select>

            @if($demandesActives->isEmpty())
                <div class="mt-3 p-3 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 text-sm">
                    <p class="font-bold italic">Vous n'avez actuellement aucun prêt actif nécessitant un remboursement.</p>
                </div>
            @endif

            <input type="hidden" name="type" id="type_demande" value="{{ old('type') }}">

            @error('demande_id')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        {{-- Section 2: Choix du Montant --}}
        <div class="mb-6 p-4 border rounded-lg bg-gray-50 dark:bg-gray-700">
            <label for="montant" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Montant du Paiement (FCFA) :</label>
            <input type="number" name="montant" id="montant" required min="1000" placeholder="Minimum 1 000 FCFA"
                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white @error('montant') border-red-500 @enderror"
                value="{{ old('montant') }}"
                @if($demandesActives->isEmpty()) disabled @endif>
            @error('montant')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Section 3: Méthode de Paiement Mobile --}}
        <div class="mb-8 p-4 border rounded-lg bg-gray-50 dark:bg-gray-700">
            <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-3">Méthode de Paiement Mobile</h4>
            
            <div class="space-y-3">
                <label class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg shadow cursor-pointer border border-transparent hover:border-orange-500 transition">
                    <input type="radio" name="methode" value="Mobile Money" required class="form-radio h-4 w-4 text-orange-600 border-gray-300 focus:ring-orange-500" checked @if($demandesActives->isEmpty()) disabled @endif>
                    <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTPtpVPPlLhKzKFOGiDOt-d6WzozdTOt3lgHQ&s" alt="Mobile Money" class="h-5 w-auto mr-2">
                        FLOOZ / T-MONEY (Paiement Direct)
                    </span>
                </label>
                
                <label class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg shadow cursor-pointer border border-transparent hover:border-blue-500 transition">
                    <input type="radio" name="methode" value="Carte Bancaire" class="form-radio h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500" @if($demandesActives->isEmpty()) disabled @endif>
                    <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        Carte Bancaire (VISA / MasterCard)
                    </span>
                </label>
            </div>
        </div>

        {{-- BOUTON DE SOUMISSION AVEC SPINNER --}}
        <button type="submit" id="submitBtn"
            @if($demandesActives->isEmpty()) disabled @endif
            class="w-full px-4 py-3 font-semibold text-lg rounded-lg shadow-md transition duration-150 flex justify-center items-center
            {{ $demandesActives->isEmpty() ? 'bg-gray-400 cursor-not-allowed text-gray-200' : 'bg-indigo-600 text-white hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-500' }}">
            
            <span id="btnText">{{ $demandesActives->isEmpty() ? 'Paiement impossible' : 'Procéder au Paiement' }}</span>
            
            <svg id="btnSpinner" class="hidden animate-spin ml-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentForm = document.getElementById('paymentForm');
    const submitBtn = document.getElementById('submitBtn');
    const inputType = document.getElementById('type_demande');
    const selectDemande = document.getElementById('demande_id');

    // Met à jour le type au chargement et au changement
    function updateType() {
        const selectedOption = selectDemande.options[selectDemande.selectedIndex];
        if (selectedOption) {
            inputType.value = selectedOption.getAttribute('data-type');
        }
    }
    selectDemande.addEventListener('change', updateType);
    updateType();

    paymentForm.addEventListener('submit', function() {
        // Affiche le spinner et désactive le bouton
        submitBtn.disabled = true;
         document.getElementById('btnText').classList.add('opacity-50');
         document.getElementById('btnSpinner').classList.remove('hidden');
     });
});
</script>

@endsection