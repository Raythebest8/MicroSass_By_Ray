@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Liste des Comptes Utilisateurs</h1>

            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Créer un Nouvel Utilisateur
            </a>
        </div>
    </div>

    {{-- Affichage du message de succès et d'erreur --}}
    @if (session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger" role="alert">
        {{ session('error') }}
    </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            @if ($users->isEmpty())
            <p class="text-info">Aucun utilisateur n'a été trouvé.</p>
            @else
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Numero de Compte</th>
                        <th>Nom & Prénom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Profession</th>
                        <th>Rôle</th>
                        {{-- Suppression de la colonne "password" pour des raisons de sécurité --}}
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->numero_compte ?? $user->id }} </td>
                        <td>{{ $user->prenom }} {{ $user->nom }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->telephone ?? 'N/A' }}</td>
                        <td>{{ $user->profession ?? 'N/A' }}</td>
                        <td><span class="badge 
                            @if($user->role == 'admin') bg-danger 
                            @elseif($user->role == 'manager') bg-warning text-dark
                            @else bg-success 
                            @endif">
                            {{ strtoupper($user->role) }}
                        </span></td>
                        
                        {{-- MISE EN PAGE FLEX AVEC ICÔNES --}}
                        <td class="d-flex gap-2"> 
                            
                            {{-- Bouton VOIR (show) --}}
                            {{-- Ajout de l'icône de l'œil --}}
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info" title="Voir les détails">
                                <i class="fas fa-eye"></i>
                            </a>

                            {{-- Bouton MODIFIER (edit) --}}
                            {{-- Ajout de l'icône du crayon --}}
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning" title="Modifier">
                                <i class="fas fa-pencil-alt"></i>
                            </a>

                            {{-- Bouton SUPPRIMER (destroy) --}}
                            {{-- Ajout de l'icône de la poubelle --}}
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer {{ $user->prenom }} {{ $user->nom }} ?')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>
@endsection