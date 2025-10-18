<div class="col-md-6 col-lg-4">
    <div class="card h-100 shadow-sm hover-shadow">
        <div class="card-body">
            <!-- Book Info -->
            <div class="d-flex mb-3">
                <?php if($progress->book->photo): ?>
                    <img src="<?php echo e(asset('storage/' . $progress->book->photo)); ?>" 
                         alt="<?php echo e($progress->book->title); ?>" 
                         class="rounded me-3"
                         style="width: 60px; height: 90px; object-fit: cover;">
                <?php else: ?>
                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                         style="width: 60px; height: 90px;">
                        <i class="fas fa-book fa-2x text-muted"></i>
                    </div>
                <?php endif; ?>
                
                <div class="flex-grow-1">
                    <h5 class="card-title mb-1" style="font-size: 1rem;">
                        <a href="<?php echo e(route('reading-progress.show', $progress)); ?>" class="text-decoration-none text-dark">
                            <?php echo e(Str::limit($progress->book->title, 40)); ?>

                        </a>
                    </h5>
                    <p class="text-muted small mb-1"><?php echo e($progress->book->author); ?></p>
                    <span class="badge bg-<?php echo e($progress->status === 'reading' ? 'primary' : ($progress->status === 'completed' ? 'success' : ($progress->status === 'to_read' ? 'info' : 'secondary'))); ?>">
                        <?php echo e($progress->status_label); ?>

                    </span>
                </div>
            </div>

            <!-- Progress Bar (only for reading/completed) -->
            <?php if($progress->total_pages && in_array($progress->status, ['reading', 'completed'])): ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small class="text-muted">Progression</small>
                        <small class="fw-bold"><?php echo e($progress->progress_percentage); ?>%</small>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" 
                             role="progressbar" 
                             style="width: <?php echo e($progress->progress_percentage); ?>%"
                             aria-valuenow="<?php echo e($progress->progress_percentage); ?>" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                        </div>
                    </div>
                    <small class="text-muted"><?php echo e($progress->current_page); ?> / <?php echo e($progress->total_pages); ?> pages</small>
                </div>
            <?php endif; ?>

            <!-- Stats -->
            <div class="d-flex justify-content-between text-muted small mb-3">
                <?php if($progress->reading_time_minutes > 0): ?>
                    <div>
                        <i class="fas fa-clock me-1"></i><?php echo e($progress->formatted_reading_time); ?>

                    </div>
                <?php endif; ?>
                <?php if($progress->started_at): ?>
                    <div>
                        <i class="fas fa-calendar me-1"></i><?php echo e($progress->started_at->format('d/m/Y')); ?>

                    </div>
                <?php endif; ?>
            </div>

            <!-- Actions -->
            <div class="d-flex gap-2">
                <a href="<?php echo e(route('reading-progress.show', $progress)); ?>" 
                   class="btn btn-sm btn-outline-primary flex-grow-1">
                    <i class="fas fa-eye me-1"></i>Détails
                </a>
                
                <?php if($progress->status === 'to_read'): ?>
                    <form action="<?php echo e(route('books.startReading', $progress->book)); ?>" method="POST" class="flex-grow-1">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-sm btn-success w-100">
                            <i class="fas fa-play me-1"></i>Commencer
                        </button>
                    </form>
                <?php elseif($progress->status === 'reading'): ?>
                    <form action="<?php echo e(route('reading-progress.complete', $progress)); ?>" method="POST" class="flex-grow-1">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-sm btn-success w-100">
                            <i class="fas fa-check me-1"></i>Terminer
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.hover-shadow {
    transition: all 0.3s ease;
}
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>
<?php /**PATH C:\Users\Lenovo\Desktop\bookShare\projet_laravel\resources\views/reading-progress/partials/progress-card.blade.php ENDPATH**/ ?>