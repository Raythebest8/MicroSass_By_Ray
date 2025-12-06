@extends('layouts.users')

@section('content')

{{-- 
    SUPPRESSION de la classe 'max-w-lg' et 'mx-auto'
    Le conteneur utilisera maintenant la largeur par défaut, qui est 100% (full width)
    du conteneur parent (généralement le corps de la section 'content').
--}}
<div class="my-12 p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700">
    
    <div class="mb-8 text-center">
        <h3 class="text-3xl font-extrabold text-indigo-700 dark:text-indigo-400 flex items-center justify-center">
            <i class="fas fa-file-contract mr-3"></i> Demande de Prêt
        </h3>
        <p class="text-md text-gray-500 dark:text-gray-400 mt-2">
            Veuillez sélectionner votre profil pour accéder au formulaire adapté et visualiser les documents requis.
        </p>
    </div>

    @if (session('error'))
        <div class="p-3 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-900 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif
    
    <div class="p-6 bg-gray-50 dark:bg-gray-700 rounded-xl border border-gray-200 dark:border-gray-600">
        <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-5 border-b pb-3 border-gray-200 dark:border-gray-600">
            Sélectionnez votre Type de Demandeur
        </h4>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            
            <button id="select-particulier" data-type="particulier"
                    class="type-selector flex flex-col items-center justify-center p-8 
                           bg-gray-200 dark:bg-gray-600 rounded-xl transition duration-300 
                           border-2 border-gray-200 dark:border-gray-600
                           hover:border-indigo-600 hover:bg-indigo-50 hover:shadow-lg dark:hover:bg-indigo-900">
                <i class="fas fa-user-circle text-5xl mb-3 text-indigo-600 dark:text-indigo-400"></i>
                <span class="font-extrabold text-lg text-gray-800 dark:text-gray-100 mt-2">Particulier</span>
                <span class="text-xs text-gray-600 dark:text-gray-300 mt-1">Prêts personnels, auto, conso.</span>
            </button>

            <button id="select-entreprise" data-type="entreprise"
                    class="type-selector flex flex-col items-center justify-center p-8 
                           bg-gray-200 dark:bg-gray-600 rounded-xl transition duration-300 
                           border-2 border-gray-200 dark:border-gray-600
                           hover:border-indigo-600 hover:bg-indigo-50 hover:shadow-lg dark:hover:bg-indigo-900">
                <i class="fas fa-building text-5xl mb-3 text-indigo-600 dark:text-indigo-400"></i>
                <span class="font-extrabold text-lg text-gray-800 dark:text-gray-100 mt-2">Entreprise</span>
                <span class="text-xs text-gray-600 dark:text-gray-300 mt-1">Investissement, fonds de roulement.</span>
            </button>
            
        </div>
    </div>
    
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const particulierBtn = document.getElementById('select-particulier');
        const entrepriseBtn = document.getElementById('select-entreprise');
        
        // Les routes doivent être définies comme 'user.demande.particulier' et 'user.demande.entreprise'
        
        particulierBtn.addEventListener('click', () => {
            // Redirection vers le formulaire Particulier
            window.location.href = "{{ route('users.demande.particulier') }}"; 
        });

        entrepriseBtn.addEventListener('click', () => {
            // Redirection vers le formulaire Entreprise
            window.location.href = "{{ route('users.demande.entreprise') }}"; 
        });
    });
</script>

@endsection