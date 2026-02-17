

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Reports', 'subtitle' => 'Due'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title mb-0">Due Report</h5>
            <p class="card-subtitle mb-0">Customers & suppliers who still have balance.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Customers Due (balance &gt; 0)</h6>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-hover table-centered">
                        <thead class="table-light">
                            <tr>
                                <th>Customer</th>
                                <th class="text-end">Sales</th>
                                <th class="text-end">Paid</th>
                                <th class="text-end">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $customerDue; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><b><?php echo e($c->name); ?></b></td>
                                    <td class="text-end"><?php echo e(number_format((float)($c->sales_total ?? 0),2)); ?></td>
                                    <td class="text-end"><?php echo e(number_format((float)($c->paid_total ?? 0),2)); ?></td>
                                    <td class="text-end text-danger"><b><?php echo e(number_format((float)($c->balance ?? 0),2)); ?></b></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="4" class="text-center text-muted">No due customers.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Suppliers Due (balance &gt; 0)</h6>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-hover table-centered">
                        <thead class="table-light">
                            <tr>
                                <th>Supplier</th>
                                <th class="text-end">Purchases</th>
                                <th class="text-end">Paid</th>
                                <th class="text-end">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $supplierDue; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><b><?php echo e($s->name); ?></b></td>
                                    <td class="text-end"><?php echo e(number_format((float)($s->purchase_total ?? 0),2)); ?></td>
                                    <td class="text-end"><?php echo e(number_format((float)($s->paid_total ?? 0),2)); ?></td>
                                    <td class="text-end text-danger"><b><?php echo e(number_format((float)($s->balance ?? 0),2)); ?></b></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="4" class="text-center text-muted">No due suppliers.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Due Report'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Fonivo lk\fonivo\resources\views/admin/reports/due.blade.php ENDPATH**/ ?>