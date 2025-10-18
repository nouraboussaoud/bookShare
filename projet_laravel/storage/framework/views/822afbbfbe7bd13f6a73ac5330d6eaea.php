

<?php $__env->startSection('title', 'Détails de l\'Avis - Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">📝 Détails de l'Avis #<?php echo e($review->id); ?></h1>
    <div>
        <a href="<?php echo e(route('admin.reviews.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
        <a href="<?php echo e(route('admin.reviews.edit', $review)); ?>" class="btn btn-warning">
            <i class="fas fa-edit"></i> Modifier
        </a>
    </div>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
<?php endif; ?>

<div class="row">
    <!-- Review Details -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Détails de l'Avis</h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>ID:</strong></div>
                    <div class="col-sm-9"><?php echo e($review->id); ?></div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Utilisateur:</strong></div>
                    <div class="col-sm-9">
                        <?php if($review->user): ?>
                            <div class="d-flex align-items-center">
                                <div class="mr-2">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 40px; height: 40px;">
                                        <?php echo e(strtoupper(substr($review->user->name, 0, 1))); ?>

                                    </div>
                                </div>
                                <div>
                                    <strong><?php echo e($review->user->name); ?></strong><br>
                                    <small class="text-muted"><?php echo e($review->user->email); ?></small>
                                </div>
                            </div>
                        <?php else: ?>
                            <span class="text-muted">Utilisateur supprimé</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Note:</strong></div>
                    <div class="col-sm-9">
                        <div class="text-warning">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star<?php echo e($i <= $review->rating ? '' : '-o'); ?>"></i>
                            <?php endfor; ?>
                            <span class="ml-2 font-weight-bold"><?php echo e($review->rating); ?>/5</span>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Commentaire:</strong></div>
                    <div class="col-sm-9">
                        <?php if($review->comment): ?>
                            <div class="bg-light p-3 rounded">
                                <?php echo e($review->comment); ?>

                            </div>
                        <?php else: ?>
                            <span class="text-muted">Aucun commentaire</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Statut:</strong></div>
                    <div class="col-sm-9">
                        <?php switch($review->status):
                            case ('PENDING'): ?>
                                <span class="badge badge-warning badge-lg">En attente</span>
                                <?php break; ?>
                            <?php case ('APPROVED'): ?>
                                <span class="badge badge-success badge-lg">Approuvé</span>
                                <?php break; ?>
                            <?php case ('REJECTED'): ?>
                                <span class="badge badge-danger badge-lg">Rejeté</span>
                                <?php break; ?>
                        <?php endswitch; ?>
                    </div>
                </div>

                <?php if($review->admin_reply): ?>
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Réponse Admin:</strong></div>
                    <div class="col-sm-9">
                        <div class="bg-info text-white p-3 rounded">
                            <i class="fas fa-user-shield"></i> <?php echo e($review->admin_reply); ?>

                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Date de création:</strong></div>
                    <div class="col-sm-9"><?php echo e($review->created_at->format('d/m/Y à H:i')); ?></div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Dernière modification:</strong></div>
                    <div class="col-sm-9"><?php echo e($review->updated_at->format('d/m/Y à H:i')); ?></div>
                </div>
            </div>
        </div>

        <!-- Admin Actions -->
        <?php if($review->status == 'PENDING'): ?>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Actions Administrateur</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-success btn-block" 
                                data-toggle="modal" data-target="#approveModal">
                            <i class="fas fa-check"></i> Approuver l'avis
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-danger btn-block" 
                                data-toggle="modal" data-target="#rejectModal">
                            <i class="fas fa-times"></i> Rejeter l'avis
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Book Details -->
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Livre Concerné</h6>
            </div>
            <div class="card-body">
                <?php if($review->book->photo): ?>
                    <img src="<?php echo e($review->book->photo_url); ?>" class="card-img-top mb-3" 
                         alt="<?php echo e($review->book->title); ?>" style="height: 200px; object-fit: cover;">
                <?php else: ?>
                    <div class="bg-light d-flex align-items-center justify-content-center mb-3" 
                         style="height: 200px;">
                        <i class="fas fa-book fa-3x text-muted"></i>
                    </div>
                <?php endif; ?>

                <h5 class="card-title"><?php echo e($review->book->title); ?></h5>
                <p class="card-text">
                    <strong>Auteur:</strong> <?php echo e($review->book->author); ?><br>
                    <strong>Propriétaire:</strong> <?php echo e($review->book->user->name); ?><br>
                    <?php if($review->book->category): ?>
                        <strong>Catégorie:</strong> 
                        <span class="badge" style="background-color: <?php echo e($review->book->category->color); ?>; color: white;">
                            <?php echo e($review->book->category->name); ?>

                        </span><br>
                    <?php endif; ?>
                    <strong>Statut:</strong> 
                    <span class="badge badge-<?php echo e($review->book->status == 'AVAILABLE' ? 'success' : 'warning'); ?>">
                        <?php echo e($review->book->status); ?>

                    </span>
                </p>

                <a href="<?php echo e(route('books.show', $review->book)); ?>" class="btn btn-primary btn-block">
                    <i class="fas fa-eye"></i> Voir le livre
                </a>
            </div>
        </div>
    </div>
</div>

<?php if($review->status == 'PENDING'): ?>
<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog">
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
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo\Desktop\bookShare\projet_laravel\resources\views/admin/reviews/show.blade.php ENDPATH**/ ?>