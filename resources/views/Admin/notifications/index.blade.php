@extends('layouts.app')

@section('content')
{{-- Style intégré directement pour éviter l'usage de @push --}}

<style>
.bg-light-warning {
background-color: #fffdf5 !important;
}
.notification-item {
transition: all 0.2s ease;
border-left: 4px solid transparent;
}
.notification-item:hover {
background-color: #f8f9fa;
transform: translateX(5px);
}
.unread-border {
border-left: 4px solid #ffc107 !important;
}
.status-indicator {
width: 10px;
height: 10px;
border-radius: 50%;
display: inline-block;
}
.text-indigo {
color: #4e73df;
font-weight: 600;
}
</style>

<div class="container-fluid py-4">
<div class="row">
<div class="col-12">
<h2 class="mb-4 text-primary font-weight-bold">
<i class="fas fa-bell mr-2"></i> Centre de Notifications Administrateur
</h2>
</div>
</div>

<div class="row">
    <div class="col-lg-10 offset-lg-1">

        {{-- Carte de résumé des compteurs --}}
        <div class="card shadow-sm border-0 mb-4 bg-white">
            <div class="card-body d-flex align-items-center justify-content-between">
                <p class="mb-0 text-dark">
                    Vous avez actuellement 
                    <span class="badge badge-danger px-2 py-1">{{ Auth::user()->unreadNotifications->count() }}</span> 
                    notifications non lues sur un total de 
                    <span class="font-weight-bold text-primary">{{ Auth::user()->notifications()->count() }}</span>.
                </p>
                
                @if (Auth::user()->unreadNotifications->count() > 0)
                <form method="POST" action="{{ route('admin.notifications.markAllAsRead') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-success shadow-sm">
                        <i class="fas fa-check-double mr-1"></i> Tout marquer comme lu
                    </button>
                </form>
                @endif
            </div>
        </div>

        {{-- Liste principale des Notifications --}}
        <div class="card shadow border-0">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="m-0 font-weight-bold text-gray-800">Historique des alertes récentes</h6>
            </div>

            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse ($notifications as $notification)
                    @php
                        $isUnread = is_null($notification->read_at);
                        $data = $notification->data;
                        $redirectUrl = $data['url'] ?? route('admin.dashboard');
                    @endphp
                    <li class="list-group-item notification-item d-flex align-items-center p-3 {{ $isUnread ? 'bg-light-warning unread-border' : '' }}">
                        
                        {{-- Indicateur visuel d'état --}}
                        <div class="mr-3 text-center" style="min-width: 30px;">
                            @if($isUnread)
                                <span class="status-indicator bg-warning shadow-sm" title="Non lu"></span>
                            @else
                                <i class="fas fa-envelope-open text-muted opacity-50" title="Lu"></i>
                            @endif
                        </div>

                        {{-- Détails du contenu --}}
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="d-block font-weight-bold {{ $isUnread ? 'text-dark' : 'text-muted' }}">
                                    {{ $data['type'] ?? 'Notification Système' }}
                                </span>
                                <small class="text-muted">
                                    <i class="far fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}
                                </small>
                            </div>
                            <div class="text-gray-700 small mt-1">
                                <span class="text-indigo">{{ $data['demande_type'] ?? 'Type de demande' }}</span> #{{ $data['demande_id'] ?? '?' }} 
                                soumise par <span class="font-weight-bold">{{ $data['user_nom'] ?? 'un utilisateur' }}</span>
                            </div>
                        </div>

                        {{-- Boutons d'actions rapides --}}
                        <div class="ml-3">
                            <a href="{{ route('admin.notifications.read', ['id' => $notification->id, 'redirect' => $redirectUrl]) }}"
                               class="btn btn-sm {{ $isUnread ? 'btn-primary shadow-sm' : 'btn-outline-secondary' }} rounded-pill px-3"
                               title="{{ $isUnread ? 'Traiter immédiatement' : 'Consulter' }}">
                                @if($isUnread)
                                    Traiter <i class="fas fa-arrow-right ml-1"></i>
                                @else
                                    Détails <i class="fas fa-eye ml-1"></i>
                                @endif
                            </a>
                        </div>
                    </li>
                    @empty
                    <li class="list-group-item text-center py-5">
                        <div class="opacity-25 mb-3">
                            <i class="fas fa-bell-slash fa-3x"></i>
                        </div>
                        <p class="text-muted mb-0">Aucune notification à afficher pour le moment.</p>
                    </li>
                    @endforelse
                </ul>
            </div>

            {{-- Pagination --}}
            @if($notifications->hasPages())
            <div class="card-footer bg-white border-top d-flex justify-content-center">
                <div class="pagination-wrapper">
                    {{ $notifications->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>


</div>
@endsection