<?php $__env->startSection('title', 'Gestion des Utilisateurs'); ?>
<?php $__env->startSection('content'); ?>
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestion des Utilisateurs</h1>
        <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus fa-sm text-white-50"></i> Nouvel Utilisateur
        </a>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Utilisateurs</h6>
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

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Statut</th>
                            <th>Date d'inscription</th>
                            <th>Dernière connexion</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($user->id); ?></td>
                            <td><?php echo e($user->name); ?></td>
                            <td><?php echo e($user->email); ?></td>
                            <td>
                                <?php if($user->role == 'admin'): ?>
                                    <span class="badge badge-danger">Admin</span>
                                <?php else: ?>
                                    <span class="badge badge-primary">User</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($user->status == 'active'): ?>
                                    <span class="badge badge-success">Actif</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Inactif</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($user->created_at->format('d/m/Y H:i')); ?></td>
                            <td><?php echo e($user->updated_at->format('d/m/Y H:i')); ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="btn btn-info btn-sm" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <?php if($user->id !== auth()->id()): ?>
                                        <form method="POST" action="<?php echo e(route('admin.users.toggle-status', $user)); ?>" style="display: inline;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <button type="submit" class="btn btn-<?php echo e($user->status == 'active' ? 'warning' : 'success'); ?> btn-sm" 
                                                    title="<?php echo e($user->status == 'active' ? 'Désactiver' : 'Activer'); ?>">
                                                <i class="fas fa-<?php echo e($user->status == 'active' ? 'ban' : 'check'); ?>"></i>
                                            </button>
                                        </form>
                                        
                                        <form method="POST" action="<?php echo e(route('admin.users.destroy', $user)); ?>" style="display: inline;" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-danger btn-sm" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="badge badge-info">Vous</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                <?php echo e($users->links()); ?>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\abous\OneDrive\Bureau\bookShare\bookShare\projet_laravel\resources\views/admin/users/index.blade.php ENDPATH**/ ?>