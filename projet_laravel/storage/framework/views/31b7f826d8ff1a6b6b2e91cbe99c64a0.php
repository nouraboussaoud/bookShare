<?php $__env->startSection('title', 'Ajouter un livre'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Nouveau Livre</h6>
                <a href="<?php echo e(route('books.index')); ?>" class="btn btn-sm btn-outline-secondary">Retour</a>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('books.store')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label class="form-label">Titre</label>
                        <input type="text" name="title" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('title')); ?>" required>
                        <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Auteur</label>
                        <input type="text" name="author" class="form-control <?php $__errorArgs = ['author'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('author')); ?>" required>
                        <?php $__errorArgs = ['author'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catégorie</label>
                        <select name="category_id" class="form-select <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">Sélectionner une catégorie (optionnel)</option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id') == $category->id ? 'selected' : ''); ?>>
                                    <?php echo e($category->name); ?> 
                                    <?php if($category->age_allowed > 0): ?>
                                        (<?php echo e($category->age_allowed); ?>+)
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Âge recommandé <span class="text-danger">*</span></label>
                        <select name="recommended_age" class="form-select <?php $__errorArgs = ['recommended_age'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="0" <?php echo e(old('recommended_age') == '0' ? 'selected' : ''); ?>>Tout âge</option>
                            <option value="6" <?php echo e(old('recommended_age') == '6' ? 'selected' : ''); ?>>6+</option>
                            <option value="9" <?php echo e(old('recommended_age') == '9' ? 'selected' : ''); ?>>9+</option>
                            <option value="12" <?php echo e(old('recommended_age') == '12' ? 'selected' : ''); ?>>12+</option>
                            <option value="13" <?php echo e(old('recommended_age') == '13' ? 'selected' : ''); ?>>13+</option>
                            <option value="15" <?php echo e(old('recommended_age') == '15' ? 'selected' : ''); ?>>15+</option>
                            <option value="16" <?php echo e(old('recommended_age') == '16' ? 'selected' : ''); ?>>16+</option>
                            <option value="18" <?php echo e(old('recommended_age') == '18' ? 'selected' : ''); ?>>18+</option>
                        </select>
                        <?php $__errorArgs = ['recommended_age'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Photo de couverture</label>
                        <input type="file" name="photo" class="form-control <?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept="image/*">
                        <?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="form-text text-muted">Formats acceptés: JPEG, PNG, JPG, GIF, WebP. Taille max: 2MB</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="4" placeholder="Décrivez le livre, son intrigue, ce qui le rend spécial..."><?php echo e(old('description')); ?></textarea>
                        <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="form-text text-muted">Maximum 1000 caractères</small>
                    </div>

                    <!-- Tags Section -->
                    <div class="mb-3" id="tags-section">
                        <label class="form-label">
                            <i class="fas fa-tags"></i> Tags 
                            <small class="text-muted">(Sélectionnez les tags qui correspondent à ce livre)</small>
                        </label>
                        <div id="tags-container" class="border rounded p-3 bg-light">
                            <p class="text-muted text-center" id="no-category-message">
                                <i class="fas fa-info-circle"></i> Sélectionnez d'abord une catégorie pour voir les tags disponibles
                            </p>
                            <div id="tags-list" class="d-none">
                                <!-- Tags will be loaded here dynamically -->
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Statut</label>
                        <select name="status" class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="available" <?php echo e(old('status') == 'available' ? 'selected' : ''); ?>>Disponible</option>
                            <option value="reserved" <?php echo e(old('status') == 'reserved' ? 'selected' : ''); ?>>Réservé</option>
                        </select>
                        <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// Tags data from categories
const categoriesData = <?php echo json_encode($categories->map(function($cat) {
    return [
        'id' => $cat->id,
        'name' => $cat->name,
        'tags' => $cat->categoryTags->map(function($tag) {
            return [
                'id' => $tag->id,
                'name' => $tag->name,
                'color' => $tag->color,
                'icon' => $tag->icon,
                'type' => $tag->type,
                'description' => $tag->description
            ];
        })
    ];
})); ?>;

// Handle category change
document.querySelector('select[name="category_id"]').addEventListener('change', function() {
    const categoryId = parseInt(this.value);
    const tagsListDiv = document.getElementById('tags-list');
    const noMessageDiv = document.getElementById('no-category-message');
    
    if (!categoryId) {
        tagsListDiv.classList.add('d-none');
        noMessageDiv.classList.remove('d-none');
        return;
    }
    
    const category = categoriesData.find(c => c.id === categoryId);
    
    if (!category || category.tags.length === 0) {
        tagsListDiv.innerHTML = '<p class="text-muted text-center"><i class="fas fa-info-circle"></i> Aucun tag disponible pour cette catégorie</p>';
        tagsListDiv.classList.remove('d-none');
        noMessageDiv.classList.add('d-none');
        return;
    }
    
    // Build tags HTML
    let html = '<div class="row">';
    category.tags.forEach(tag => {
        const typeLabel = {
            'genre': '📖 Genre',
            'theme': '🎭 Thème',
            'mood': '😊 Ambiance',
            'pace': '⚡ Rythme',
            'other': '🏷️ Autre'
        }[tag.type] || '🏷️';
        
        html += `
            <div class="col-md-6 mb-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="tags[]" value="${tag.id}" id="tag-${tag.id}">
                    <label class="form-check-label" for="tag-${tag.id}">
                        <span class="badge" style="background-color: ${tag.color}; color: white;">
                            ${tag.icon ? '<i class="' + tag.icon + '"></i>' : ''} ${tag.name}
                        </span>
                        <small class="text-muted d-block">${typeLabel} ${tag.description ? '- ' + tag.description : ''}</small>
                    </label>
                </div>
            </div>
        `;
    });
    html += '</div>';
    
    tagsListDiv.innerHTML = html;
    tagsListDiv.classList.remove('d-none');
    noMessageDiv.classList.add('d-none');
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo\Desktop\bookShare\projet_laravel\resources\views/books/create.blade.php ENDPATH**/ ?>