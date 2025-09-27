<?php $__env->startSection('title', 'Mes Livres'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $isAdmin = auth()->check() ? auth()->user()->isAdmin() : false;
    $isOthers = isset($scope) && $scope == 'others';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 text-gray-800 mb-2">
            <?php if(auth()->guard()->check()): ?>
                <?php if($isAdmin): ?>
                    <?php echo e($isOthers ? 'Livres de la communauté' : 'Tous les livres'); ?>

                <?php else: ?>
                    <?php echo e($isOthers ? 'Livres de la communauté' : 'Mes livres'); ?>

                <?php endif; ?>
            <?php endif; ?>
        </h1>
        <?php if(auth()->guard()->check()): ?>
            <div class="btn-group" role="group">
                <?php if(!$isAdmin): ?>
                    <a href="<?php echo e(route('books.index')); ?>" class="btn btn-sm <?php echo e($isOthers ? 'btn-outline-secondary' : 'btn-secondary'); ?>">Mes livres</a>
                <?php endif; ?>
                <a href="<?php echo e(route('books.index', ['scope' => 'others'])); ?>" class="btn btn-sm <?php echo e($isOthers ? 'btn-secondary' : 'btn-outline-secondary'); ?>">Livres de la communauté</a>
                <?php if($isAdmin): ?>
                    <a href="<?php echo e(route('books.index')); ?>" class="btn btn-sm btn-outline-secondary">Tous</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    <a href="<?php echo e(route('books.create')); ?>" class="btn btn-primary"><i class="fas fa-plus mr-1"></i> Ajouter un livre</a>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
<?php endif; ?>

<div class="row">
    <?php $__empty_1 = true; $__currentLoopData = $books; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card h-100 shadow-sm book-card">
                <?php if($book->photo): ?>
                    <img src="<?php echo e($book->photo_url); ?>" class="card-img-top" alt="<?php echo e($book->title); ?>" 
                         style="height: 250px; object-fit: cover;">
                <?php else: ?>
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                         style="height: 250px;">
                        <i class="fas fa-book fa-4x text-muted"></i>
                    </div>
                <?php endif; ?>
                
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="card-title mb-0">
                            <a href="<?php echo e(route('books.show', $book)); ?>" class="text-decoration-none">
                                <?php echo e(Str::limit($book->title, 20)); ?>

                            </a>
                        </h6>
                        <?php if($book->category): ?>
                            <span class="badge" style="background-color: <?php echo e($book->category->color); ?>; color: white; font-size: 0.7rem;">
                                <?php echo e($book->category->name); ?>

                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <p class="card-text text-muted mb-2 small">par <?php echo e($book->author); ?></p>
                    
                    <div class="mb-2">
                        <span class="badge badge-<?php echo e($book->status == 'AVAILABLE' ? 'success' : 'warning'); ?>">
                            <?php echo e($book->status); ?>

                        </span>
                        <span class="badge badge-info"><?php echo e($book->age_display); ?></span>
                    </div>
                    
                    <p class="card-text small text-info mb-2">
                        <i class="fas fa-user"></i> <?php echo e($book->user?->name ?? 'N/A'); ?>

                    </p>
                    
                    <?php if($book->description): ?>
                        <p class="card-text small text-muted mb-3">
                            <?php echo e(Str::limit($book->description, 60)); ?>

                        </p>
                    <?php endif; ?>
                    
                    <div class="mt-auto">
                        <div class="btn-group w-100" role="group">
                            <a href="<?php echo e(route('books.show', $book)); ?>" class="btn btn-sm btn-outline-info" title="Voir détails">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                            <?php if(auth()->guard()->check()): ?>
                                <?php if(auth()->user()->isAdmin() || auth()->id() == $book->user_id): ?>
                                    <a href="<?php echo e(route('books.edit', $book)); ?>" class="btn btn-sm btn-outline-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <form action="<?php echo e(route('books.toggleStatus', $book)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-secondary" title="Changer le statut">
                                            <i class="fas fa-sync"></i>
                                        </button>
                                    </form>
                                    
                                    <form action="<?php echo e(route('books.destroy', $book)); ?>" method="POST" 
                                          onsubmit="return confirm('Supprimer ce livre ?');" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <?php
                                        $userReview = $book->reviews->where('user_id', Auth::id())->first();
                                    ?>
                                    <?php if(!$userReview): ?>
                                        <a href="<?php echo e(route('reviews.create', ['book_id' => $book->id])); ?>" 
                                           class="btn btn-sm btn-outline-primary" title="Donner un avis">
                                            <i class="fas fa-star"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?php echo e(route('reviews.edit', $userReview)); ?>" 
                                           class="btn btn-sm btn-outline-warning" title="Modifier mon avis">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="fas fa-book-open fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucun livre trouvé</h5>
                    <p class="text-muted">
                        <?php if($isOthers): ?>
                            La communauté n'a pas encore partagé de livres.
                        <?php else: ?>
                            Vous n'avez pas encore ajouté de livres.
                        <?php endif; ?>
                    </p>
                    <a href="<?php echo e(route('books.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Ajouter votre premier livre
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Pagination -->
<?php if($books->hasPages()): ?>
    <div class="d-flex justify-content-center mt-4">
        <?php echo e($books->links()); ?>

    </div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\abous\OneDrive\Bureau\bookShare\bookShare\projet_laravel\resources\views/books/index.blade.php ENDPATH**/ ?>