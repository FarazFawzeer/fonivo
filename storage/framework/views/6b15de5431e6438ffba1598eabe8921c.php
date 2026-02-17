

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Reports', 'subtitle' => 'Stock'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Stock Report</h5>
            <p class="card-subtitle mb-0">Phones by status + accessories current stock + low stock.</p>
        </div>

        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-4"><b>Phones Available:</b> <?php echo e((int) ($phoneCounts->available ?? 0)); ?></div>
                <div class="col-md-4"><b>Phones Sold:</b> <?php echo e((int) ($phoneCounts->sold ?? 0)); ?></div>
                <div class="col-md-4"><b>Phones Reserved:</b> <?php echo e((int) ($phoneCounts->reserved ?? 0)); ?></div>
            </div>

            <form method="GET" action="<?php echo e(route('admin.reports.stock')); ?>" class="row g-2 mb-3">
                <div class="col-md-3">
                    <label class="form-label">Low stock threshold</label>
                <input type="number" name="low" class="form-control" value="<?php echo e($lowDefault); ?>">
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button class="btn btn-primary w-100" type="submit">Apply</button>
                    <a href="<?php echo e(route('admin.reports.stock')); ?>" class="btn btn-light w-100">Reset</a>
                </div>
            </form>

            <h6 class="mb-2">Accessories Stock</h6>
            <div class="table-responsive">
                <table class="table table-hover table-centered">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th class="text-end">In</th>
                            <th class="text-end">Out</th>
                            <th class="text-end">Current</th>
                            <th class="text-end">Reorder</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Low</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $accStocks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <b><?php echo e($p->name); ?></b>
                                    <div class="text-muted small"><?php echo e($p->brand ?? ''); ?> <?php echo e($p->model ?? ''); ?></div>
                                </td>
                                <td><?php echo e($p->sku ?? '-'); ?></td>
                                <td class="text-end"><?php echo e((int) ($p->total_in ?? 0)); ?></td>
                                <td class="text-end"><?php echo e((int) ($p->total_out ?? 0)); ?></td>
                                <td class="text-end"><?php echo e((int) ($p->current_stock ?? 0)); ?></td>
                                <td class="text-end"><?php echo e((int) ($p->threshold ?? $lowDefault)); ?></td>
                                <td class="text-center">
                                    <?php if($p->is_active): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if($p->is_low): ?>
                                        <span class="badge bg-danger">LOW</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">OK</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">No products found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Stock Report'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Fonivo lk\fonivo\resources\views/admin/reports/stock.blade.php ENDPATH**/ ?>