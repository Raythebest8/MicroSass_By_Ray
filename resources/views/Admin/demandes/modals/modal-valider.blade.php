{{-- resources/views/admin/demandes/modals/modal-valider.blade.php --}}

<div class="modal fade" id="modal-valider-{{ $demande->type }}-{{ $demande->id }}" tabindex="-1" role="dialog" aria-labelledby="modalValiderLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            
            {{-- EN-TÊTE DU MODAL --}}
            <div class="modal-header bg-success text-white py-3">
                <h5 class="modal-title" id="modalValiderLabel">
                    <i class="fas fa-check-circle mr-2"></i> Confirmation d'Approbation
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form action="{{ route('admin.demandes.approuver', ['type' => $demande->type, 'demandeId' => $demande->id]) }}" method="POST">
                @csrf

                {{-- CORPS DU MODAL --}}
                <div class="modal-body">
                    <p class="mb-4">
                        Vous êtes sur le point d'**approuver** la demande de prêt de 
                        <span class="font-weight-bold text-primary">{{ $demande->user->nom ?? 'Utilisateur Inconnu' }}</span>. 
                        Veuillez renseigner les termes du prêt accordé.
                    </p>
                    
                    {{-- Champs d'approbation requis par AdminDemandeController@approuverDemande --}}
                    
                    {{-- MONTANT ACCORDÉ --}}
                    <div class="form-group mb-3">
                        <label for="montant_accorde-{{ $demande->id }}">Montant accordé (FCFA) :</label>
                        <input 
                            type="number" 
                            step="1000" 
                            name="montant_accorde" 
                            id="montant_accorde-{{ $demande->id }}" 
                            class="form-control" 
                            value="{{ $demande->montant_souhaite ?? '' }}" 
                            placeholder="Ex: 500000"
                            min="0"
                            required
                        >
                    </div>
                    
                    <div class="form-row">
                        
                        {{-- DURÉE --}}
                        <div class="form-group col-md-6 mb-3">
                            <label for="duree_mois-{{ $demande->id }}">Durée (Mois) :</label>
                            <input 
                                type="number" 
                                name="duree_mois" 
                                id="duree_mois-{{ $demande->id }}" 
                                class="form-control" 
                                value="{{ $demande->duree_mois ?? '' }}" 
                                min="1"
                                required
                            >
                        </div>
                        
                        {{-- TAUX D'INTÉRÊT --}}
                        <div class="form-group col-md-6 mb-3">
                            <label for="taux_interet-{{ $demande->id }}">Taux d'intérêt annuel (Ex: 0.10) :</label>
                            <input 
                                type="number" 
                                step="any" {{-- Permet n'importe quel pas pour plus de flexibilité --}}
                                name="taux_interet" 
                                id="taux_interet-{{ $demande->id }}" 
                                class="form-control" 
                                placeholder="Ex: 0.10" 
                                min="0"
                                required
                            >
                        </div>
                    </div>

                    {{-- COMMENTAIRE --}}
                    <div class="form-group mt-2">
                        <label for="commentaire-approuver-{{ $demande->id }}">Commentaire d'approbation (Optionnel) :</label>
                        <textarea name="commentaire_approbation" id="commentaire-approuver-{{ $demande->id }}" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                
                {{-- FOOTER DU MODAL --}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check-circle mr-1"></i> Approuver la Demande
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>