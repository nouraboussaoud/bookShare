

<?php $__env->startSection('title', 'Gestion des Avis - Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">📝 Gestion des Avis</h1>
    <a href="<?php echo e(route('admin.reviews.create')); ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Créer un Avis
    </a>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Tous les Avis (<?php echo e($reviews->total()); ?>)</h6>
    </div>
    <div class="card-body">
        <?php if($reviews->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Livre</th>
                            <th>Utilisateur</th>
                            <th>Note</th>
                            <th>Commentaire</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($review->id); ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if($review->book->photo): ?>
                                            <img src="<?php echo e($review->book->photo_url); ?>" 
                                                 class="rounded mr-2" style="width: 40px; height: 40px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-light rounded mr-2 d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-book text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <strong><?php echo e(Str::limit($review->book->title, 30)); ?></strong><br>
                                            <small class="text-muted">par <?php echo e($review->book->author); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong><?php echo e($review->user ? $review->user->name : 'Utilisateur supprimé'); ?></strong><br>
                                        <small class="text-muted"><?php echo e($review->user ? $review->user->email : 'N/A'); ?></small>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-warning">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star<?php echo e($i <= $review->rating ? '' : '-o'); ?>"></i>
                                        <?php endfor; ?>
                                        <span class="ml-1">(<?php echo e($review->rating); ?>/5)</span>
                                    </div>
                                </td>
                                <td>
                                    <?php if($review->comment): ?>
                                        <?php echo e(Str::limit($review->comment, 50)); ?>

                                    <?php else: ?>
                                        <span class="text-muted">Aucun commentaire</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php switch($review->status):
                                        case ('PENDING'): ?>
                                            <span class="badge badge-warning">En attente</span>
                                            <?php break; ?>
                                        <?php case ('APPROVED'): ?>
                                            <span class="badge badge-success">Approuvé</span>
                                            <?php break; ?>
                                        <?php case ('REJECTED'): ?>
                                            <span class="badge badge-danger">Rejeté</span>
                                            <?php break; ?>
                                    <?php endswitch; ?>
                                </td>
                                <td><?php echo e($review->created_at->format('d/m/Y H:i')); ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo e(route('admin.reviews.show', $review)); ?>" 
                                           class="btn btn-sm btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <a href="<?php echo e(route('admin.reviews.edit', $review)); ?>" 
                                           class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <?php if($review->status == 'PENDING'): ?>
                                            <button type="button" class="btn btn-sm btn-success" 
                                                    data-toggle="modal" data-target="#approveModal<?php echo e($review->id); ?>" 
                                                    title="Approuver">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    data-toggle="modal" data-target="#rejectModal<?php echo e($review->id); ?>" 
                                                    title="Rejeter">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        <?php endif; ?>
                                        
                                        <form action="<?php echo e(route('admin.reviews.destroy', $review)); ?>" method="POST" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet avis ?');" 
                                              class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <?php if($review->status == 'PENDING'): ?>
                                <!-- Approve Modal -->
                                <div class="modal fade" id="approveModal<?php echo e($review->id); ?>" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Approuver l'avis</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <form action="<?php echo e(route('admin.reviews.approve', $review)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <div class="modal-body">
                                                    <p>Êtes-vous sûr de vouloir approuver cet avis ?</p>
                                                    <div class="form-group">
                                                        <label>Réponse admin (optionnelle)</label>
                                                        <textarea name="admin_reply" class="form-control" rows="3" 
                                                                  placeholder="Réponse ou commentaire de l'administrateur..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                    <button type="submit" class="btn btn-success">Approuver</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal<?php echo e($review->id); ?>" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Rejeter l'avis</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <form action="<?php echo e(route('admin.reviews.reject', $review)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <div class="modal-body">
                                                    <p>Êtes-vous sûr de vouloir rejeter cet avis ?</p>
                                                    <div class="form-group">
                                                        <label>Raison du rejet <span class="text-danger">*</span></label>
                                                        <textarea name="admin_reply" class="form-control" rows="3" 
                                                                  placeholder="Expliquez pourquoi cet avis est rejeté..." required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                    <button type="submit" class="btn btn-danger">Rejeter</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if($reviews->hasPages()): ?>
                <div class="d-flex justify-content-center mt-4">
                    <?php echo e($reviews->links()); ?>

                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="text-center py-4">
                <i class="fas fa-star fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Aucun avis trouvé</h5>
                <p class="text-muted">Les avis des utilisateurs apparaîtront ici.</p>
                <a href="<?php echo e(route('admin.reviews.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Créer le premier avis
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo\Desktop\bookShare\projet_laravel\resources\views/admin/reviews/index.blade.php ENDPATH**/ ?>