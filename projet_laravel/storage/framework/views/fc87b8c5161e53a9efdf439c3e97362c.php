<?php if($paginator->hasPages()): ?>
    <nav role="navigation" aria-label="<?php echo e(__('Pagination Navigation')); ?>" class="modern-pagination">
        <ul class="pagination-modern">
            
            <?php if($paginator->onFirstPage()): ?>
                <li class="page-item-modern disabled" aria-disabled="true">
                    <span class="page-link-modern">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </span>
                </li>
            <?php else: ?>
                <li class="page-item-modern">
                    <a class="page-link-modern" href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </a>
                </li>
            <?php endif; ?>

            
            <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                
                <?php if(is_string($element)): ?>
                    <li class="page-item-modern disabled" aria-disabled="true">
                        <span class="page-link-modern"><?php echo e($element); ?></span>
                    </li>
                <?php endif; ?>

                
                <?php if(is_array($element)): ?>
                    <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($page == $paginator->currentPage()): ?>
                            <li class="page-item-modern active" aria-current="page">
                                <span class="page-link-modern"><?php echo e($page); ?></span>
                            </li>
                        <?php else: ?>
                            <li class="page-item-modern">
                                <a class="page-link-modern" href="<?php echo e($url); ?>"><?php echo e($page); ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <?php if($paginator->hasMorePages()): ?>
                <li class="page-item-modern">
                    <a class="page-link-modern" href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>
                </li>
            <?php else: ?>
                <li class="page-item-modern disabled" aria-disabled="true">
                    <span class="page-link-modern">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </span>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

    <style>
        /* Modern Pagination Styles */
        .modern-pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 2rem 0;
        }

        .pagination-modern {
            display: flex;
            align-items: center;
            gap: 10px;
            list-style: none;
            padding: 0;
            margin: 0;
            font-family: 'Inter', 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .page-item-modern {
            display: inline-flex;
        }

        .page-link-modern {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 0 12px;
            font-size: 15px;
            font-weight: 500;
            color: #64748b;
            background-color: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        /* Hover effect for non-active items */
        .page-item-modern:not(.active):not(.disabled) .page-link-modern:hover {
            background: linear-gradient(135deg, #4A90E2 0%, #9B59B6 100%);
            color: white;
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(74, 144, 226, 0.3);
        }

        /* Active page style */
        .page-item-modern.active .page-link-modern {
            background: linear-gradient(135deg, #4A90E2 0%, #9B59B6 100%);
            color: white;
            border-color: transparent;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(74, 144, 226, 0.35);
        }

        /* Disabled state */
        .page-item-modern.disabled .page-link-modern {
            color: #cbd5e1;
            background-color: #f8fafc;
            border-color: #f1f5f9;
            cursor: not-allowed;
            opacity: 0.6;
        }

        /* Arrow SVG styling */
        .page-link-modern svg {
            width: 16px;
            height: 16px;
            stroke-width: 2.5;
        }

        /* Responsive adjustments */
        @media (max-width: 576px) {
            .pagination-modern {
                gap: 6px;
            }

            .page-link-modern {
                min-width: 36px;
                height: 36px;
                font-size: 14px;
                padding: 0 10px;
            }

            .page-link-modern svg {
                width: 14px;
                height: 14px;
            }
        }

        /* Three dots separator */
        .page-item-modern.disabled .page-link-modern {
            min-width: auto;
            padding: 0 8px;
        }
    </style>
<?php endif; ?>
<?php /**PATH C:\Users\Lenovo\Desktop\bookShare\projet_laravel\resources\views/vendor/pagination/bootstrap-5.blade.php ENDPATH**/ ?>