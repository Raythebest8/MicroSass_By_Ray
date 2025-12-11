@extends('layouts.app') 

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4 text-primary"> Centre de Notifications Administrateur</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 offset-lg-1">
            
            {{-- Compteur d'état --}}
            <div class="card shadow mb-4">
                <div class="card-body">
                    <p class="mb-0">
                        Vous avez actuellement 
                        <span class="font-weight-bold text-danger">{{ Auth::user()->unreadNotifications->count() }}</span> notifications non lues 
                        sur un total de 
                        <span class="font-weight-bold">{{ Auth::user()->notifications()->count() }}</span>.
                    </p>
                </div>
            </div>

            {{-- Liste des Notifications --}}
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Historique des 20 dernières alertes</h6>
                    
                    {{-- Bouton Marquer tout comme lu --}}
                    @if (Auth::user()->unreadNotifications->count() > 0)
                        <form method="POST" action="{{ route('admin.notifications.mark_all_read') }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-check-double"></i> Marquer tout comme lu
                            </button>
                        </form>
                    @endif
                </div>
                
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        
                        @forelse ($notifications as $notification)
                            {{-- Chaque item de la liste --}}
                            <li class="list-group-item d-flex align-items-center 
                                {{ $notification->read_at ? '' : 'bg-light-danger' }}" 
                            @style([
                                'border-left: 5px solid #ffc107; font-weight: 500;' => !$notification->read_at,])
                                {{-- Icône et Statut --}}
                                <div class="mr-3 text-center" style="min-width: 25px;">
                                    @if (!$notification->read_at)
                                        <i class="fas fa-circle text-warning small"></i>
                                    @else
                                        <i class="fas fa-envelope-open text-muted small"></i>
                                    @endif
                                </div>

                                {{-- Message de la Notification --}}
                                <div class="flex-grow-1">
                                    {{-- Titre/Type --}}
                                    <span class="d-block text-gray-800">
                                        {{ $notification->data['type'] }} 
                                        ({{ $notification->data['demande_type'] ?? 'N/A' }})
                                    </span>
                                    {{-- Détails --}}
                                    <small class="text-muted">
                                        Demande {{ $notification->data['demande_id'] }} par 
                                        <span class="font-weight-bold">{{ $notification->data['client_name'] ?? 'Inconnu' }}</span> - Montant: {{ $notification->data['montant'] ?? 'N/A' }}
                                    </small>
                                </div>

                                {{-- Temps et Action --}}
                                <div class="ml-auto text-right">
                                    <small class="d-block text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                    
                                    {{-- Lien d'action : Marquer comme lu et redirection --}}
                                    <a href="{{ route('admin.notifications.read', ['id' => $notification->id, 'redirect' => $notification->data['url'] ?? route('admin.dashboard')]) }}" 
                                       class="btn btn-sm {{ $notification->read_at ? 'btn-outline-secondary' : 'btn-primary' }} mt-1">
                                        {{ $notification->read_at ? 'Voir les détails' : 'Traiter et Marquer lu' }}
                                    </a>
                                </div>
                            </li>

                        @empty
                            <li class="list-group-item text-center text-muted">
                                Félicitations ! Aucun historique de notification trouvé.
                            </li>
                        @endforelse
                    </ul>
                </div>
                
                {{-- Pagination --}}
                <div class="card-footer">
                    {{ $notifications->links() }}
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@push('styles')
    {{-- CSS déplacé ici pour une meilleure organisation --}}
    <style>
        .list-group-item.bg-light-danger {
            background-color: #fff3cd !important; /* Couleur jaune clair pour non lu */
        }
    </style>
@endpush