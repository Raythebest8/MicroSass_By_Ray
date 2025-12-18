@extends('layouts.app')

@section('content')


<style>
    /* CSS du fichier index.blade.php doit être inclus ici, ainsi que les styles additionnels ci-dessous */

    .type-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: normal;
        margin-left: 15px;
        background-color: #f0f0f0;
        color: #555;
    }

    .type-particulier {
        border: 1px solid #007bff;
        color: #007bff;
        background-color: #e6f0ff;
    }

    .type-entreprise {
        border: 1px solid #28a745;
        color: #28a745;
        background-color: #eaf7ed;
    }

    .btn-secondary-outline {
        padding: 8px 15px;
        border: 1px solid var(--muted-color);
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        color: var(--muted-color);
        background-color: transparent;
        transition: all 0.2s;
    }

    .btn-secondary-outline:hover {
        background-color: var(--muted-color);
        color: white;
    }

    /* --- Details Card Structure --- */
    .card-admin-details {
        background: white;
        border-radius: 8px;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }

    .card-header-details {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 30px;
        border-bottom: 1px solid var(--border-color);
    }

    /* User Info Styling */
    .user-info-large {
        display: flex;
        align-items: center;
    }

    .avatar-lg {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        margin-right: 15px;
        object-fit: cover;
        border: 2px solid var(--primary-color);
    }

    .user-name-lg {
        font-size: 1.5rem;
        margin: 0;
        color: var(--text-color);
    }

    .user-email-lg {
        font-size: 0.9rem;
        color: var(--muted-color);
        margin: 0;
    }

    /* Status Box */
    .status-box {
        padding: 10px 15px;
        border-radius: 4px;
        font-weight: bold;
        font-size: 14px;
    }

    .status-en_attente {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffeeba;
    }

    .status-validee {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .status-rejetee {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .status-label {
        text-transform: uppercase;
    }

    /* Body Grid */
    .card-body-grid {
        padding: 30px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
    }

    .section-box {
        background-color: var(--bg-light);
        padding: 20px;
        border-radius: 6px;
        border-left: 5px solid var(--primary-color);
    }

    .full-width {
        grid-column: 1 / -1;
        /* Permet à cette section de prendre toute la largeur */
        border-left: 5px solid #6c757d;
    }

    .section-title {
        font-size: 1.1rem;
        color: var(--primary-color);
        margin-bottom: 15px;
        border-bottom: 1px dotted var(--border-color);
        padding-bottom: 5px;
    }

    .icon-primary {
        margin-right: 8px;
        color: var(--primary-color);
    }

    .detail-group {
        margin-bottom: 12px;
    }

    .detail-group label {
        display: block;
        font-size: 0.9rem;
        color: var(--muted-color);
        font-weight: bold;
    }

    .detail-value {
        font-size: 1rem;
        margin-top: 2px;
        margin-bottom: 0;
        color: var(--text-color);
    }

    .value-primary {
        font-size: 1.2rem;
        font-weight: bold;
        color: var(--success-color);
    }

    /* Document List */
    .document-list {
        list-style: none;
        padding: 0;
        margin-top: 15px;
    }

    .document-list li {
        padding: 8px 0;
        border-bottom: 1px dotted #f0f0f0;
        color: var(--text-color);
    }

    .action-link {
        color: var(--primary-color);
        text-decoration: underline;
        cursor: pointer;
        font-size: 0.9em;
        margin-left: 10px;
    }

    .text-success {
        color: var(--success-color);
    }

    /* --- Footer Actions --- */
    .card-footer-actions {
        padding: 20px 30px;
        border-top: 1px solid var(--border-color);
        background-color: #fcfcfc;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .action-text {
        font-weight: bold;
        margin: 0;
    }

    .btn-success-lg,
    .btn-danger-lg {
        padding: 10px 20px;
        font-size: 16px;
        font-weight: bold;
        border-radius: 6px;
        text-decoration: none;
        transition: opacity 0.2s;
    }

    .btn-success-lg {
        background-color: var(--success-color);
        color: white;
    }

    .btn-danger-lg {
        background-color: var(--danger-color);
        color: white;
    }

    .btn-success-lg:hover,
    .btn-danger-lg:hover {
        opacity: 0.9;
    }

    .text-muted-small {
        font-size: 0.9rem;
        color: var(--muted-color);
    }

    @media (max-width: 768px) {
        .card-header-details {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .card-body-grid {
            grid-template-columns: 1fr;
        }

        .card-footer-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .action-text {
            text-align: center;
            margin-bottom: 10px;
        }
    }
</style>

<div class="container">
    <h2 class="page-title">
        Détails de la Demande de Prêt
        <span class="type-badge type-{{ $demande->type }}">{{ ucfirst($demande->type) }}</span>
    </h2>

    {{-- Bouton de retour --}}
    <div style="margin-bottom: 20px;">
        <a href="{{ route('admin.demandes.index') }}" class="btn btn-secondary-outline">
            <i class="fas fa-arrow-left"></i> Retour aux Demandes
        </a>
    </div>

    <div class="card-admin-details">

        {{-- En-tête de la Demande --}}
        <div class="card-header-details">
            <div class="user-info-large">
                <img src="{{ asset('storage/' . ($demande->user->image_path ?? 'default/default-photo.png')) }}" class="avatar-lg" alt="Avatar">
                <div>
                    <h3 class="user-name-lg">{{ $demande->user->nom ?? 'Utilisateur Inconnu' }}</h3>
                    <p class="user-email-lg">{{ $demande->user->email ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="status-box status-{{ strtolower(str_replace(' ', '_', $demande->statut)) }}">
                Statut :
                <span class="status-label">{{ str_replace('_', ' ', $demande->statut) }}</span>
            </div>
        </div>

        {{-- Corps - Informations Principales --}}
        <div class="card-body-grid">

            {{-- Section 1: Informations sur le Prêt --}}
            <div class="section-box">
                <h4 class="section-title"><i class="fas fa-wallet icon-primary"></i> Montant et Durée</h4>
                <div class="detail-group">
                    <label>Montant Sollicité :</label>
                    <p class="detail-value value-primary">{{ number_format($demande->montant_souhaite ?? 0, 0, ',', ' ') }} FCFA</p>
                </div>
                <div class="detail-group">
                    <label>Durée de Remboursement :</label>
                    <p class="detail-value">{{ $demande->duree_mois ?? 'N/A' }} mois</p>
                </div>
                <div class="detail-group">
                    <label>Date de Soumission :</label>
                    <p class="detail-value">{{ $demande->created_at->isoFormat('LLLL') }}</p>
                </div>
                <div class="detail-group">
                    <label>Motif du Prêt :</label>
                    <p class="detail-value">{{ $demande->motif ?? 'Non spécifié' }}</p>
                </div>
            </div>

            {{-- Section 2: Informations Spécifiques au Modèle --}}
            <div class="section-box">
                <h4 class="section-title"><i class="fas fa-info-circle icon-primary"></i> Détails {{ $demande->type == 'particulier' ? 'Personnel' : 'Entreprise' }}</h4>

                @if ($demande->type === 'particulier')
                <div class="detail-group">
                    <label>Profession :</label>
                    <p class="detail-value">{{ $demande->secteur_activite ?? 'N/A' }}</p>
                </div>
                <div class="detail-group">
                    <label>Revenu Mensuel :</label>
                    <p class="detail-value">{{ number_format($demande->revenu_mensuel ?? 0, 0, ',', ' ') }} FCFA</p>
                </div>
                <div class="detail-group">
                    <label>Statut Matrimonial :</label>
                    <p class="detail-value">{{ ucfirst($demande->statut_matrimonial ?? 'N/A') }}</p>
                </div>
                @else
                <div class="detail-group">
                    <label>Nom de l'Entreprise :</label>
                    <p class="detail-value">{{ $demande->nom_entreprise ?? 'N/A' }}</p>
                </div>
                <div class="detail-group">
                    <label>Secteur d'Activité :</label>
                    <p class="detail-value">{{ $demande->secteur_activite ?? 'N/A' }}</p>
                </div>
                <div class="detail-group">
                    <label>Chiffre d'Affaires Annuel :</label>
                    <p class="detail-value">{{ number_format($demande->ca_annuel ?? 0, 0, ',', ' ') }} FCFA</p>
                </div>
                @endif
            </div>

            {{-- Section 3: Documents --}}
            <div class="section-box full-width">
                <h4 class="section-title"><i class="fas fa-file-alt icon-primary"></i> Documents Soumis</h4>

                <ul class="document-list">

                    @foreach ($demande->documents as $document)
                    <div class="d-flex align-items-center justify-content-between border-bottom py-2">

                        <div>
                            <strong>{{ $document->nom_afficher ?? 'Document' }}</strong><br>
                            <small class="text-muted">{{ $document->mime_type }}</small>
                        </div>

                        <div class="actions">
                            {{-- Aperçu --}}
                            <a href="{{ route('admin.documents.preview', $document->id) }}"
                                target="_blank"
                                class="text-primary me-3"
                                title="Voir le document">
                                <i class="fas fa-eye"></i> Voir
                            </a>

                            {{-- Télécharger --}}
                            <a href="{{ route('admin.documents.download', $document->id) }}"
                                class="text-success"
                                title="Télécharger">
                                <i class="fas fa-download"></i> Télécharger
                            </a>
                        </div>

                    </div>
                    @endforeach


                </ul>
            </div>

        </div>

        {{-- Pied de page - Actions --}}
        <div class="card-footer-actions " style="background-color: #81828567;">
            @if (strtolower(str_replace(' ', '_', $demande->statut)) === 'en_attente')

            <p class="action-text">Actions disponibles :</p>

            {{-- Bouton Valider --}}
            <a href="#modal-valider-{{ $demande->id }}" data-toggle="modal" class="btn btn-success-lg" title="Valider">
                <i class="fas fa-check"></i> Valider la Demande
            </a>

            {{-- Bouton Refuser --}}
            <a href="#modal-refuser-{{ $demande->id }}" data-toggle="modal" class="btn btn-danger-lg" title="Refuser">
                <i class="fas fa-times"></i> Refuser la Demande
            </a>
            @else
            <p class="action-text text-muted-small">La demande est déjà **{{ str_replace('_', ' ', $demande->statut) }}**. Aucune action supplémentaire n'est requise.</p>
            @endif
        </div>

    </div>
</div>

{{-- MODALS (Doivent être inclus ici pour fonctionner) --}}
@include('admin.demandes.modals.modal-valider', ['demande' => $demande])
@include('admin.demandes.modals.modal-refuser', ['demande' => $demande])

@endsection