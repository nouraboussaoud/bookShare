<?php $__env->startSection('title', 'Mes Livres'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $isAdmin = auth()->check() ? auth()->user()->isAdmin() : false;
    $isOthers = isset($scope) && $scope == 'others';
    $myBooksCount = auth()->check() ? \App\Models\Book::where('user_id', auth()->id())->count() : 0;
    $totalBooks = \App\Models\Book::count();
    $availableBooks = \App\Models\Book::where('status', 'available')->count();
    $reservedBooks = \App\Models\Book::where('status', 'reserved')->count();
?>

<!-- Animated Stars Background -->
<div class="stars-background" id="starsContainer"></div>

<!-- Header "Welcome to ShareBooks" -->
<div class="mb-5" style="position: relative; z-index: 1;">
    
    
    <div class="card smooth" style="overflow: hidden; border-radius: 0px;">
        <div class="row g-0">
            <!-- Image à gauche -->
            <div class="col-md-5 d-none d-md-block" style="background: #f5f5f5; position: relative; min-height: 300px;">
                <img src="<?php echo e(asset('images/livres.png')); ?>" 
                     alt="Livres" 
                     style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            
            <!-- Statistiques à droite -->
            <div class="col-md-7" style="background: #f8f9fa;">
                <div class="p-4 p-md-5">
                    <!-- Statistiques compactes -->
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="p-3 bg-white rounded shadow-sm text-center">
                                <div class="text-uppercase text-muted small mb-1" style="font-weight: 600; font-size: 0.7rem; letter-spacing: 0.5px;">Mes Livres</div>
                                <div class="h3 mb-0 fw-bold" style="color: #667eea;">
                                    <i class="fas fa-book me-2" style="font-size: 1.2rem;"></i><?php echo e($myBooksCount); ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-white rounded shadow-sm text-center">
                                <div class="text-uppercase text-muted small mb-1" style="font-weight: 600; font-size: 0.7rem; letter-spacing: 0.5px;">Total Livres</div>
                                <div class="h3 mb-0 fw-bold" style="color: #667eea;">
                                    <i class="fas fa-books me-2" style="font-size: 1.2rem;"></i><?php echo e($totalBooks); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Boutons d'action -->
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="<?php echo e(route('books.create')); ?>" class="btn btn-purple btn-lg" style="border-radius: 0px; padding: 0.75rem 2rem; font-weight: 500;">
                            <i class="fas fa-plus me-2"></i>Ajouter un livre
                        </a>
                        <?php if(auth()->guard()->check()): ?>
                            <?php if(!$isAdmin && !$isOthers): ?>
                            <a href="<?php echo e(route('books.index', ['scope' => 'others'])); ?>" class="btn btn-outline-purple btn-lg" style="border-radius: 0px; padding: 0.75rem 2rem; font-weight: 500;">
                                <i class="fas fa-users me-2"></i> Livres
                            </a>
                            <?php elseif($isOthers): ?>
                            <a href="<?php echo e(route('books.index')); ?>" class="btn btn-outline-purple btn-lg" style="border-radius: 0px; padding: 0.75rem 2rem; font-weight: 500;">
                                <i class="fas fa-book me-2"></i>Mes livres
                            </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success" style="position: relative; z-index: 1;"><?php echo e(session('success')); ?></div>
<?php endif; ?>

<div class="row" style="position: relative; z-index: 1;">
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
                        <span class="badge badge-<?php echo e($book->status == 'available' ? 'success' : 'warning'); ?>">
                            <?php echo e($book->status == 'available' ? 'Disponible' : 'Réservé'); ?>

                        </span>
                        <span class="badge badge-purple"><?php echo e($book->age_display); ?></span>
                    </div>
                    
                    <p class="card-text small text-purple mb-2">
                        <i class="fas fa-user"></i> <?php echo e($book->user?->name ?? 'N/A'); ?>

                    </p>
                    
                    <?php if($book->description): ?>
                        <p class="card-text small text-muted mb-3">
                            <?php echo e(Str::limit($book->description, 60)); ?>

                        </p>
                    <?php endif; ?>
                    
                    <div class="mt-auto">
                        <div class="btn-group w-100" role="group">
                            <a href="<?php echo e(route('books.show', $book)); ?>" class="btn btn-sm btn-outline-purple" title="Voir détails">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                            <?php if(auth()->guard()->check()): ?>
                                <?php if(auth()->user()->isAdmin() || auth()->id() == $book->user_id): ?>
                                    <a href="<?php echo e(route('books.edit', $book)); ?>" class="btn btn-sm btn-outline-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <?php if(!$book->ai_summary): ?>
                                        <form action="<?php echo e(route('books.generateSummary', $book)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-sm btn-outline-info" title="Générer résumé IA">
                                                <i class="fas fa-magic"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    
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
                                           class="btn btn-sm btn-outline-purple" title="Donner un avis">
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
                    <a href="<?php echo e(route('books.create')); ?>" class="btn btn-purple">
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

<?php $__env->startPush('styles'); ?>
<style>
    .book-card {
        transition: all 0.3s ease;
        border: 1px solid #f1f5f9;
        border-radius: 0px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    
    .book-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .book-card .card-img-top {
        transition: transform 0.3s ease;
    }
    
    .book-card:hover .card-img-top {
        transform: scale(1.05);
    }
    
    .btn-group .btn {
        border-radius: 0;
        font-size: 0.8rem;
    }
    
    .btn-group .btn:first-child {
        border-top-left-radius: 0.25rem;
        border-bottom-left-radius: 0.25rem;
    }
    
    .btn-group .btn:last-child {
        border-top-right-radius: 0.25rem;
        border-bottom-right-radius: 0.25rem;
    }
    
    .badge {
        font-size: 0.7rem;
        font-weight: 500;
    }
    
    .card-title a {
        color: #2c3e50;
        font-weight: 600;
    }
    
    .card-title a:hover {
        color: #667eea;
    }
    
    /* Rendre le body transparent pour voir le fond */
    body {
        background: transparent !important;
    }
    
    /* Fond dégradé violet avec étoiles */
    .stars-background {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        z-index: 0;
        overflow: hidden;
    }
    
    /* Mettre le contenu au-dessus du fond */
    nav, .container-fluid {
        position: relative;
        z-index: 10;
    }
    
    /* Navbar semi-transparente sur fond violet */
    nav.navbar {
        background: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(10px);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    
    .star {
        position: absolute;
        background: white;
        border-radius: 50%;
        animation: float linear infinite;
        box-shadow: 0 0 15px rgba(255, 255, 255, 0.9), 0 0 30px rgba(255, 255, 255, 0.5);
        opacity: 0;
    }
    
    @keyframes float {
        0% {
            transform: translateY(0) translateX(0) rotate(0deg) scale(0.5);
            opacity: 0;
        }
        5% {
            opacity: 1;
        }
        50% {
            transform: translateY(-50vh) translateX(20px) rotate(180deg) scale(1);
            opacity: 1;
        }
        95% {
            opacity: 1;
        }
        100% {
            transform: translateY(-110vh) translateX(-20px) rotate(360deg) scale(0.5);
            opacity: 0;
        }
    }
    
    /* Rendre les cartes plus visibles sur le fond violet */
    .card.smooth {
        background: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(10px);
    }
    
    .book-card {
        background: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(10px);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Créer des étoiles flottantes
    function createStars() {
        const container = document.getElementById('starsContainer');
        const numberOfStars = 100; // Plus d'étoiles pour un effet plus remarquable
        
        for (let i = 0; i < numberOfStars; i++) {
            const star = document.createElement('div');
            star.className = 'star';
            
            // Taille aléatoire entre 3px et 8px (plus grandes)
            const size = Math.random() * 5 + 3;
            star.style.width = size + 'px';
            star.style.height = size + 'px';
            
            // Position horizontale aléatoire
            star.style.left = Math.random() * 100 + '%';
            
            // Position verticale de départ aléatoire (commencer en bas)
            star.style.top = '100%';
            
            // Durée d'animation aléatoire entre 8s et 20s (plus rapide)
            const duration = Math.random() * 12 + 8;
            star.style.animationDuration = duration + 's';
            
            // Délai aléatoire pour un effet plus naturel
            star.style.animationDelay = Math.random() * 8 + 's';
            
            container.appendChild(star);
        }
    }
    
    // Créer les étoiles au chargement de la page
    document.addEventListener('DOMContentLoaded', createStars);
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo\Desktop\bookShare\projet_laravel\resources\views/books/index.blade.php ENDPATH**/ ?>