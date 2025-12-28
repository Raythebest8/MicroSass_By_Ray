@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    {{-- En-tête Dynamique --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Gestion des Utilisateurs</h1>
            <p class="text-muted small mb-0">Total : {{ $users->count() }} comptes enregistrés</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary px-4 shadow-sm" style="border-radius: 12px; font-weight: 600;">
            <i class="fas fa-plus-circle mr-2"></i> Créer un compte
        </a>
    </div>

    {{-- Notifications Stylisées --}}
    @if (session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px; border-left: 5px solid #28a745 !important;">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Card Principale --}}
    <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
        <div class="card-body p-0">
            @if ($users->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-users-slash fa-3x text-gray-300 mb-3"></i>
                    <p class="text-muted">Aucun utilisateur trouvé dans la base de données.</p>
                </div>
            @else
            <div class="table-responsive">
                <table class="table align-middle mb-0" style="min-width: 1000px;">
                    <thead class="bg-light text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                        <tr>
                            <th class="border-0 px-4 py-3 text-muted">Utilisateur</th>
                            <th class="border-0 py-3 text-muted">Coordonnées</th>
                            <th class="border-0 py-3 text-muted">Profession</th>
                            <th class="border-0 py-3 text-muted">Rôle</th>
                            <th class="border-0 py-3 text-right text-muted px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.9rem;">
                        @foreach ($users as $user)
                        <tr class="hover-row transition-all">
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle mr-3">
                                        {{ strtoupper(substr($user->prenom, 0, 1)) }}{{ strtoupper(substr($user->nom, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-dark mb-0">{{ $user->prenom }} {{ $user->nom }}</div>
                                        <div class="small text-muted">Compte: {{ $user->numero_compte ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="small"><i class="far fa-envelope text-muted mr-1"></i> {{ $user->email }}</div>
                                <div class="small"><i class="fas fa-phone-alt text-muted mr-1"></i> {{ $user->telephone ?? 'N/A' }}</div>
                            </td>
                            <td class="py-3 font-weight-500">
                                {{ $user->profession ?? 'Indéfini' }}
                            </td>
                            <td class="py-3">
                                @php
                                    $badgeClass = match($user->role) {
                                        'admin' => 'badge-soft-danger',
                                        'manager' => 'badge-soft-warning',
                                        default => 'badge-soft-success'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }} px-3 py-2">
                                    <i class="fas fa-circle mr-1" style="font-size: 6px; vertical-align: middle;"></i>
                                    {{ strtoupper($user->role) }}
                                </span>
                            </td>
                            <td class="py-3 text-right px-4">
                                <div class="btn-group shadow-sm" style="border-radius: 8px; overflow: hidden;">
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-white btn-sm" title="Voir">
                                        <i class="fas fa-eye text-info"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-white btn-sm" title="Modifier">
                                        <i class="fas fa-pencil-alt text-warning"></i>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-white btn-sm" title="Supprimer" onclick="return confirm('Supprimer cet utilisateur ?')">
                                            <i class="fas fa-trash-alt text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- CSS Additionnel pour le "Look & Feel" --}}
<style>
    .avatar-circle {
        width: 40px; height: 40px; border-radius: 50%; background: #f0f2f5;
        color: #4e73df; display: flex; align-items: center; justify-content: center;
        font-weight: bold; font-size: 0.85rem; border: 1px solid #e3e6f0;
    }
    .hover-row:hover { background-color: #f8f9fc; }
    .btn-white { background: #fff; border: 1px solid #e3e6f0; }
    .btn-white:hover { background: #f8f9fc; }
    
    /* Badges Soft (Modernes) */
    .badge-soft-danger { background-color: #ffe5e5; color: #e74a3b; border-radius: 6px; }
    .badge-soft-warning { background-color: #fff4e5; color: #f6c23e; border-radius: 6px; }
    .badge-soft-success { background-color: #e5f9e5; color: #1cc88a; border-radius: 6px; }
    
    .transition-all { transition: all 0.2s; }
</style>
@endsection