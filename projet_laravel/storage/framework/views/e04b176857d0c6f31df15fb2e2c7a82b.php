

<?php $__env->startSection('title', 'Dashboard Utilisateur'); ?>

<?php $__env->startSection('content'); ?>
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

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">📚 Dashboard BookShare</h1>
            <p class="mb-0 text-gray-600">Bienvenue, <?php echo e(Auth::user()->name); ?> ! Trouvez votre prochain livre à lire</p>
        </div>
        <div>
            <a href="<?php echo e(route('books.create')); ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2">
                <i class="fas fa-plus fa-sm text-white-50"></i> Ajouter un Livre
            </a>
            <a href="<?php echo e(route('books.index')); ?>" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">
                <i class="fas fa-book fa-sm text-white-50"></i> Mes Livres
            </a>
        </div>
    </div>

    <!-- Statistics Row -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Mes Livres</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($myBooksCount); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Livres</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($totalBooksCount); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-books fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Disponibles</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($availableBooksCount); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Communauté</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Active</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Categories Section -->
    <?php if($featuredCategories->count() > 0): ?>
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">📚 Catégories Populaires</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php $__currentLoopData = $featuredCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="<?php echo e(route('user.dashboard', ['category' => $category->id])); ?>" class="text-decoration-none">
                                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid <?php echo e($category->color ?? '#007bff'); ?> !important;">
                                    <div class="card-body text-center">
                                        <i class="<?php echo e($category->icon ?? 'fas fa-book'); ?> fa-2x mb-2" style="color: <?php echo e($category->color ?? '#007bff'); ?>;"></i>
                                        <h6 class="card-title"><?php echo e($category->name); ?></h6>
                                        <p class="card-text text-muted small">Découvrir</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Search and Filter Section -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">🔍 Rechercher des Livres</h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="<?php echo e(route('user.dashboard')); ?>">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="search" class="form-label">Recherche</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="<?php echo e($search); ?>" placeholder="Titre ou auteur...">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="category" class="form-label">Catégorie</label>
                                <select class="form-control" id="category" name="category">
                                    <option value="">Toutes les catégories</option>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($category->id); ?>" <?php echo e($categoryId == $category->id ? 'selected' : ''); ?>>
                                            <?php echo e($category->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="age" class="form-label">Âge maximum</label>
                                <select class="form-control" id="age" name="age">
                                    <option value="">Tous les âges</option>
                                    <option value="6" <?php echo e($ageFilter == '6' ? 'selected' : ''); ?>>6 ans et moins</option>
                                    <option value="9" <?php echo e($ageFilter == '9' ? 'selected' : ''); ?>>9 ans et moins</option>
                                    <option value="12" <?php echo e($ageFilter == '12' ? 'selected' : ''); ?>>12 ans et moins</option>
                                    <option value="15" <?php echo e($ageFilter == '15' ? 'selected' : ''); ?>>15 ans et moins</option>
                                    <option value="18" <?php echo e($ageFilter == '18' ? 'selected' : ''); ?>>18 ans et moins</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-search"></i> Rechercher
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Books -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-success">📖 Livres Disponibles</h6>
                    <?php if($categoryId || $ageFilter || $search): ?>
                        <a href="<?php echo e(route('user.dashboard')); ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-times"></i> Effacer les filtres
                        </a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if($availableBooks->count() > 0): ?>
                        <div class="row">
                            <?php $__currentLoopData = $availableBooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card h-100 shadow-sm book-card">
                                    <?php if($book->photo): ?>
                                        <img src="<?php echo e($book->photo_url); ?>" class="card-img-top" alt="<?php echo e($book->title); ?>" 
                                             style="height: 200px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                             style="height: 200px;">
                                            <i class="fas fa-book fa-3x text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title mb-0">
                                                <a href="<?php echo e(route('books.show', $book)); ?>" class="text-decoration-none">
                                                    <?php echo e(Str::limit($book->title, 25)); ?>

                                                </a>
                                            </h6>
                                            <?php if($book->category): ?>
                                                <span class="badge" style="background-color: <?php echo e($book->category->color); ?>; color: white;">
                                                    <?php echo e($book->category->name); ?>

                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <p class="card-text text-muted mb-2">par <?php echo e($book->author); ?></p>
                                        <p class="card-text">
                                            <small class="text-info">
                                                <i class="fas fa-user"></i> <?php echo e($book->user->name); ?>

                                            </small>
                                        </p>
                                        <div class="mb-3">
                                            <span class="badge badge-info"><?php echo e($book->age_display); ?></span>
                                            <?php if($book->review): ?>
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-star"></i> <?php echo e($book->review->rating); ?>/5
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="btn-group btn-group-sm w-100" role="group">
                                            <a href="<?php echo e(route('books.show', $book)); ?>" 
                                               class="btn btn-outline-info" title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php
                                                $userReview = $book->reviews->where('user_id', Auth::id())->first();
                                            ?>
                                            <?php if(!$userReview): ?>
                                                <a href="<?php echo e(route('reviews.create', ['book_id' => $book->id])); ?>" 
                                                   class="btn btn-outline-primary" title="Donner un avis">
                                                    <i class="fas fa-star"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="<?php echo e(route('reviews.edit', $userReview)); ?>" 
                                                   class="btn btn-outline-warning" title="Modifier mon avis">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            <?php echo e($availableBooks->appends(request()->query())->links()); ?>

                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun livre trouvé</h5>
                            <p class="text-muted">Essayez de modifier vos critères de recherche.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- My Recent Books (if user has books) -->
    <?php if($recentMyBooks->count() > 0): ?>
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">📚 Mes Livres Récents</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php $__currentLoopData = $recentMyBooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="card h-100 shadow-sm book-card">
                                <?php if($book->photo): ?>
                                    <img src="<?php echo e($book->photo_url); ?>" class="card-img-top" alt="<?php echo e($book->title); ?>" 
                                         style="height: 180px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                         style="height: 180px;">
                                        <i class="fas fa-book fa-2x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <a href="<?php echo e(route('books.show', $book)); ?>" class="text-decoration-none">
                                            <?php echo e(Str::limit($book->title, 20)); ?>

                                        </a>
                                    </h6>
                                    <p class="card-text text-muted small">par <?php echo e($book->author); ?></p>
                                    <div class="mb-2">
                                        <span class="badge badge-<?php echo e($book->status == 'AVAILABLE' ? 'success' : 'warning'); ?>">
                                            <?php echo e($book->status); ?>

                                        </span>
                                        <?php if($book->category): ?>
                                            <span class="badge" style="background-color: <?php echo e($book->category->color ?? '#007bff'); ?>; color: white; font-size: 0.7rem;">
                                                <?php echo e($book->category->name); ?>

                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="btn-group btn-group-sm w-100" role="group">
                                        <a href="<?php echo e(route('books.show', $book)); ?>" class="btn btn-outline-info" title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('books.edit', $book)); ?>" class="btn btn-outline-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="text-center">
                        <a href="<?php echo e(route('books.index')); ?>" class="btn btn-primary">Voir tous mes livres</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Welcome message for new users -->
    <?php if($myBooksCount == 0): ?>
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4 border-left-info">
                <div class="card-body text-center py-5">
                    <i class="fas fa-book-reader fa-4x text-info mb-4"></i>
                    <h4 class="text-info">Bienvenue dans BookShare !</h4>
                    <p class="text-muted mb-4">
                        Commencez votre aventure littéraire en ajoutant votre premier livre ou en explorant notre collection.
                    </p>
                    <div>
                        <a href="<?php echo e(route('books.create')); ?>" class="btn btn-info btn-lg mr-2">
                            <i class="fas fa-plus"></i> Ajouter mon premier livre
                        </a>
                        <a href="<?php echo e(route('books.index', ['scope' => 'others'])); ?>" class="btn btn-outline-info btn-lg">
                            <i class="fas fa-book-open"></i> Découvrir les livres
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo\Desktop\bookShare\projet_laravel\resources\views/pages/user-dashboard.blade.php ENDPATH**/ ?>