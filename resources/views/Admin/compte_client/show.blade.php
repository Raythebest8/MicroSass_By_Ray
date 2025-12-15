@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Détails de l'utilisateur : {{ $user->prenom }} {{ $user->nom }}</div>

                <div class="card-body">
                    <h5 class="card-title">Informations Générales</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Nom :</strong> {{ $user->nom }}</li>
                        <li class="list-group-item"><strong>Prénom :</strong> {{ $user->prenom }}</li>
                        <li class="list-group-item"><strong>Email :</strong> {{ $user->email }}</li>
                        <li class="list-group-item"><strong>Rôle :</strong> {{ ucfirst($user->role) }}</li>
                        <li class="list-group-item"><strong>Téléphone :</strong> {{ $user->telephone }}</li>
                    </ul>

                    <h5 class="card-title mt-4">Statut Professionnel</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Profession :</strong> {{ $user->profession }}</li>
                        <li class="list-group-item"><strong>Situation Matrimoniale :</strong> {{ $user->situation_matrimonial }}</li>
                    </ul>

                    <div class="mt-4">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">Modifier</a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Retour à la Liste</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection