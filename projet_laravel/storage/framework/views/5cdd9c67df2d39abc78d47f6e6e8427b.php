<?php $__env->startSection('title', 'Reading Groups'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    :root{
        --primary: #6366f1;
        --secondary: #8b5cf6;
        --success: #10b981;
        --warning: #f59e0b;
        --border: #e2e8f0;
    }

    .page-hero {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: #fff;
        border-radius: 1rem;
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    .page-hero::before{
        content: "";
        position:absolute; inset:0;
        background: url("data:image/svg+xml,<svg width='60' height='60' xmlns='http://www.w3.org/2000/svg'><circle cx='30' cy='30' r='3' fill='%23fff' fill-opacity='0.12'/></svg>");
        opacity:.2;
    }

    .stat-card { background:#fff; border-left:4px solid var(--primary); border-radius:.75rem; padding:1rem; box-shadow:0 2px 6px rgba(0,0,0,.04); }
    .stat-number{ font-size:1.5rem; font-weight:700; color:var(--primary); }

    /* Group card improvements */
    .group-card { border:1px solid var(--border); border-radius:.75rem; overflow:hidden; transition:.18s; display:flex; flex-direction:column; height:100%; position:relative; background:#fff; }
    .group-card:hover { transform:translateY(-4px); box-shadow:0 8px 18px rgba(0,0,0,.06); border-color:var(--primary); }
    .group-badge { position:absolute; top:12px; right:12px; border-radius:999px; padding:.25rem .6rem; font-weight:600; font-size:.78rem; }
    .badge-public { background:#dcfce7; color:var(--success); }
    .badge-private { background:#fef3c7; color:var(--warning); }

    .group-content { padding:1rem 1rem 0 1rem; flex:0 0 auto; }
    .group-meta { padding:0 1rem 1rem 1rem; color:#6b7280; font-size:.9rem; }
    .group-owner { padding:0 1rem 1rem 1rem; display:flex; gap:.5rem; align-items:center; }
    .owner-avatar { width:34px; height:34px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; color:#fff; background:linear-gradient(135deg,var(--primary),var(--secondary)); }

    .group-actions { margin-top:auto; border-top:1px solid var(--border); padding:.75rem 1rem; background:#f8fafc; display:flex; gap:.5rem; flex-wrap:wrap; align-items:center; }
    .btn-modern { border-radius:.5rem; padding:.45rem .9rem; display:inline-flex; align-items:center; gap:.5rem; font-weight:600; font-size:.9rem; text-decoration:none; }
    .btn-join { background:var(--success); color:#fff; }
    .btn-request { background:var(--warning); color:#fff; }
    .btn-manage { background:var(--primary); color:#fff; }
    .btn-view { background:#fff; border:1px solid var(--border); color:#444; }

    /* responsive tweaks */
    @media (max-width:576px){ .group-meta { font-size:.85rem; } .stat-number{ font-size:1.25rem; } }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-3 px-md-4">

    <div class="page-hero">
        <div class="d-flex justify-content-between flex-wrap align-items-start gap-3">
            <div>
                <h1 class="h3 fw-bold mb-1"><i class="fas fa-book-reader me-2"></i> Reading Groups</h1>
                <p class="mb-0 text-white-75">Discover amazing reading communities and connect with fellow book lovers</p>
            </div>
            <a href="<?php echo e(route('reading-groups.create')); ?>" class="btn btn-light btn-lg shadow-sm">
                <i class="fas fa-plus me-2"></i>Create Group
            </a>
        </div>
    </div>

    
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-number"><?php echo e($groups->total()); ?></div>
                <div class="text-muted small">Total Groups</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-number"><?php echo e($groups->where('is_private', false)->count()); ?></div>
                <div class="text-muted small">Public Groups</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-number"><?php echo e($groups->where('owner_id', auth()->id())->count()); ?></div>
                <div class="text-muted small">Your Groups</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-number"><?php echo e($groups->sum('members_count')); ?></div>
                <div class="text-muted small">Total Members</div>
            </div>
        </div>
    </div>

    
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h5 class="mb-0"><i class="fas fa-users me-2 text-primary"></i> All Reading Groups</h5>
            <div class="d-flex gap-2 w-100 w-md-auto">
                <div class="position-relative flex-grow-1">
                    <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input class="form-control ps-5 rounded-pill" placeholder="Search groups by name or description..." />
                </div>
                <select class="form-select rounded-pill w-auto">
                    <option>All Groups</option>
                    <option>Public Groups</option>
                    <option>Private Groups</option>
                    <option>My Groups</option>
                </select>
            </div>
        </div>

        <div class="card-body">
            <?php if($groups->count()): ?>
                <div class="row g-4">
                    <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            // safe fallbacks
                            $membersCount = $group->members_count ?? ($group->members ? $group->members->count() : 0);
                            $ownerName = optional($group->owner)->name ?? 'Unknown';
                        ?>

                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="group-card h-100">
                                <span class="group-badge <?php echo e($group->is_private ? 'badge-private' : 'badge-public'); ?>">
                                    <i class="fas fa-<?php echo e($group->is_private ? 'lock' : 'globe'); ?> me-1"></i>
                                    <?php echo e($group->is_private ? 'Private' : 'Public'); ?>

                                </span>

                                <div class="group-content">
                                    <h5 class="mb-1 fw-bold">
                                        <?php echo e($group->name); ?>

                                        <?php if($group->owner_id === auth()->id()): ?>
                                            <i class="fas fa-crown text-warning small ms-1" title="You own this group"></i>
                                        <?php endif; ?>
                                    </h5>

                                    <p class="text-muted small mb-2" style="min-height:2.6rem;">
                                        <?php echo e(\Illuminate\Support\Str::limit($group->description ?? 'No description available', 140)); ?>

                                    </p>

                                    <div class="group-meta d-flex gap-3 flex-wrap">
                                        <span><i class="fas fa-users me-1"></i> <?php echo e($membersCount); ?> <?php echo e(\Illuminate\Support\Str::plural('member', $membersCount)); ?></span>
                                        <span><i class="fas fa-calendar me-1"></i> <?php echo e(optional($group->created_at)->format('M j, Y')); ?></span>
                                    </div>
                                </div>

                                <div class="group-owner">
                                    <div class="owner-avatar">
                                        <?php echo e(strtoupper(substr($ownerName,0,2))); ?>

                                    </div>
                                    <div class="small text-truncate"><?php echo e($ownerName); ?></div>
                                </div>

                                <div class="group-actions">
                                    <?php
                                        $isOwner = $group->owner_id === auth()->id();
                                        $isMember = $group->members ? $group->members->contains(auth()->id()) : false;
                                    ?>

                                    <?php if($isOwner): ?>
                                        <a href="<?php echo e(route('reading-groups.edit', $group)); ?>" class="btn-modern btn-manage">
                                            <i class="fas fa-cog"></i> Manage
                                        </a>
                                    <?php elseif($isMember): ?>
                                        <form action="<?php echo e(route('reading-groups.leave', $group)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn-modern btn-request" onclick="return confirm('Leave this group?')">
                                                <i class="fas fa-sign-out-alt"></i> Leave
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <form action="<?php echo e(route('reading-groups.join', $group)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn-modern <?php echo e($group->is_private ? 'btn-request' : 'btn-join'); ?>">
                                                <i class="fas fa-<?php echo e($group->is_private ? 'clock' : 'user-plus'); ?>"></i>
                                                <?php echo e($group->is_private ? 'Request' : 'Join'); ?>

                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <a href="<?php echo e(route('reading-groups.show', $group)); ?>" class="btn-modern btn-view ms-auto">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    <?php echo e($groups->links()); ?>

                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-book-open empty-icon"></i>
                    <h5>No reading groups yet</h5>
                    <p class="small mb-3">Be the first to create a reading community!</p>
                    <a href="<?php echo e(route('reading-groups.create')); ?>" class="btn-modern btn-manage">
                        <i class="fas fa-plus me-1"></i>Create First Group
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo\Desktop\bookShare\projet_laravel\resources\views/groups/index.blade.php ENDPATH**/ ?>