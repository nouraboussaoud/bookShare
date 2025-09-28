<!-- Report Modal Component -->
<div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="reportModalLabel">
                    <i class="fas fa-flag mr-2"></i>Créer un signalement
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="reportForm" method="POST" action="{{ route('reports.store') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="exchange_id" id="modal_exchange_id">
                    <input type="hidden" name="reported_user_id" id="modal_reported_user_id">
                    
                    <div class="form-group">
                        <label for="modal_type" class="font-weight-bold">Type de signalement *</label>
                        <select name="type" id="modal_type" class="form-control" required>
                            <option value="">Sélectionner un type</option>
                            <option value="CONFLIT_ECHANGE">Conflit d'échange</option>
                            <option value="COMPORTEMENT">Comportement inapproprié</option>
                        </select>
                        <small class="form-text text-muted">
                            Choisissez "Conflit d'échange" pour les problèmes liés à un échange spécifique, 
                            ou "Comportement inapproprié" pour signaler le comportement d'un utilisateur.
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="modal_description" class="font-weight-bold">Description du problème *</label>
                        <textarea name="description" id="modal_description" class="form-control" rows="5" 
                                  placeholder="Décrivez en détail le problème rencontré..." required></textarea>
                        <small class="form-text text-muted">
                            Soyez aussi précis que possible pour nous aider à traiter votre signalement.
                        </small>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Information :</strong> Votre signalement sera examiné par notre équipe de modération. 
                        Nous vous contacterons si des informations supplémentaires sont nécessaires.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-flag mr-1"></i>Envoyer le signalement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openReportModal(type, targetId = null, targetType = null) {
    // Reset form
    document.getElementById('reportForm').reset();
    document.getElementById('modal_exchange_id').value = '';
    document.getElementById('modal_reported_user_id').value = '';
    
    // Set values based on type
    if (type === 'exchange' && targetId) {
        document.getElementById('modal_exchange_id').value = targetId;
        document.getElementById('modal_type').value = 'CONFLIT_ECHANGE';
        // Désactiver visuellement mais garder le champ fonctionnel
        document.getElementById('modal_type').style.backgroundColor = '#e9ecef';
        document.getElementById('modal_type').style.pointerEvents = 'none';
    } else if (type === 'user' && targetId) {
        document.getElementById('modal_reported_user_id').value = targetId;
        document.getElementById('modal_type').value = 'COMPORTEMENT';
        // Désactiver visuellement mais garder le champ fonctionnel
        document.getElementById('modal_type').style.backgroundColor = '#e9ecef';
        document.getElementById('modal_type').style.pointerEvents = 'none';
    } else {
        document.getElementById('modal_type').style.backgroundColor = '';
        document.getElementById('modal_type').style.pointerEvents = '';
    }
    
    // Show modal
    $('#reportModal').modal('show');
}

// Handle form submission
document.getElementById('reportForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Disable submit button to prevent double submission
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Envoi en cours...';
    
    // Submit form
    fetch(this.action, {
        method: 'POST',
        body: new FormData(this),
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            $('#reportModal').modal('hide');
            
            // Show success alert
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show';
            alert.innerHTML = `
                <i class="fas fa-check-circle mr-2"></i>
                <strong>Signalement envoyé !</strong> Votre signalement a été transmis à notre équipe de modération.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            `;
            
            // Insert alert at top of page
            const container = document.querySelector('.container-fluid') || document.body;
            container.insertBefore(alert, container.firstChild);
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                alert.remove();
            }, 5000);
        } else {
            // Show error message
            alert('Erreur lors de l\'envoi du signalement. Veuillez réessayer.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de l\'envoi du signalement. Veuillez réessayer.');
    })
    .finally(() => {
        // Re-enable submit button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});
</script>