<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">🏆 Mes Défis de Lecture</h1>
        <p class="text-gray-600">Suivez votre progression et vos accomplissements</p>
    </div>

    <!-- Navigation Tabs -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <a href="<?php echo e(route('challenges.index')); ?>" class="border-transparent py-4 px-1 text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Tous les Défis
            </a>
            <a href="<?php echo e(route('challenges.my-challenges')); ?>" class="border-b-2 border-blue-500 py-4 px-1 text-blue-600 font-medium">
                Mes Défis
            </a>
        </nav>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Défis Actifs</p>
                    <p class="text-3xl font-bold mt-2"><?php echo e($activeChallenges->count()); ?></p>
                </div>
                <div class="text-5xl opacity-50">🔥</div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Défis Complétés</p>
                    <p class="text-3xl font-bold mt-2"><?php echo e($completedChallenges->count()); ?></p>
                </div>
                <div class="text-5xl opacity-50">✅</div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm">Total Points</p>
                    <p class="text-3xl font-bold mt-2"><?php echo e($totalPoints); ?></p>
                </div>
                <div class="text-5xl opacity-50">⭐</div>
            </div>
        </div>
    </div>

    <!-- Active Challenges -->
    <?php if($activeChallenges->count() > 0): ?>
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">🔥 Mes Défis Actifs</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__currentLoopData = $activeChallenges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $participation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $challenge = $participation->challenge;
                    ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                        <!-- Header with category color -->
                        <div class="h-2" style="background-color: <?php echo e($challenge->category->color ?? '#3B82F6'); ?>"></div>
                        
                        <div class="p-6">
                            <!-- Badge Icon -->
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-4xl"><?php echo e($challenge->badge_icon ?? '🏆'); ?></span>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    En cours
                                </span>
                            </div>

                            <!-- Challenge Info -->
                            <h3 class="text-xl font-bold text-gray-900 mb-2"><?php echo e($challenge->challenge_name); ?></h3>
                            <p class="text-sm text-gray-600 mb-4"><?php echo e($challenge->category->name); ?></p>

                            <!-- Progress Bar -->
                            <div class="mb-4">
                                <div class="flex justify-between text-sm text-gray-600 mb-2">
                                    <span>Progression</span>
                                    <span class="font-semibold"><?php echo e($participation->books_completed); ?>/<?php echo e($challenge->books_target); ?> livres</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-blue-600 h-3 rounded-full transition-all duration-500" 
                                         style="width: <?php echo e($participation->progress_percentage); ?>%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1 text-right"><?php echo e($participation->progress_percentage); ?>%</p>
                            </div>

                            <!-- Stats -->
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <p class="text-xs text-gray-500">Livres restants</p>
                                    <p class="font-semibold text-gray-900"><?php echo e($participation->booksRemaining()); ?></p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Temps restant</p>
                                    <p class="font-semibold text-gray-900"><?php echo e($challenge->daysRemaining()); ?> jours</p>
                                </div>
                            </div>

                            <!-- Books Read -->
                            <?php if($participation->progress->count() > 0): ?>
                                <div class="mb-4">
                                    <p class="text-xs text-gray-500 mb-2">Livres lus:</p>
                                    <div class="flex flex-wrap gap-2">
                                        <?php $__currentLoopData = $participation->progress->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $progress): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="text-xs bg-gray-100 px-2 py-1 rounded">
                                                📚 <?php echo e(Str::limit($progress->book->titre ?? 'Livre', 15)); ?>

                                            </span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($participation->progress->count() > 3): ?>
                                            <span class="text-xs text-gray-500">+<?php echo e($participation->progress->count() - 3); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Action Button -->
                            <a href="<?php echo e(route('challenges.show', $challenge->id)); ?>" 
                               class="block w-full text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                                Continuer le défi
                            </a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php else: ?>
        <div class="bg-gray-50 rounded-lg p-12 text-center mb-12">
            <div class="text-6xl mb-4">📚</div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Aucun défi actif</h3>
            <p class="text-gray-600 mb-6">Rejoignez un défi pour commencer votre aventure de lecture!</p>
            <a href="<?php echo e(route('challenges.index')); ?>" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                Découvrir les défis
            </a>
        </div>
    <?php endif; ?>

    <!-- Completed Challenges -->
    <?php if($completedChallenges->count() > 0): ?>
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">🏆 Défis Complétés</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__currentLoopData = $completedChallenges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $participation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $challenge = $participation->challenge;
                    ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border-2 border-green-200">
                        <div class="h-2 bg-gradient-to-r from-green-400 to-green-600"></div>
                        
                        <div class="p-6">
                            <!-- Badge Icon with Success -->
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-4xl"><?php echo e($challenge->badge_icon ?? '🏆'); ?></span>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    ✓ Complété
                                </span>
                            </div>

                            <!-- Challenge Info -->
                            <h3 class="text-xl font-bold text-gray-900 mb-2"><?php echo e($challenge->challenge_name); ?></h3>
                            <p class="text-sm text-gray-600 mb-4"><?php echo e($challenge->category->name); ?></p>

                            <!-- Completion Stats -->
                            <div class="bg-green-50 rounded-lg p-4 mb-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm text-gray-600">Badge gagné</span>
                                    <span class="text-2xl"><?php echo e($challenge->badge_icon ?? '🏆'); ?></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Points gagnés</span>
                                    <span class="text-lg font-bold text-green-600">+<?php echo e($participation->points_earned); ?></span>
                                </div>
                            </div>

                            <!-- Completion Date -->
                            <p class="text-xs text-gray-500 mb-4">
                                Complété le <?php echo e($participation->completed_at->format('d/m/Y')); ?>

                            </p>

                            <!-- View Button -->
                            <a href="<?php echo e(route('challenges.show', $challenge->id)); ?>" 
                               class="block w-full text-center bg-gray-100 text-gray-700 py-2 rounded-lg hover:bg-gray-200 transition">
                                Voir les détails
                            </a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Badges Earned -->
    <?php if($totalBadges > 0): ?>
        <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg p-8 text-white text-center">
            <h3 class="text-2xl font-bold mb-4">🎉 Vos Accomplissements</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div>
                    <p class="text-4xl font-bold"><?php echo e($totalBadges); ?></p>
                    <p class="text-sm opacity-90">Badges Gagnés</p>
                </div>
                <div>
                    <p class="text-4xl font-bold"><?php echo e($totalPoints); ?></p>
                    <p class="text-sm opacity-90">Points Totaux</p>
                </div>
                <div>
                    <p class="text-4xl font-bold"><?php echo e($activeChallenges->count() + $completedChallenges->count()); ?></p>
                    <p class="text-sm opacity-90">Défis Rejoints</p>
                </div>
                <div>
                    <p class="text-4xl font-bold"><?php echo e($completedChallenges->sum(fn($p) => $p->books_completed)); ?></p>
                    <p class="text-sm opacity-90">Livres Lus</p>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo\Desktop\bookShare\projet_laravel\resources\views/challenges/my-challenges.blade.php ENDPATH**/ ?>