@extends('layouts.users')

@section('content')

    {{-- Conteneur principal centré --}}
    <div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8">
        
        {{-- Titre --}}
        <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">Mon Profil</h1>

        {{-- Message de succès après redirection --}}
        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif
        
        {{-- =============================================== --}}
        {{-- BLOC PRINCIPAL : Photo et Infos (Lecture Seule) --}}
        {{-- =============================================== --}}
        <div class="bg-white shadow-xl rounded-xl p-6 mb-8 flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-8">
            
            {{-- 1. Photo de Profil --}}
            <div class="flex-shrink-0">
                <img 
                    src="{{ asset('storage/' . ($user->image_path ?? 'default/default-photo.png')) }}" 
                    alt="Photo de profil" 
                    class="w-32 h-32 object-cover rounded-full border-4 border-indigo-500 shadow-lg" 
                >
            </div>
            
            {{-- 2. Informations Personnelles --}}
            <div class="flex-grow w-full">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Détails du Compte</h2>
                
                <dl class="space-y-3">
                    {{-- Nom --}}
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center p-2 bg-gray-50 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500">Nom complet :</dt>
                        <dd class="mt-1 text-base text-gray-800 sm:mt-0 sm:ml-4 font-semibold">{{ $user->prenom }} {{ $user->nom }} </dd>
                    </div>
                    
                    {{-- Email --}}
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center p-2 bg-gray-50 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500">Adresse Email :</dt>
                        <dd class="mt-1 text-base text-gray-800 sm:mt-0 sm:ml-4 font-semibold">{{ $user->email }}</dd>
                    </div>

                    <div>date dincdiption</div>
                    <div>numero de compte </div>
                    
                </dl>
            </div>
        </div>

        {{-- =============================================== --}}
        {{-- BLOCS DE MODIFICATION --}}
        {{-- =============================================== --}}

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            {{-- Formulaire 1 : Photo de Profil (pour modification) --}}
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Modifier la Photo</h3>
                
                <form action="{{ route('users.profile.update.photo') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="photo" class="block text-sm font-medium text-gray-700 mb-1">Sélectionner une nouvelle photo</label>
                        <input 
                            type="file" 
                            name="image_path" 
                            id="photo" 
                            class="block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-indigo-700
                                hover:file:bg-indigo-100
                                @error('photo') border-red-500 @enderror"
                        >
                        
                        @error('photo')
                            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                        Mettre à jour la photo
                    </button>
                </form>
            </div>
            
            {{-- Formulaire 2 : Changement de Mot de Passe (pour modification) --}}
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Changer le Mot de Passe</h3>
                
                <form action="{{ route('users.profile.update.password') }}" method="POST">
                    @csrf
                    
                    {{-- Mot de Passe Actuel --}}
                    <div class="mb-4">
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe actuel</label>
                        <input 
                            type="password" 
                            name="current_password" 
                            id="current_password" 
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 
                                @error('current_password') border-red-500 @enderror"
                        >
                        @error('current_password')
                            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- Nouveau Mot de Passe --}}
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe</label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 
                                @error('password') border-red-500 @enderror"
                        >
                        @error('password')
                            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- Confirmation du Nouveau Mot de Passe --}}
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le nouveau mot de passe</label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation" 
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    </div>
                    
                    <button type="submit" class="w-full px-4 py-2 bg-yellow-600 text-white font-semibold rounded-lg shadow-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2 transition duration-150 ease-in-out">
                        Changer le Mot de Passe
                    </button>
                </form>
            </div>
        </div>

    </div>

@endsection