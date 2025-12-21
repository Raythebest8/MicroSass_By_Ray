{{-- resources/views/admin/demandes/modals/modal-refuser.blade.php --}}

<div class="modal fade" id="modal-refuser-{{ $demande->type }}-{{ $demande->id }}" tabindex="-1" role="dialog" aria-labelledby="modalRefuserLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            
            {{-- EN-TÊTE DU MODAL --}}
            <div class="modal-header bg-danger text-white py-3">
                <h5 class="modal-title" id="modalRefuserLabel">
                    <i class="fas fa-times-circle mr-2"></i> Confirmation de Rejet
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form action="{{ route('admin.demandes.rejeter', ['type' => $demande->type, 'demandeId' => $demande->id]) }}" method="POST">
                @csrf

                {{-- CORPS DU MODAL --}}
                <div class="modal-body">
                    <p class="mb-4">
                        Êtes-vous sûr de vouloir **rejeter** définitivement la demande de prêt de 
                        <span class="font-weight-bold text-danger">{{ $demande->user->nom ?? 'Utilisateur Inconnu' }}</span> ?
                    </p>
                    <div class="form-group mt-3">
                        @error('raison_rejet')
        <div class="alert alert-danger py-1 px-2 small">{{ $message }}</div>
    @enderror
                        <label for="raison-rejeter-{{ $demande->type }}-{{ $demande->id }}">Raison du rejet (Obligatoire) :</label>
                        <textarea 
                            name="raison_rejet" 
                            id="raison-rejeter-{{ $demande->type }}-{{ $demande->id }}" 
                            class="form-control" 
                            rows="4" 
                            placeholder="Veuillez fournir un motif clair pour informer l'utilisateur."
                            required
                        >{{ old('raison_rejet') }}</textarea>
                    </div>
                </div>
                
                {{-- FOOTER DU MODAL --}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-ban mr-1"></i> Confirmer le Rejet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>