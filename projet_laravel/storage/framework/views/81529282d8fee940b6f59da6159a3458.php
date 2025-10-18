<?php $__env->startSection('title', 'Détails de la Progression'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="mb-4">
        <a href="<?php echo e(route('reading-progress.index')); ?>" class="btn btn-outline-secondary mb-3">
            <i class="fas fa-arrow-left me-2"></i>Retour à mes lectures
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Colonne gauche: Info du livre -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <?php if($readingProgress->book->photo): ?>
                        <img src="<?php echo e(asset('storage/' . $readingProgress->book->photo)); ?>" 
                             alt="<?php echo e($readingProgress->book->title); ?>" 
                             class="img-fluid rounded mb-3"
                             style="max-height: 300px; object-fit: cover;">
                    <?php else: ?>
                        <div class="bg-light rounded mb-3 d-flex align-items-center justify-content-center" 
                             style="height: 300px;">
                            <i class="fas fa-book fa-5x text-muted"></i>
                        </div>
                    <?php endif; ?>

                    <h3 class="h5 mb-2"><?php echo e($readingProgress->book->title); ?></h3>
                    <p class="text-muted mb-3">par <?php echo e($readingProgress->book->author); ?></p>
                    
                    <?php if($readingProgress->book->category): ?>
                        <span class="badge bg-secondary mb-3"><?php echo e($readingProgress->book->category->name); ?></span>
                    <?php endif; ?>

                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('books.show', $readingProgress->book)); ?>" class="btn btn-outline-primary">
                            <i class="fas fa-book me-2"></i>Voir le livre
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne droite: Progression -->
        <div class="col-lg-8">
            <!-- Statut et Actions -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">
                            <span class="badge bg-<?php echo e($readingProgress->status === 'reading' ? 'primary' : ($readingProgress->status === 'completed' ? 'success' : ($readingProgress->status === 'to_read' ? 'info' : 'secondary'))); ?> fs-6">
                                <?php echo e($readingProgress->status_label); ?>

                            </span>
                        </h4>
                        
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-cog me-1"></i>Actions
                            </button>
                            <ul class="dropdown-menu">
                                <?php if($readingProgress->status !== 'reading'): ?>
                                    <li>
                                        <form action="<?php echo e(route('books.startReading', $readingProgress->book)); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-play me-2"></i>Commencer la lecture
                                            </button>
                                        </form>
                                    </li>
                                <?php endif; ?>
                                <?php if($readingProgress->status === 'reading'): ?>
                                    <li>
                                        <form action="<?php echo e(route('reading-progress.complete', $readingProgress)); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-check me-2"></i>Marquer comme terminé
                                            </button>
                                        </form>
                                    </li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="<?php echo e(route('reading-progress.destroy', $readingProgress)); ?>" method="POST" 
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette progression ?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-trash me-2"></i>Supprimer
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Barre de progression -->
                    <?php if($readingProgress->total_pages): ?>
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-bold">Progression</span>
                                <span class="text-primary fw-bold"><?php echo e($readingProgress->progress_percentage); ?>%</span>
                            </div>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-success" 
                                     role="progressbar" 
                                     style="width: <?php echo e($readingProgress->progress_percentage); ?>%">
                                    <?php echo e($readingProgress->current_page); ?> / <?php echo e($readingProgress->total_pages); ?>

                                </div>
                            </div>
                            <small class="text-muted"><?php echo e($readingProgress->pages_remaining); ?> pages restantes</small>
                        </div>
                    <?php endif; ?>

                    <!-- Statistiques -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded text-center">
                                <i class="fas fa-book-open text-primary fa-2x mb-2"></i>
                                <div class="fw-bold"><?php echo e($readingProgress->current_page); ?></div>
                                <small class="text-muted">Pages lues</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded text-center">
                                <i class="fas fa-clock text-success fa-2x mb-2"></i>
                                <div class="fw-bold"><?php echo e($readingProgress->formatted_reading_time); ?></div>
                                <small class="text-muted">Temps de lecture</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded text-center">
                                <i class="fas fa-calendar text-info fa-2x mb-2"></i>
                                <div class="fw-bold">
                                    <?php if($readingProgress->started_at): ?>
                                        <?php echo e($readingProgress->started_at->diffForHumans()); ?>

                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </div>
                                <small class="text-muted">Démarré</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaire de mise à jour -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Mettre à jour la progression</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('reading-progress.update', $readingProgress)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="current_page" class="form-label">Page actuelle</label>
                                <input type="number" class="form-control" id="current_page" name="current_page" 
                                       value="<?php echo e($readingProgress->current_page); ?>" min="0" 
                                       max="<?php echo e($readingProgress->total_pages ?? 9999); ?>">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="total_pages" class="form-label">Total de pages</label>
                                <input type="number" class="form-control" id="total_pages" name="total_pages" 
                                       value="<?php echo e($readingProgress->total_pages); ?>" min="1">
                            </div>

                            <div class="col-md-6">
                                <label for="status" class="form-label">Statut</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="to_read" <?php echo e($readingProgress->status === 'to_read' ? 'selected' : ''); ?>>À lire</option>
                                    <option value="reading" <?php echo e($readingProgress->status === 'reading' ? 'selected' : ''); ?>>En cours</option>
                                    <option value="completed" <?php echo e($readingProgress->status === 'completed' ? 'selected' : ''); ?>>Terminé</option>
                                    <option value="abandoned" <?php echo e($readingProgress->status === 'abandoned' ? 'selected' : ''); ?>>Abandonné</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="reading_time_minutes" class="form-label">Temps de lecture (minutes)</label>
                                <input type="number" class="form-control" id="reading_time_minutes" name="reading_time_minutes" 
                                       value="<?php echo e($readingProgress->reading_time_minutes); ?>" min="0">
                            </div>

                            <div class="col-12">
                                <label for="notes" class="form-label">Notes personnelles</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" 
                                          placeholder="Vos impressions, citations préférées..."><?php echo e($readingProgress->notes); ?></textarea>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Enregistrer les modifications
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Notes -->
            <?php if($readingProgress->notes): ?>
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-sticky-note me-2"></i>Mes notes</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0"><?php echo e($readingProgress->notes); ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo\Desktop\bookShare\projet_laravel\resources\views/reading-progress/show.blade.php ENDPATH**/ ?>