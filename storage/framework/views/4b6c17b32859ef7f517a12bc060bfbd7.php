

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Reports', 'subtitle' => 'Profit'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Profit Report</h5>
            <p class="card-subtitle mb-0">
                Phones: sell - purchase snapshot | Accessories: sell - avg purchase cost (temporary).
            </p>
        </div>

        <div class="card-body">

            <form method="GET" action="<?php echo e(route('admin.reports.profit')); ?>" class="row g-2 mb-3">
                <div class="col-md-3">
                    <input type="date" name="from" class="form-control" value="<?php echo e($from); ?>">
                </div>
                <div class="col-md-3">
                    <input type="date" name="to" class="form-control" value="<?php echo e($to); ?>">
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" value="Accessory Avg Cost: <?php echo e(number_format((float)$accAvgCost,2)); ?>" readonly>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-primary w-100" type="submit">Filter</button>
                    <a href="<?php echo e(route('admin.reports.profit')); ?>" class="btn btn-light w-100">Reset</a>
                </div>
            </form>

            <div class="row mb-3">
                <div class="col-md-3"><b>Sales:</b> <?php echo e(number_format((float)$grand['sales_total'],2)); ?></div>
                <div class="col-md-3"><b>Cost:</b> <?php echo e(number_format((float)$grand['cost_total'],2)); ?></div>
                <div class="col-md-3"><b>Profit:</b> <?php echo e(number_format((float)$grand['profit_total'],2)); ?></div>
                <div class="col-md-3">
                    <span class="text-muted">Phones: <?php echo e(number_format((float)$grand['phone_profit'],2)); ?> | Accessories: <?php echo e(number_format((float)$grand['accessory_profit'],2)); ?></span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-centered">
                    <thead class="table-light">
                        <tr>
                            <th>Invoice</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th class="text-end">Sales</th>
                            <th class="text-end">Cost</th>
                            <th class="text-end">Profit</th>
                            <th class="text-center" style="width:80px;">View</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $p = $invoiceProfits[$inv->id] ?? ['sales'=>0,'cost'=>0,'profit'=>0];
                            ?>
                            <tr>
                                <td><b><?php echo e($inv->invoice_no); ?></b></td>
                                <td><?php echo e($inv->sale_date?->format('d M Y') ?? '-'); ?></td>
                                <td><?php echo e($inv->customer?->name ?? '-'); ?></td>
                                <td class="text-end"><?php echo e(number_format((float)$p['sales'],2)); ?></td>
                                <td class="text-end"><?php echo e(number_format((float)$p['cost'],2)); ?></td>
                                <td class="text-end"><?php echo e(number_format((float)$p['profit'],2)); ?></td>
                                <td class="text-center">
                                    <a href="<?php echo e(route('admin.sales.show', $inv->id)); ?>"
                                        style="color:#198754; font-size:18px; display:inline-flex; align-items:center;"
                                        data-bs-toggle="tooltip" title="View Invoice">
                                        <iconify-icon icon="solar:eye-outline"></iconify-icon>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="7" class="text-center text-muted">No invoices found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <?php echo e($sales->links()); ?>

            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Profit Report'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Fonivo lk\fonivo\resources\views/admin/reports/profit.blade.php ENDPATH**/ ?>