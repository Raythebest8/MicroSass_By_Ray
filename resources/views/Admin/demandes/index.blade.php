@extends('layouts.app') 

@section('content')

<style>
/* --- VOS STYLES CSS CONSERVÉS --- */
:root {
    --primary-color: #007bff; /* Bleu principal */
    --success-color: #28a745;
    --danger-color: #dc3545;
    --warning-color: #ffc107;
    --text-color: #333;
    --muted-color: #6c757d;
    --border-color: #e3e6f0;
    --bg-light: #f8f9fc;
}

.container { width: 95%; max-width: 1300px; margin: 0 auto; padding: 20px 0; }
.page-title { color: var(--primary-color); font-size: 24px; margin-bottom: 20px; border-bottom: 2px solid var(--primary-color); padding-bottom: 10px; }
.card-admin { background: white; border-radius: 8px; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15); margin-bottom: 30px; }
.card-header-flex { display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; border-bottom: 1px solid var(--border-color); }
.search-area { display: flex; align-items: center; width: 400px; }
.icon-muted { color: var(--muted-color); margin-right: 10px; }
.input-search { flex-grow: 1; padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 4px; font-size: 14px; }
.filters-area { display: flex; gap: 10px; }
.select-filter { padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 4px; font-size: 14px; background-color: white; }
.card-body-table { padding: 0; }
.table-responsive-wrapper { overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; font-size: 14px; }
.data-table thead { background-color: var(--bg-light); border-bottom: 2px solid var(--border-color); }
.data-table th, .data-table td { padding: 12px 15px; text-align: left; vertical-align: middle; }
.data-table tbody tr:nth-child(even) { background-color: #f3f3f3; }
.table-row-hover:hover { background-color: #e9ecef; }
/* --- ATTENTION : J'ai retiré ici les styles 'header-sort' inutiles pour votre demande --- */
.user-info-flex { display: flex; align-items: center; }
.avatar-sm { width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; object-fit: cover; border: 1px solid var(--border-color); }
.user-name { font-weight: bold; }
.user-email { color: var(--muted-color); font-size: 12px; }
.amount-value { font-weight: bold; color: var(--success-color); }
.badge-status { display: inline-block; padding: 5px 10px; border-radius: 50px; font-size: 12px; font-weight: bold; text-transform: uppercase; text-align: center; }
.badge-type { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; background-color: #eee; color: var(--text-color); }
.badge-en_attente { background-color: var(--warning-color); color: #333; }
.badge-validee { background-color: var(--success-color); color: white; }
.badge-rejetee { background-color: var(--danger-color); color: white; }
.action-buttons-flex { display: flex; gap: 5px; }
.btn { padding: 8px 10px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; line-height: 1; text-decoration: none; transition: background-color 0.2s; }
.btn-info { background-color: var(--primary-color); color: white; }
.btn-success { background-color: var(--success-color); color: white; }
.btn-danger { background-color: var(--danger-color); color: white; }
.card-footer-center { padding: 15px 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: center; }
.text-center-empty { text-align: center; padding: 40px; color: var(--muted-color); }
.text-center-empty i { color: #ccc; margin-bottom: 10px; }
/* --- AJOUTEZ CECI DANS VOTRE BLOC <style> DANS index.blade.php --- */
/* resources/views/index.blade.php (dans la balise <style>) */

/* * Force les éléments du modal à être au-dessus 
 * de tous les autres éléments de la page (comme votre sidebar ou header)
 */
.modal-backdrop {
    /* Normalement 1040 */
    z-index: 10400 !important;
}

.modal {
    /* Normalement 1050 (doit être supérieur au backdrop) */
    z-index: 10500 !important;
}
body {
    position: initial !important; 
    overflow: auto !important; 
}

.modal-open {
    /* Assurez-vous que l'overflow n'est pas caché lorsque le modal est ouvert */
    overflow: auto !important; 
}
@media (max-width: 768px) { /* ... styles responsive ... */ }
</style>

<div class="container">
    <h2 class="page-title">Gestion des Demandes de Prêt</h2>
    
    <div class="card-admin">
        
        <div class="card-header-flex">
            
            <div class="search-area">
                <i class="fas fa-search icon-muted"></i>
                <input type="text" class="input-search" placeholder="Rechercher...">
            </div>
            
            <div class="filters-area">
                <select class="select-filter">
                    <option selected>Tous les status</option>
                    <option value="en_attente">EN ATTENTE</option>
                    <option value="validee">VALIDÉE</option>
                    <option value="rejetee">REFUSÉE</option>
                </select>
                <select class="select-filter">
                    <option selected>Tous les types</option>
                    <option value="particulier">Particulier</option>
                    <option value="entreprise">Entreprise</option>
                </select>
            </div>
        </div>
        
        <div class="card-body-table">
            <div class="table-responsive-wrapper">
                <table class="data-table" id="demandeTable">
                    <thead>
                        <tr>
                            <th>NOM</th>
                            <th>TYPE</th>
                            <th>DATE SOUMISSION</th>
                            <th>MONTANT</th>
                            <th>STATUS</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($demandes as $demande)
                            @php
                                $statut = strtolower(str_replace(' ', '_', $demande->statut));
                                $statusLabel = str_replace('_', ' ', $demande->statut);
                            @endphp
                            <tr class="table-row-hover">
                                {{-- 1. NOM --}}
                                <td data-label="Nom">
                                    <div class="user-info-flex">
                                            <img src="{{ $demande->user->image_path ?? asset('images/default-avatar.png') }}" class="avatar-sm" alt="Avatar">                                        <div>
                                            <div class="user-name">{{ $demande->user->nom  ?? 'Utilisateur Inconnu' }}</div>
                                            <small class="user-email">{{ $demande->user->email ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                
                                {{-- 2. TYPE --}}
                                <td data-label="Type">
                                   <span class="badge-type">{{ ucfirst($demande->type) }}</span>
                                </td>
                                
                                {{-- 3. DATE SOUMISSION --}}
                                <td data-label="Date">
                                    {{ $demande->created_at->isoFormat('ddd D MMM, HH:mm') }}
                                </td>
                                
                                {{-- 4. MONTANT --}}
                                <td data-label="Montant" class="amount-value">
                                    {{ number_format($demande->montant_souhaite ?? 0, 0, ',', ' ') }} FCFA
                                </td>
                                
                                {{-- 5. STATUS --}}
                                <td data-label="Statut">
                                    <span class="badge-status badge-{{ $statut }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                
                                {{-- 6. ACTIONS (C'EST LA QUE LE JS DE BOOTSTRAP AGIT) --}}
                                <td data-label="Actions" class="action-buttons-flex">
                                    
                                    {{-- Détails --}}
                                    <a href="{{ route('admin.demandes.details', [
                                        'type' => $demande->type,              
                                        'demandeId' => $demande->id            
                                    ]) }}" class="btn btn-sm btn-info" title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if ($statut === 'en_attente')
                                        
                                        {{-- 🎯 BOUTON VALIDER : data-target doit correspondre à l'ID exact du modal inclus --}}
                                        <button type="button" 
                                            data-toggle="modal" 
                                            data-target="#modal-valider-{{ $demande->type }}-{{ $demande->id }}" 
                                            class="btn btn-success" 
                                            title="Valider">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        
                                        {{-- 🎯 BOUTON REFUSER : data-target doit correspondre à l'ID exact du modal inclus --}}
                                        <button type="button" 
                                            data-toggle="modal" 
                                            data-target="#modal-refuser-{{ $demande->type }}-{{ $demande->id }}" 
                                            class="btn btn-danger" 
                                            title="Refuser">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center-empty">
                                    <i class="fas fa-box-open fa-3x mb-3"></i>
                                    <p>Aucune demande de prêt trouvée.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer-center">
            {{ $demandes->links() }}
        </div>
    </div>
</div>

{{-- MODALS : Inclusion des fichiers de modal (Assurez-vous qu'ils existent et ont les bons IDs) --}}
@foreach ($demandes->filter(function ($d) { return strtolower($d->statut) === 'en attente' || strtolower($d->statut) === 'en_attente'; }) as $demande)
    @include('admin.demandes.modals.modal-valider', ['demande' => $demande])
    @include('admin.demandes.modals.modal-refuser', ['demande' => $demande])
@endforeach

@endsection
@section('scripts')
<script>
    /**
     * Correction du Z-Index des Modals Bootstrap.
     * Déplace l'élément du modal directement sous la balise <body> lors de son affichage.
     */
    $(document).ready(function() {
        
        // Écoute l'événement 'show' qui est déclenché juste avant que le modal ne devienne visible
        $('.modal').on('show.bs.modal', function() {
            
            // Déplace l'élément du modal pour qu'il soit un enfant direct du <body>
            // Cela l'extrait de tout conteneur HTML susceptible de causer un conflit de z-index.
            $(this).appendTo('body');
        });
        
    });
</script>
@endsection
{{-- Dans resources/views/admin/demandes/index.blade.php, après @endsection --}}
@section('scripts') 
    <script src="https://kit.fontawesome.com/votre-kit-font-awesome.js"></script>
    <script>
        // Placez le code jQuery ci-dessus ici
        $(document).ready(function() {
            $('.modal').on('show.bs.modal', function() {
                $(this).appendTo('body');
            });
        });
    </script>
@endsection
