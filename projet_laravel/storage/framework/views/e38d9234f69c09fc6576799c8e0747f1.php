<?php $__env->startSection('title', 'Gestion des Catégories - Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">📚 Gestion des Catégories</h1>
    <a href="<?php echo e(route('admin.categories.create')); ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouvelle Catégorie
    </a>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo e(session('success')); ?>

        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo e(session('error')); ?>

        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Liste des Catégories</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Âge Min.</th>
                        <th>Couleur</th>
                        <th>Livres</th>
                        <th>Statut</th>
                        <th>Mise en avant</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php if($category->icon): ?>
                                        <i class="<?php echo e($category->icon); ?> text-primary mr-2"></i>
                                    <?php endif; ?>
                                    <strong><?php echo e($category->name); ?></strong>
                                </div>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?php echo e(Str::limit($category->description, 50)); ?>

                                </small>
                            </td>
                            <td>
                                <span class="badge badge-info"><?php echo e($category->age_allowed); ?>+</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="color-preview" 
                                         style="width: 20px; height: 20px; background-color: <?php echo e($category->color); ?>; border-radius: 3px; margin-right: 8px;"></div>
                                    <small><?php echo e($category->color); ?></small>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-secondary"><?php echo e($category->books_count); ?></span>
                            </td>
                            <td>
                                <form action="<?php echo e(route('admin.categories.toggle-status', $category)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <button type="submit" class="btn btn-sm btn-<?php echo e($category->is_active ? 'success' : 'secondary'); ?>" 
                                            title="<?php echo e($category->is_active ? 'Désactiver' : 'Activer'); ?>">
                                        <i class="fas fa-<?php echo e($category->is_active ? 'check' : 'times'); ?>"></i>
                                        <?php echo e($category->is_active ? 'Actif' : 'Inactif'); ?>

                                    </button>
                                </form>
                            </td>
                            <td>
                                <form action="<?php echo e(route('admin.categories.toggle-featured', $category)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <button type="submit" class="btn btn-sm btn-<?php echo e($category->is_featured ? 'warning' : 'outline-warning'); ?>" 
                                            title="<?php echo e($category->is_featured ? 'Retirer de la mise en avant' : 'Mettre en avant'); ?>">
                                        <i class="fas fa-star"></i>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="<?php echo e(route('admin.categories.show', $category)); ?>" 
                                       class="btn btn-sm btn-info" title="Voir détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('admin.categories.edit', $category)); ?>" 
                                       class="btn btn-sm btn-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if($category->books_count == 0): ?>
                                        <form action="<?php echo e(route('admin.categories.destroy', $category)); ?>" 
                                              method="POST" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?');" 
                                              class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-secondary" disabled title="Impossible de supprimer - contient des livres">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-folder-open fa-3x mb-3"></i><br>
                                <strong>Aucune catégorie trouvée</strong><br>
                                <small>Créez votre première catégorie pour organiser les livres.</small><br>
                                <a href="<?php echo e(route('admin.categories.create')); ?>" class="btn btn-primary btn-sm mt-2">
                                    <i class="fas fa-plus"></i> Créer une catégorie
                                </a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Catégories
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($categories->count()); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tags fa-2x text-gray-300"></i>
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
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Catégories Actives
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($categories->where('is_active', true)->count()); ?></div>
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
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Mises en avant
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($categories->where('is_featured', true)->count()); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-star fa-2x text-gray-300"></i>
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
                            Total Livres
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($categories->sum('books_count')); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-book fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo\Desktop\bookShare\projet_laravel\resources\views/admin/categories/index.blade.php ENDPATH**/ ?>