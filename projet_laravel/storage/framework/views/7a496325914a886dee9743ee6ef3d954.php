<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">📚 Défis de Lecture</h1>
        <p class="text-gray-600">Rejoignez des défis, lisez des livres et gagnez des badges!</p>
    </div>

    <?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <!-- Navigation Tabs -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <a href="<?php echo e(route('challenges.index')); ?>" class="border-b-2 border-blue-500 py-4 px-1 text-blue-600 font-medium">
                Tous les Défis
            </a>
            <a href="<?php echo e(route('challenges.my-challenges')); ?>" class="border-transparent py-4 px-1 text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Mes Défis
            </a>
        </nav>
    </div>

    <!-- Active Challenges -->
    <div class="mb-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">🔥 Défis Actifs</h2>
        
        <?php if($activeChallenges->count() > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__currentLoopData = $activeChallenges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $challenge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                        <!-- Header with category color -->
                        <div class="h-2" style="background-color: <?php echo e($challenge->category->color ?? '#3B82F6'); ?>"></div>
                        
                        <div class="p-6">
                            <!-- Badge Icon -->
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-4xl"><?php echo e($challenge->badge_icon ?? '🏆'); ?></span>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Actif
                                </span>
                            </div>

                            <!-- Challenge Info -->
                            <h3 class="text-xl font-bold text-gray-900 mb-2"><?php echo e($challenge->challenge_name); ?></h3>
                            <p class="text-sm text-gray-600 mb-4"><?php echo e(Str::limit($challenge->description, 100)); ?></p>

                            <!-- Stats -->
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <p class="text-xs text-gray-500">Catégorie</p>
                                    <p class="font-semibold text-gray-900"><?php echo e($challenge->category->name); ?></p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Objectif</p>
                                    <p class="font-semibold text-gray-900"><?php echo e($challenge->books_target); ?> livres</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <p class="text-xs text-gray-500">Participants</p>
                                    <p class="font-semibold text-gray-900"><?php echo e($challenge->participants_count); ?></p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Temps restant</p>
                                    <p class="font-semibold text-gray-900"><?php echo e($challenge->daysRemaining()); ?> jours</p>
                                </div>
                            </div>

                            <!-- Difficulty Badge -->
                            <div class="mb-4">
                                <span class="text-sm"><?php echo e($challenge->difficulty_badge); ?></span>
                            </div>

                            <!-- Action Button -->
                            <?php
                                $userParticipation = $userParticipations->where('challenge_id', $challenge->id)->first();
                            ?>

                            <?php if($userParticipation): ?>
                                <a href="<?php echo e(route('challenges.show', $challenge->id)); ?>" 
                                   class="block w-full text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                                    Voir ma progression
                                </a>
                            <?php else: ?>
                                <a href="<?php echo e(route('challenges.show', $challenge->id)); ?>" 
                                   class="block w-full text-center bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition">
                                    Rejoindre le défi
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="bg-gray-50 rounded-lg p-8 text-center">
                <p class="text-gray-500">Aucun défi actif pour le moment.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Upcoming Challenges -->
    <?php if($upcomingChallenges->count() > 0): ?>
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">🔜 Défis à Venir</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__currentLoopData = $upcomingChallenges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $challenge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden opacity-75">
                        <div class="h-2 bg-gray-300"></div>
                        
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-4xl"><?php echo e($challenge->badge_icon ?? '🏆'); ?></span>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Bientôt
                                </span>
                            </div>

                            <h3 class="text-xl font-bold text-gray-900 mb-2"><?php echo e($challenge->challenge_name); ?></h3>
                            <p class="text-sm text-gray-600 mb-4"><?php echo e(Str::limit($challenge->description, 100)); ?></p>

                            <div class="mb-4">
                                <p class="text-xs text-gray-500">Début le</p>
                                <p class="font-semibold text-gray-900"><?php echo e($challenge->start_date->format('d/m/Y')); ?></p>
                            </div>

                            <button disabled class="block w-full text-center bg-gray-300 text-gray-600 py-2 rounded-lg cursor-not-allowed">
                                Pas encore disponible
                            </button>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo\Desktop\bookShare\projet_laravel\resources\views/challenges/index.blade.php ENDPATH**/ ?>