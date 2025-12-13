@extends('layouts.app') 

@section('content')

<style>
/* --- STYLES CSS --- */
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
.search-area { display: flex; align-items: center; width: 300px; } /* Ajusté la largeur pour l'espace */
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
.action-buttons-flex { display: flex; gap: 10px; align-items: center; } /* Élargi le gap */
.btn { padding: 8px 10px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; line-height: 1; text-decoration: none; transition: background-color 0.2s; }
.btn-info { background-color: var(--primary-color); color: white; }
.btn-success { background-color: var(--success-color); color: white; }
.btn-danger { background-color: var(--danger-color); color: white; }
.card-footer-center { padding: 15px 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: center; }
.text-center-empty { text-align: center; padding: 40px; color: var(--muted-color); }
.text-center-empty i { color: #ccc; margin-bottom: 10px; }

/* Styles pour modals Bootstrap si vous utilisez un template custom */
.modal-backdrop { z-index: 10400 !important; }
.modal { z-index: 10500 !important; }
body { position: initial !important; overflow: auto !important; }
.modal-open { overflow: auto !important; }
</style>

<div class="container">
    <h2 class="page-title">Gestion des Demandes de Prêt</h2>
    
    <div class="card-admin">
        
        <div class="card-header-flex">
            
            <div class="search-area">
                <i class="fas fa-search icon-muted"></i>
                <input type="text" class="input-search" placeholder="Rechercher...">
            </div>

            {{-- CONTENEUR POUR LES BOUTONS D'ACTION RAPIDE (CIBLE JS) --}}
            <div id="dynamic-actions-top" class="action-buttons-flex">
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
                                // Assurez-vous que le statut est bien 'en_attente', 'validee' ou 'rejetee'
                                $statut = strtolower(str_replace([' ', '-'], '_', $demande->statut));
                                $statusLabel = ucfirst(str_replace('_', ' ', $statut));
                            @endphp
                            <tr class="table-row-hover">
                                {{-- 1. NOM --}}
                                <td data-label="Nom">
                                    <div class="user-info-flex">
                                        {{-- CORRECTION ICI : Utilisation de asset('storage/') et gestion du cas null --}}
                                        <img src="{{ asset('storage/' . ($demande->user->image_path ?? 'default/default-photo.png')) }}" 
                                            class="avatar-sm" 
                                            alt="Avatar de {{ $demande->user->nom ?? 'Utilisateur Inconnu' }}"> 
                                        <div>
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
                                
                                {{-- 6. ACTIONS --}}
                                <td data-label="Actions" class="action-buttons-flex">
                                    
                                    {{-- Détails --}}
                                    <a href="{{ route('admin.demandes.details', [
                                        'type' => $demande->type,          
                                        'demandeId' => $demande->id           
                                    ]) }}" class="btn btn-sm btn-info" title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if ($statut === 'en_attente')
                                        
                                        {{-- BOUTON VALIDER --}}
                                        <button type="button" 
                                            data-toggle="modal" 
                                            data-target="#modal-valider-{{ $demande->type }}-{{ $demande->id }}" 
                                            class="btn btn-sm btn-success action-validate" 
                                            title="Valider">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        
                                        {{-- BOUTON REFUSER --}}
                                        <button type="button" 
                                            data-toggle="modal" 
                                            data-target="#modal-refuser-{{ $demande->type }}-{{ $demande->id }}" 
                                            class="btn btn-sm btn-danger action-reject" 
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

{{-- MODALS : Inclusion des fichiers de modal --}}
{{-- Filtre pour n'inclure les modaux que pour les demandes 'en attente' afin d'optimiser les ressources --}}
@foreach ($demandes->filter(function ($d) { return strtolower($d->statut) === 'en attente' || strtolower($d->statut) === 'en_attente'; }) as $demande)
    @include('admin.demandes.modals.modal-valider', ['demande' => $demande])
    @include('admin.demandes.modals.modal-refuser', ['demande' => $demande])
@endforeach

@endsection 

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('demandeTable');
    if (!table) return;

    const tbody = table.querySelector('tbody');
    const dynamicActionsContainer = document.getElementById('dynamic-actions-top');

    let firstWaitingRow = null;
    
    // 1. Trouver la première demande "en attente" avec les boutons Valider/Refuser
    tbody.querySelectorAll('tr.table-row-hover').forEach(row => {
        const actionCell = row.cells[5]; 
        
        const validateButton = actionCell.querySelector('.action-validate');
        const rejectButton = actionCell.querySelector('.action-reject');

        if (validateButton && rejectButton && !firstWaitingRow) {
            firstWaitingRow = row;
        }
    });

    if (firstWaitingRow) {
        // Récupérer les boutons originaux
        const originalActionCell = firstWaitingRow.cells[5];
        const originalValidateBtn = originalActionCell.querySelector('.action-validate');
        const originalRejectBtn = originalActionCell.querySelector('.action-reject');

        // 2. Cloner les boutons
        const clonedValidateBtn = originalValidateBtn.cloneNode(true); 
        const clonedRejectBtn = originalRejectBtn.cloneNode(true); 

        // 3. Modifier le style et le texte des boutons clonés
        
        // Bouton Valider
        clonedValidateBtn.classList.remove('btn-sm');
        clonedValidateBtn.innerHTML = '<i class="fas fa-check"></i> Valider (1ère)';
        clonedValidateBtn.style.padding = '8px 15px';

        // Bouton Refuser
        clonedRejectBtn.classList.remove('btn-sm');
        clonedRejectBtn.innerHTML = '<i class="fas fa-times"></i> Refuser (1ère)';
        clonedRejectBtn.style.padding = '8px 15px';
        
        // 4. Injecter les boutons clonés
        dynamicActionsContainer.appendChild(clonedValidateBtn);
        dynamicActionsContainer.appendChild(clonedRejectBtn);
        
    } else {
        // Si aucune demande 'en attente' n'est trouvée, cacher le conteneur
        dynamicActionsContainer.style.display = 'none';
    }
});
</script>
@endpush