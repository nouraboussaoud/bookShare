<?php $__env->startSection('title', 'BookShare - Mes signalements'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-flag text-primary mr-2"></i>
                Mes signalements
            </h1>
            <p class="mb-0 text-gray-600">Consultez l'état de vos signalements</p>
        </div>
        <a href="<?php echo e(route('reports.create')); ?>" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Nouveau signalement
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Signalements
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($reports->total()); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-flag fa-2x text-gray-300"></i>
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
                                En Attente
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo e($reports->where('status', 'EN_ATTENTE')->count()); ?>

                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                                Traités
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo e($reports->where('status', 'TRAITE')->count()); ?>

                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Rejetés
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo e($reports->where('status', 'REJETE')->count()); ?>

                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Liste de mes signalements</h6>
        </div>
        <div class="card-body">
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

            <!-- Filters Card -->
            <div class="card mb-4 border-left-primary">
                <div class="card-header bg-light">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-filter mr-2"></i>Filtres de recherche
                    </h6>
                </div>
                <div class="card-body">
                    <form method="GET" class="row">
                        <div class="col-md-4 mb-3">
                            <label for="status" class="form-label font-weight-bold">Statut</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">Tous les statuts</option>
                                <?php $__currentLoopData = \App\Models\Report::getStatuses(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($value); ?>" <?php echo e(request('status') == $value ? 'selected' : ''); ?>>
                                        <?php echo e($label); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="type" class="form-label font-weight-bold">Type</label>
                            <select name="type" id="type" class="form-control">
                                <option value="">Tous les types</option>
                                <?php $__currentLoopData = \App\Models\Report::getTypes(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($value); ?>" <?php echo e(request('type') == $value ? 'selected' : ''); ?>>
                                        <?php echo e($label); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3 d-flex align-items-end">
                            <div class="btn-group w-100" role="group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search mr-1"></i> Filtrer
                                </button>
                                <?php if(request()->hasAny(['status', 'type'])): ?>
                                    <a href="<?php echo e(route('reports.index')); ?>" class="btn btn-outline-secondary">
                                        <i class="fas fa-undo mr-1"></i> Reset
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>
                    
                    <?php if(request()->hasAny(['status', 'type'])): ?>
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                Filtres actifs: 
                                <?php if(request('status')): ?>
                                    <span class="badge badge-primary"><?php echo e(\App\Models\Report::getStatuses()[request('status')]); ?></span>
                                <?php endif; ?>
                                <?php if(request('type')): ?>
                                    <span class="badge badge-info"><?php echo e(\App\Models\Report::getTypes()[request('type')]); ?></span>
                                <?php endif; ?>
                            </small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if($reports->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Type</th>
                                <th>Statut</th>
                                <th>Cible</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><strong>#<?php echo e($report->id); ?></strong></td>
                                <td>
                                    <?php if($report->type === 'CONFLIT_ECHANGE'): ?>
                                        <span class="badge badge-purple">Conflit d'échange</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Comportement</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($report->status === 'EN_ATTENTE'): ?>
                                        <span class="badge badge-warning">En attente</span>
                                    <?php elseif($report->status === 'TRAITE'): ?>
                                        <span class="badge badge-success">Traité</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Rejeté</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($report->reportedUser): ?>
                                        <i class="fas fa-user text-primary"></i> <?php echo e($report->reportedUser->name); ?>

                                    <?php elseif($report->exchange): ?>
                                        <i class="fas fa-exchange-alt text-info"></i> Échange #<?php echo e($report->exchange->id); ?>

                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e(Str::limit($report->description, 50)); ?></td>
                                <td><?php echo e($report->created_at->format('d/m/Y H:i')); ?></td>
                                <td>
                                    <a href="<?php echo e(route('reports.show', $report)); ?>" class="btn btn-info btn-sm" title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    <?php echo e($reports->appends(request()->query())->links()); ?>

                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-flag fa-4x text-gray-300 mb-3"></i>
                    <h4 class="text-gray-600 mb-2">Aucun signalement trouvé</h4>
                    <p class="text-gray-500">
                        <?php if(request()->hasAny(['status', 'type'])): ?>
                            Aucun signalement ne correspond à vos critères de recherche.
                        <?php else: ?>
                            Vous n'avez créé aucun signalement pour le moment.
                        <?php endif; ?>
                    </p>
                    <?php if(!request()->hasAny(['status', 'type'])): ?>
                        <a href="<?php echo e(route('reports.create')); ?>" class="btn btn-primary mt-3">
                            <i class="fas fa-plus mr-1"></i> Créer mon premier signalement
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.badge-purple {
    color: #fff;
    background-color: #6f42c1;
}

.card.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.card.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.card.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.card.border-left-danger {
    border-left: 0.25rem solid #e74a3b !important;
}

.btn-group .btn {
    border-radius: 0;
}

.btn-group .btn:first-child {
    border-top-left-radius: 0.375rem;
    border-bottom-left-radius: 0.375rem;
}

.btn-group .btn:last-child {
    border-top-right-radius: 0.375rem;
    border-bottom-right-radius: 0.375rem;
}

.form-label {
    margin-bottom: 0.5rem;
    color: #5a5c69;
}

.card-header.bg-light {
    background-color: #f8f9fc !important;
    border-bottom: 1px solid #e3e6f0;
}

.table thead th {
    border-bottom: 2px solid #e3e6f0;
    background-color: #f8f9fc;
    color: #5a5c69;
    font-weight: 600;
}

.table tbody tr:hover {
    background-color: #f8f9fc;
}
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo\Desktop\bookShare\projet_laravel\resources\views/reports/index.blade.php ENDPATH**/ ?>