<?php $__env->startSection('title', 'Mes Avis'); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"> Mes Avis sur les Livres</h1>
        <p class="mb-0 text-gray-600">Gérez les avis et évaluations des livres</p>
    </div>
    <a href="<?php echo e(route('reviews.create')); ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Nouvel Avis
    </a>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo e(session('error')); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <!-- Reviews Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Avis</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Livre</th>
                            <th>Auteur du Livre</th>
                            <th>Catégorie</th>
                            <th>Note</th>
                            <th>Commentaire</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <strong><?php echo e($review->book->title); ?></strong>
                                    <br>
                                    <small class="text-muted">par <?php echo e($review->book->user->name); ?></small>
                                </td>
                                <td><?php echo e($review->book->author); ?></td>
                                <td>
                                    <?php if($review->book->category): ?>
                                        <span class="badge" style="background-color: <?php echo e($review->book->category->color); ?>; color: white;">
                                            <?php echo e($review->book->category->name); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">Aucune</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="rating">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <?php if($i <= $review->rating): ?>
                                                <i class="fas fa-star text-warning"></i>
                                            <?php else: ?>
                                                <i class="far fa-star text-muted"></i>
                                            <?php endif; ?>
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
                                <td><?php echo e($review->created_at->format('d/m/Y')); ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo e(route('reviews.show', $review)); ?>" class="btn btn-sm btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <?php if($review->user_id == Auth::id()): ?>
                                            <a href="<?php echo e(route('reviews.edit', $review)); ?>" class="btn btn-sm btn-warning" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <form action="<?php echo e(route('reviews.destroy', $review)); ?>" method="POST" 
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre avis ?');" 
                                                  class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>

                            <?php if(Auth::user()->isAdmin() && $review->status == 'PENDING'): ?>
                                <!-- Approve Modal -->
                                <div class="modal fade" id="approveModal<?php echo e($review->id); ?>" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="<?php echo e(route('admin.reviews.approve', $review)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Approuver l'avis</h5>
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Êtes-vous sûr de vouloir approuver cet avis ?</p>
                                                    <div class="form-group">
                                                        <label for="admin_reply">Réponse de l'administrateur (optionnel)</label>
                                                        <textarea class="form-control" name="admin_reply" rows="3"></textarea>
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
                                            <form action="<?php echo e(route('admin.reviews.reject', $review)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Rejeter l'avis</h5>
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Pourquoi rejetez-vous cet avis ?</p>
                                                    <div class="form-group">
                                                        <label for="admin_reply">Raison du rejet <span class="text-danger">*</span></label>
                                                        <textarea class="form-control" name="admin_reply" rows="3" required></textarea>
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
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <?php if(Auth::user()->isAdmin()): ?>
                                        Aucun avis trouvé dans le système.
                                    <?php else: ?>
                                        <div>
                                            <i class="fas fa-star fa-3x mb-3 text-muted"></i><br>
                                            <strong>Vous n'avez pas encore créé d'avis.</strong><br>
                                            <small>Parcourez les livres disponibles et laissez votre premier avis !</small><br>
                                            <a href="<?php echo e(route('user.dashboard')); ?>" class="btn btn-primary btn-sm mt-2">
                                                <i class="fas fa-book"></i> Voir les livres
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if($reviews->hasPages()): ?>
                <div class="d-flex justify-content-center">
                    <?php echo e($reviews->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo\Desktop\bookShare\projet_laravel\resources\views/reviews/index.blade.php ENDPATH**/ ?>