@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Modifier le Compte Utilisateur : {{ $user->prenom }} {{ $user->nom }}</div>

                <div class="card-body">
                    {{-- Le formulaire doit pointer vers la route 'update' et utiliser la méthode PATCH/PUT --}}
                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('PATCH') 
                        
                        {{-- Section Informations Personnelles --}}
                        <h3>Informations Personnelles</h3>
                        <hr>

                        <div class="row">
                            {{-- Champ Nom --}}
                            <div class="col-md-6 mb-3">
                                <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                {{-- Utilisation de $user->nom pour pré-remplir, ou old('nom') après une erreur --}}
                                <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{ old('nom', $user->nom) }}" required>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            {{-- Répétez ce modèle pour tous les autres champs (prenom, telephone, profession, situation_matrimonial, email, role) --}}
                            
                            {{-- Champ Prénom --}}
                            <div class="col-md-6 mb-3">
                                <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('prenom') is-invalid @enderror" id="prenom" name="prenom" value="{{ old('prenom', $user->prenom) }}" required>
                                @error('prenom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Champ Téléphone --}}
                        <div class="mb-3">
                            <label for="telephone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('telephone') is-invalid @enderror" id="telephone" name="telephone" value="{{ old('telephone', $user->telephone) }}" required>
                            @error('telephone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <h3>Statut</h3>
                        <hr>

                        <div class="mb-3">
                            <label for="profession" class="form-label">Profession <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('profession') is-invalid @enderror" id="profession" name="profession" value="{{ old('profession', $user->profession) }}" required>
                            @error('profession')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="situation_matrimonial" class="form-label">Situation Matrimoniale <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('situation_matrimonial') is-invalid @enderror" id="situation_matrimonial" name="situation_matrimonial" value="{{ old('situation_matrimonial', $user->situation_matrimonial) }}" required>
                            @error('situation_matrimonial')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <h3>Accès et Rôle</h3>
                        <hr>
                        
                        {{-- Champ Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Champ Rôle --}}
                        <div class="mb-4">
                            <label for="role" class="form-label">Rôle Attribué <span class="text-danger">*</span></label>
                            <select id="role" name="role" class="form-control @error('role') is-invalid @enderror" required>
                                <option value="">-- Choisir un rôle --</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role }}" {{ old('role', $user->role) == $role ? 'selected' : '' }}>
                                        {{ ucfirst($role) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-warning btn-lg">Mettre à jour l'utilisateur</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Annuler</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection