<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-store fa-sm text-success"></i>
            Marketplace des Locations
        </h1>
        <div>
            <a href="<?php echo e(route('locations.help')); ?>" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm mr-2">
                <i class="fas fa-question-circle fa-sm text-white-50"></i> Guide d'aide
            </a>
            <a href="<?php echo e(route('locations.index')); ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-handshake fa-sm text-white-50"></i> Mes Locations
            </a>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Livres Disponibles</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($livresDisponibles->total()); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Locations Actives</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($locationsRecentes->where('statut', 'en_cours')->count()); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-handshake fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Prix Moyen</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php if($locationsRecentes->count() > 0): ?>
                                    <?php echo e(number_format($locationsRecentes->avg('prix'), 2)); ?>€
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-euro-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Propriétaires Actifs</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($livresDisponibles->pluck('user_id')->unique()->count()); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter"></i>
                Filtres et Recherche
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('locations.marketplace')); ?>">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="search">Rechercher un livre</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="search" 
                                   name="search" 
                                   value="<?php echo e(request('search')); ?>"
                                   placeholder="Titre, auteur...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="category">Catégorie</label>
                            <select class="form-control" id="category" name="category">
                                <option value="">Toutes les catégories</option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category->id); ?>" 
                                            <?php echo e(request('category') == $category->id ? 'selected' : ''); ?>>
                                        <?php echo e($category->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="price_max">Prix maximum (€)</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="price_max" 
                                   name="price_max" 
                                   value="<?php echo e(request('price_max')); ?>"
                                   placeholder="Ex: 10"
                                   step="0.01">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search"></i> Filtrer
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des livres disponibles -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-success">
                <i class="fas fa-book-open"></i>
                Livres Disponibles à la Location (<?php echo e($livresDisponibles->total()); ?>)
            </h6>
        </div>
        <div class="card-body">
            <?php if($livresDisponibles->count() > 0): ?>
                <div class="row">
                    <?php $__currentLoopData = $livresDisponibles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $livre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                            <div class="card border-left-success h-100">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <!-- Image du livre -->
                                        <div class="col-4">
                                            <?php if($livre->hasPhoto()): ?>
                                                <img src="<?php echo e($livre->photo_url); ?>" 
                                                     alt="<?php echo e($livre->title); ?>" 
                                                     class="img-fluid rounded shadow-sm"
                                                     style="max-height: 120px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                     style="height: 120px;">
                                                    <i class="fas fa-book fa-2x text-gray-400"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <!-- Informations du livre -->
                                        <div class="col-8">
                                            <h6 class="card-title mb-2">
                                                <a href="<?php echo e(route('books.show', $livre)); ?>" 
                                                   class="text-decoration-none text-dark">
                                                    <?php echo e(Str::limit($livre->title, 30)); ?>

                                                </a>
                                            </h6>
                                            
                                            <p class="card-text text-muted mb-1">
                                                <small><strong>Auteur:</strong> <?php echo e(Str::limit($livre->author, 25)); ?></small>
                                            </p>
                                            
                                            <p class="card-text text-muted mb-1">
                                                <small>
                                                    <i class="fas fa-user text-primary"></i>
                                                    <strong>Propriétaire:</strong> <?php echo e($livre->user->name); ?>

                                                </small>
                                            </p>
                                            
                                            <?php if($livre->category): ?>
                                                <span class="badge badge-primary mb-2">
                                                    <i class="<?php echo e($livre->category->icon); ?>"></i>
                                                    <?php echo e($livre->category->name); ?>

                                                </span>
                                            <?php endif; ?>
                                            
                                            <!-- Prix suggéré basé sur les locations récentes -->
                                            <?php
                                                $prixSuggere = $locationsRecentes->where('book.title', $livre->title)->avg('prix') 
                                                            ?? $locationsRecentes->avg('prix') 
                                                            ?? 5.00;
                                            ?>
                                            
                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                <div>
                                                    <small class="text-muted">Prix suggéré:</small><br>
                                                    <span class="h6 text-success mb-0">
                                                        ~<?php echo e(number_format($prixSuggere, 2)); ?>€
                                                    </span>
                                                </div>
                                                
                                                <?php if(Auth::id() != $livre->user_id): ?>
                                                    <a href="<?php echo e(route('locations.create', ['book_id' => $livre->id])); ?>" 
                                                       class="btn btn-success btn-sm">
                                                        <i class="fas fa-handshake"></i> Louer
                                                    </a>
                                                <?php else: ?>
                                                    <span class="badge badge-secondary">Votre livre</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Informations supplémentaires -->
                                    <div class="mt-3 pt-2 border-top">
                                        <div class="row text-center">
                                            <div class="col-4">
                                                <small class="text-muted">Âge</small><br>
                                                <span class="badge badge-info"><?php echo e($livre->age_display); ?></span>
                                            </div>
                                            <div class="col-4">
                                                <small class="text-muted">Statut</small><br>
                                                <span class="badge badge-success">Disponible</span>
                                            </div>
                                            <div class="col-4">
                                                <small class="text-muted">Ajouté</small><br>
                                                <small class="text-muted"><?php echo e($livre->created_at->diffForHumans()); ?></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    <?php echo e($livresDisponibles->links()); ?>

                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-book-open fa-4x text-gray-300 mb-4"></i>
                    <h4 class="text-gray-500">Aucun livre disponible</h4>
                    <p class="text-gray-400">Il n'y a actuellement aucun livre disponible à la location.</p>
                    <a href="<?php echo e(route('books.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Ajouter un livre
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Locations récentes (exemples) -->
    <?php if($locationsRecentes->count() > 0): ?>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-info">
                    <i class="fas fa-history"></i>
                    Locations Récentes (Exemples de prix)
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php $__currentLoopData = $locationsRecentes->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-xl-4 col-md-6 mb-3">
                            <div class="card border-left-info">
                                <div class="card-body py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1"><?php echo e(Str::limit($location->book->title, 25)); ?></h6>
                                            <small class="text-muted">
                                                Par <?php echo e($location->proprietaire->name); ?>

                                            </small>
                                        </div>
                                        <div class="text-right">
                                            <div class="h6 text-success mb-0"><?php echo e(number_format($location->prix, 2)); ?>€</div>
                                            <small class="text-muted"><?php echo e($location->duree_jours); ?> jours</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo\Desktop\bookShare\projet_laravel\resources\views/locations/marketplace.blade.php ENDPATH**/ ?>