

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Reports', 'subtitle' => 'Daily Sales'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Daily Sales Summary</h5>
            <p class="card-subtitle mb-0">Totals by day based on sales invoices.</p>
        </div>

        <div class="card-body">

            <form method="GET" action="<?php echo e(route('admin.reports.dailySales')); ?>" class="row g-2 mb-3">
                <div class="col-md-3">
                    <input type="date" name="from" class="form-control" value="<?php echo e($from); ?>">
                </div>
                <div class="col-md-3">
                    <input type="date" name="to" class="form-control" value="<?php echo e($to); ?>">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-primary w-100" type="submit">Filter</button>
                    <a href="<?php echo e(route('admin.reports.dailySales')); ?>" class="btn btn-light w-100">Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover table-centered">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th class="text-end">Sales</th>
                            <th class="text-end">Paid</th>
                            <th class="text-end">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e(\Carbon\Carbon::parse($d->day)->format('d M Y')); ?></td>
                                <td class="text-end"><?php echo e(number_format((float)($d->total_sales ?? 0),2)); ?></td>
                                <td class="text-end"><?php echo e(number_format((float)($d->total_paid ?? 0),2)); ?></td>
                                <td class="text-end"><?php echo e(number_format((float)($d->total_balance ?? 0),2)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="4" class="text-center text-muted">No records found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <div class="d-flex justify-content-end mt-3">
                    <?php echo e($days->links()); ?>

                </div>
            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Daily Sales Summary'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Fonivo lk\fonivo\resources\views/admin/reports/daily_sales.blade.php ENDPATH**/ ?>