<?php $__env->startSection('title', 'BookShare - Historique des Échanges'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-history text-primary mr-2"></i>
                Historique des Échanges
            </h1>
            <p class="mb-0 text-gray-600">Gérez et consultez l'historique de tous vos échanges de livres</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('exchanges.create')); ?>" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Nouvel Échange
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Échanges</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($exchanges->count()); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exchange-alt fa-2x text-gray-300"></i>
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
                                Échanges Terminés</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo e($exchanges->where('status', 'TERMINE')->count()); ?>

                            </div>
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
                                En Attente</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo e($exchanges->where('status', 'EN_ATTENTE')->count()); ?>

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
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                En Cours</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo e($exchanges->where('status', 'EN_COURS')->count()); ?>

                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-sync fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Exchanges Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list mr-2"></i>Liste des Échanges
                    </h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Actions rapides:</div>
                            <a class="dropdown-item" href="<?php echo e(route('exchanges.create')); ?>">
                                <i class="fas fa-plus fa-sm fa-fw mr-2 text-gray-400"></i>
                                Nouvel échange
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($exchanges->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th><i class="fas fa-tag mr-1"></i>Type</th>
                                        <th class="text-center"><i class="fas fa-traffic-light mr-1"></i>Statut</th>
                                        <th><i class="fas fa-book mr-1"></i>Livre Demandé</th>
                                        <th class="text-center"><i class="fas fa-user mr-1"></i>Initiateur</th>
                                        <th class="text-center"><i class="fas fa-user-check mr-1"></i>Récepteur</th>
                                        <th class="text-center"><i class="fas fa-calendar-alt mr-1"></i>Début</th>
                                        <th class="text-center"><i class="fas fa-calendar-check mr-1"></i>Fin</th>
                                        <th class="text-center"><i class="fas fa-cogs mr-1"></i>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $exchanges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exchange): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="exchange-row" data-exchange-id="<?php echo e($exchange->id); ?>">
                                        <td class="text-center font-weight-bold text-primary">#<?php echo e($exchange->id); ?></td>
                                        <td>
                                            <?php
                                                $typeClass = 'info';
                                                $typeIcon = 'fas fa-exchange-alt';
                                                switch($exchange->type) {
                                                    case 'PRET':
                                                        $typeClass = 'success';
                                                        $typeIcon = 'fas fa-hand-holding';
                                                        break;
                                                    case 'ECHANGE':
                                                        $typeClass = 'primary';
                                                        $typeIcon = 'fas fa-sync-alt';
                                                        break;
                                                }
                                            ?>
                                            <span class="badge badge-<?php echo e($typeClass); ?> badge-pill">
                                                <i class="<?php echo e($typeIcon); ?> mr-1"></i><?php echo e($exchange->type); ?>

                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                                $statusConfig = [
                                                    'EN_ATTENTE' => ['class' => 'warning', 'icon' => 'fas fa-clock', 'text' => 'En Attente'],
                                                    'EN_COURS' => ['class' => 'primary', 'icon' => 'fas fa-sync fa-spin', 'text' => 'En Cours'],
                                                    'TERMINE' => ['class' => 'success', 'icon' => 'fas fa-check-circle', 'text' => 'Terminé'],
                                                    'ANNULE' => ['class' => 'danger', 'icon' => 'fas fa-times-circle', 'text' => 'Annulé']
                                                ];
                                                $status = $statusConfig[$exchange->status] ?? ['class' => 'secondary', 'icon' => 'fas fa-question', 'text' => $exchange->status];
                                            ?>
                                            <span class="badge badge-<?php echo e($status['class']); ?> badge-pill p-2">
                                                <i class="<?php echo e($status['icon']); ?> mr-1"></i><?php echo e($status['text']); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="mr-2">
                                                    <i class="fas fa-book text-primary"></i>
                                                </div>
                                                <div>
                                                    <div class="font-weight-bold text-gray-800">
                                                        <?php echo e($exchange->bookDemande ? $exchange->bookDemande->title : 'Livre non spécifié'); ?>

                                                    </div>
                                                    <?php if($exchange->bookDemande && $exchange->bookDemande->user): ?>
                                                        <small class="text-muted">
                                                            <i class="fas fa-user fa-sm mr-1"></i>
                                                            Propriétaire: <?php echo e($exchange->bookDemande->user->name); ?>

                                                        </small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <?php if($exchange->initiateur): ?>
                                                <div class="d-flex flex-column align-items-center">
                                                    <span class="badge badge-outline-primary mb-1">
                                                        <i class="fas fa-user mr-1"></i><?php echo e($exchange->initiateur->name); ?>

                                                    </span>
                                                    <small class="text-muted"><?php echo e($exchange->initiateur->email); ?></small>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted">N/A</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if($exchange->recepteur): ?>
                                                <div class="d-flex flex-column align-items-center">
                                                    <span class="badge badge-outline-secondary mb-1">
                                                        <i class="fas fa-user-check mr-1"></i><?php echo e($exchange->recepteur->name); ?>

                                                    </span>
                                                    <small class="text-muted"><?php echo e($exchange->recepteur->email); ?></small>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted">Non assigné</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex flex-column">
                                                <span class="font-weight-bold"><?php echo e(\Carbon\Carbon::parse($exchange->dateDebut)->format('d/m/Y')); ?></span>
                                                <small class="text-muted"><?php echo e(\Carbon\Carbon::parse($exchange->dateDebut)->format('H:i')); ?></small>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex flex-column">
                                                <span class="font-weight-bold"><?php echo e(\Carbon\Carbon::parse($exchange->dateFin)->format('d/m/Y')); ?></span>
                                                <small class="text-muted"><?php echo e(\Carbon\Carbon::parse($exchange->dateFin)->format('H:i')); ?></small>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo e(route('exchanges.show', $exchange->id)); ?>" 
                                                   class="btn btn-outline-info btn-sm" 
                                                   data-toggle="tooltip" 
                                                   title="Voir les détails">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if($exchange->status !== 'TERMINE' && $exchange->status !== 'ANNULE'): ?>
                                                    <a href="<?php echo e(route('exchanges.edit', $exchange->id)); ?>" 
                                                       class="btn btn-outline-warning btn-sm" 
                                                       data-toggle="tooltip" 
                                                       title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-exchange-alt fa-4x text-gray-300 mb-3"></i>
                            </div>
                            <h4 class="text-gray-600 mb-3">Aucun échange en cours</h4>
                            <p class="text-gray-500 mb-4">
                                Vous n'avez pas encore créé d'échange de livres.<br>
                                Commencez dès maintenant à partager vos livres avec la communauté BookShare !
                            </p>
                            <div class="d-flex justify-content-center gap-3">
                                <a href="<?php echo e(route('exchanges.create')); ?>" class="btn btn-primary btn-lg shadow-sm">
                                    <i class="fas fa-plus mr-2"></i>Créer mon premier échange
                                </a>
                                <a href="#" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-book mr-2"></i>Parcourir les livres
                                </a>
                            </div>
                            <div class="mt-4">
                                <small class="text-muted">
                                    <i class="fas fa-lightbulb mr-1"></i>
                                    Astuce : Vous pouvez échanger, prêter ou emprunter des livres facilement avec BookShare !
                                </small>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .exchange-row {
        transition: all 0.2s ease-in-out;
    }
    
    .exchange-row:hover {
        background-color: #f8f9fc !important;
        transform: translateY(-1px);
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    
    .badge-outline-primary {
        color: #4e73df;
        background-color: transparent;
        border: 1px solid #4e73df;
    }
    
    .badge-outline-secondary {
        color: #858796;
        background-color: transparent;
        border: 1px solid #858796;
    }
    
    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .card-header h6, .card-header i {
        color: white !important;
    }
    
    .table th {
        border-top: none;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .btn-group .btn {
        margin: 0 2px;
    }
    
    .badge-pill {
        font-size: 0.75rem;
        padding: 0.4rem 0.8rem;
    }
    
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();
        
        // Initialize DataTable with enhanced features
        $('#dataTable').DataTable({
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Tous"]],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json"
            },
            "order": [[ 0, "desc" ]], // Order by ID descending
            "columnDefs": [
                { "orderable": false, "targets": [8] }, // Disable ordering on Actions column
                { "searchable": false, "targets": [8] }  // Disable search on Actions column
            ],
            "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                   '<"row"<"col-sm-12"tr>>' +
                   '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        });

        // Add smooth animations for row interactions
        $('.exchange-row').on('mouseenter', function() {
            $(this).addClass('table-active');
        }).on('mouseleave', function() {
            $(this).removeClass('table-active');
        });
        
        // Add click animation for buttons
        $('.btn').on('click', function() {
            $(this).addClass('pulse');
            setTimeout(() => {
                $(this).removeClass('pulse');
            }, 300);
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo\Desktop\bookShare\projet_laravel\resources\views/exchanges/index.blade.php ENDPATH**/ ?>