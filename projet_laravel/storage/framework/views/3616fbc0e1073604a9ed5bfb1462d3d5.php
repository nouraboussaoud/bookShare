<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-handshake fa-sm text-primary"></i>
            Mes Locations
        </h1>
        <div>
            <a href="<?php echo e(route('locations.help')); ?>" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm mr-2">
                <i class="fas fa-question-circle fa-sm text-white-50"></i> Guide d'aide
            </a>
            <a href="<?php echo e(route('books.index')); ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Louer un livre
            </a>
        </div>
    </div>

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

    <div class="row">
        <!-- Locations où je suis locataire -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-book-reader"></i>
                        Livres que je loue (<?php echo e($locationsCommeLocataire->count()); ?>)
                    </h6>
                </div>
                <div class="card-body">
                    <?php if($locationsCommeLocataire->isEmpty()): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-book-open fa-3x text-gray-300 mb-3"></i>
                            <p class="text-gray-500">Vous n'avez pas encore loué de livres.</p>
                            <a href="<?php echo e(route('books.index')); ?>" class="btn btn-primary btn-sm">
                                Parcourir les livres
                            </a>
                        </div>
                    <?php else: ?>
                        <?php $__currentLoopData = $locationsCommeLocataire; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="card mb-3 border-left-<?php echo e($location->statut === 'en_cours' ? 'success' : ($location->statut === 'en_attente' ? 'warning' : 'info')); ?>">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h6 class="card-title mb-1">
                                                <a href="<?php echo e(route('locations.show', $location)); ?>" class="text-decoration-none">
                                                    <?php echo e($location->book->title); ?>

                                                </a>
                                            </h6>
                                            <p class="card-text text-muted mb-1">
                                                <small>
                                                    <i class="fas fa-user"></i> Propriétaire: <?php echo e($location->proprietaire->name); ?>

                                                </small>
                                            </p>
                                            <p class="card-text text-muted mb-1">
                                                <small>
                                                    <i class="fas fa-calendar"></i> 
                                                    Du <?php echo e($location->date_location->format('d/m/Y')); ?> 
                                                    au <?php echo e($location->date_fin_prevue->format('d/m/Y')); ?>

                                                    (<?php echo e($location->duree_jours); ?> jours)
                                                </small>
                                            </p>
                                            <p class="card-text text-muted mb-0">
                                                <small>
                                                    <i class="fas fa-map-marker-alt"></i> <?php echo e($location->localisation); ?>

                                                </small>
                                            </p>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <div class="mb-2">
                                                <?php if($location->statut === 'en_attente'): ?>
                                                    <span class="badge badge-warning">En attente</span>
                                                <?php elseif($location->statut === 'confirmee'): ?>
                                                    <span class="badge badge-info">Confirmée</span>
                                                <?php elseif($location->statut === 'en_cours'): ?>
                                                    <span class="badge badge-success">En cours</span>
                                                    <?php if($location->estEnRetard()): ?>
                                                        <span class="badge badge-danger">En retard</span>
                                                    <?php endif; ?>
                                                <?php elseif($location->statut === 'terminee'): ?>
                                                    <span class="badge badge-secondary">Terminée</span>
                                                <?php else: ?>
                                                    <span class="badge badge-dark">Annulée</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="h5 mb-2 text-success"><?php echo e(number_format($location->prix, 2)); ?>€</div>
                                            <a href="<?php echo e(route('locations.show', $location)); ?>" class="btn btn-sm btn-outline-primary">
                                                Voir détails
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Locations où je suis propriétaire -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-coins"></i>
                        Mes livres en location (<?php echo e($locationsCommeProprietaire->count()); ?>)
                    </h6>
                </div>
                <div class="card-body">
                    <?php if($locationsCommeProprietaire->isEmpty()): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-hand-holding-usd fa-3x text-gray-300 mb-3"></i>
                            <p class="text-gray-500">Aucune demande de location pour vos livres.</p>
                            <a href="<?php echo e(route('books.create')); ?>" class="btn btn-success btn-sm">
                                Ajouter un livre
                            </a>
                        </div>
                    <?php else: ?>
                        <?php $__currentLoopData = $locationsCommeProprietaire; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="card mb-3 border-left-<?php echo e($location->statut === 'en_cours' ? 'success' : ($location->statut === 'en_attente' ? 'warning' : 'info')); ?>">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h6 class="card-title mb-1">
                                                <a href="<?php echo e(route('locations.show', $location)); ?>" class="text-decoration-none">
                                                    <?php echo e($location->book->title); ?>

                                                </a>
                                            </h6>
                                            <p class="card-text text-muted mb-1">
                                                <small>
                                                    <i class="fas fa-user"></i> Locataire: <?php echo e($location->locataire->name); ?>

                                                </small>
                                            </p>
                                            <p class="card-text text-muted mb-1">
                                                <small>
                                                    <i class="fas fa-calendar"></i> 
                                                    Du <?php echo e($location->date_location->format('d/m/Y')); ?> 
                                                    au <?php echo e($location->date_fin_prevue->format('d/m/Y')); ?>

                                                    (<?php echo e($location->duree_jours); ?> jours)
                                                </small>
                                            </p>
                                            <p class="card-text text-muted mb-0">
                                                <small>
                                                    <i class="fas fa-map-marker-alt"></i> <?php echo e($location->localisation); ?>

                                                </small>
                                            </p>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <div class="mb-2">
                                                <?php if($location->statut === 'en_attente'): ?>
                                                    <span class="badge badge-warning">En attente</span>
                                                <?php elseif($location->statut === 'confirmee'): ?>
                                                    <span class="badge badge-info">Confirmée</span>
                                                <?php elseif($location->statut === 'en_cours'): ?>
                                                    <span class="badge badge-success">En cours</span>
                                                    <?php if($location->estEnRetard()): ?>
                                                        <span class="badge badge-danger">En retard</span>
                                                    <?php endif; ?>
                                                <?php elseif($location->statut === 'terminee'): ?>
                                                    <span class="badge badge-secondary">Terminée</span>
                                                <?php else: ?>
                                                    <span class="badge badge-dark">Annulée</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="h5 mb-2 text-success"><?php echo e(number_format($location->prix, 2)); ?>€</div>
                                            <a href="<?php echo e(route('locations.show', $location)); ?>" class="btn btn-sm btn-outline-success">
                                                Gérer
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo\Desktop\bookShare\projet_laravel\resources\views/locations/index.blade.php ENDPATH**/ ?>