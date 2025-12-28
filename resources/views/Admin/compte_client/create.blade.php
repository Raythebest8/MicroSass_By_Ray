@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            
            {{-- Fil d'ariane / Retour --}}
            <nav aria-label="breadcrumb" class="mb-3">
                <a href="{{ route('admin.users.index') }}" class="text-decoration-none small text-muted">
                    <i class="fas fa-arrow-left"></i> Retour à la liste des utilisateurs
                </a>
            </nav>

            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 font-weight-bold text-primary">Créer un Nouveau Compte</h5>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf

                        {{-- Section 1 --}}
                        <div class="mb-4">
                            <h6 class="text-uppercase text-muted small font-weight-bold mb-3" style="letter-spacing: 1px;">
                                <i class="fas fa-user-circle mr-2"></i> Informations Personnelles
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nom" class="small font-weight-bold">Nom <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control custom-input @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{ old('nom') }}" placeholder="Nom de famille" required>
                                    @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="prenom" class="small font-weight-bold">Prénom <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control custom-input @error('prenom') is-invalid @enderror" id="prenom" name="prenom" value="{{ old('prenom') }}" placeholder="Prénom(s)" required>
                                    @error('prenom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="telephone" class="small font-weight-bold">Téléphone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control custom-input @error('telephone') is-invalid @enderror" id="telephone" name="telephone" value="{{ old('telephone') }}" placeholder="Ex: +228 90 00 00 00" required>
                                @error('telephone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Section 2 --}}
                        <div class="mb-4">
                            <h6 class="text-uppercase text-muted small font-weight-bold mb-3" style="letter-spacing: 1px;">
                                <i class="fas fa-briefcase mr-2"></i> Statut & Situation
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="profession" class="small font-weight-bold">Profession <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control custom-input @error('profession') is-invalid @enderror" id="profession" name="profession" value="{{ old('profession') }}" placeholder="Ex: Agent de crédit" required>
                                    @error('profession') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="situation_matrimonial" class="small font-weight-bold">Situation Matrimoniale <span class="text-danger">*</span></label>
                                    <select class="form-control custom-input @error('situation_matrimonial') is-invalid @enderror" name="situation_matrimonial" required>
                                        <option value="">Sélectionner...</option>
                                        <option value="Célibataire" {{ old('situation_matrimonial') == 'Célibataire' ? 'selected' : '' }}>Célibataire</option>
                                        <option value="Marié(e)" {{ old('situation_matrimonial') == 'Marié(e)' ? 'selected' : '' }}>Marié(e)</option>
                                        <option value="Divorcé(e)" {{ old('situation_matrimonial') == 'Divorcé(e)' ? 'selected' : '' }}>Divorcé(e)</option>
                                    </select>
                                    @error('situation_matrimonial') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Section 3 --}}
                        <div class="mb-4">
                            <h6 class="text-uppercase text-muted small font-weight-bold mb-3" style="letter-spacing: 1px;">
                                <i class="fas fa-lock mr-2"></i> Accès et Rôle
                            </h6>
                            <div class="mb-3">
                                <label for="email" class="small font-weight-bold">Adresse Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control custom-input @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="adresse@domaine.com" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="role" class="small font-weight-bold">Rôle Attribué <span class="text-danger">*</span></label>
                                <select id="role" name="role" class="form-control custom-input @error('role') is-invalid @enderror" required>
                                    <option value="">-- Choisir un niveau d'accès --</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                                            {{ ucfirst($role) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mt-5 d-flex justify-content-between">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-light border px-4">Annuler</a>
                            <button type="submit" class="btn btn-primary px-5 font-weight-bold shadow-sm">
                                <i class="fas fa-save mr-2"></i> Créer le compte
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body { background-color: #f8f9fa; }
    .custom-input {
        border-radius: 8px;
        border: 1px solid #ced4da;
        padding: 0.6rem 1rem;
        transition: all 0.2s;
    }
    .custom-input:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.1);
    }
    .card-header { border-top-left-radius: 12px !important; border-top-right-radius: 12px !important; }
    hr { border-top: 1px solid #edf2f7; }
</style>
@endsection