

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Fonivo.lk', 'subtitle' => 'Dashboard'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="row align-items-stretch">
        
        <div class="col-md-6 col-xl-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <div class="avatar-md bg-primary bg-opacity-10 rounded-circle">
                                <iconify-icon icon="solar:money-bag-outline"
                                    class="fs-32 text-primary avatar-title"></iconify-icon>
                            </div>
                        </div>
                        <div class="col-6 text-end">
                            <p class="text-muted mb-0 text-truncate">Today Sales</p>
                            <h3 class="text-dark mt-2 mb-0"><?php echo e(number_format((float) $todaySales, 2)); ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-0 py-2 bg-light bg-opacity-50 mx-2 mb-2">
                    <span class="text-muted fs-12">Total sales for today</span>
                </div>
            </div>
        </div>

        
        <div class="col-md-6 col-xl-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <div class="avatar-md bg-danger bg-opacity-10 rounded-circle">
                                <iconify-icon icon="solar:hand-money-outline"
                                    class="fs-32 text-danger avatar-title"></iconify-icon>
                            </div>
                        </div>
                        <div class="col-6 text-end">
                            <p class="text-muted mb-0 text-truncate">Customer Due</p>
                            <h3 class="text-dark mt-2 mb-0"><?php echo e(number_format((float) $customerDue, 2)); ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-0 py-2 bg-light bg-opacity-50 mx-2 mb-2">
                    <span class="text-muted fs-12">Total outstanding from customers</span>
                </div>
            </div>
        </div>

        
        <div class="col-md-6 col-xl-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <div class="avatar-md bg-warning bg-opacity-10 rounded-circle">
                                <iconify-icon icon="solar:box-outline"
                                    class="fs-32 text-warning avatar-title"></iconify-icon>
                            </div>
                        </div>
                        <div class="col-6 text-end">
                            <p class="text-muted mb-0 text-truncate">Stock Snapshot</p>
                            <h3 class="text-dark mt-2 mb-0">
                                <?php echo e((int) $phonesAvailable); ?>

                            </h3>
                            <div class="text-muted small mt-1">
                                Low Accessories: <b><?php echo e((int) $lowStockCount); ?></b>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card-footer border-0 py-2 bg-light bg-opacity-50 mx-2 mb-2">
                    <span class="text-muted fs-12">Available phones + low stock accessories</span>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row mt-3">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-0">Recent Sales Invoices</h4>
                        <p class="card-subtitle mb-0">Latest invoices from your system.</p>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="<?php echo e(route('admin.sales.index')); ?>" class="btn btn-light btn-sm">
                            View All
                        </a>
                        <a href="<?php echo e(route('admin.sales.create')); ?>" class="btn btn-primary btn-sm">
                            Create Sale
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive table-centered">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Invoice</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-end">Paid</th>
                                    <th class="text-end">Balance</th>
                                    <th>Status</th>
                                    <th class="text-center" style="width:70px;">View</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $recentSales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        $badge =
                                            $s->status === 'paid'
                                                ? 'bg-success'
                                                : ($s->status === 'partial'
                                                    ? 'bg-warning'
                                                    : 'bg-danger');
                                    ?>
                                    <tr>
                                        <td><b><?php echo e($s->invoice_no); ?></b></td>
                                        <td><?php echo e($s->sale_date?->format('d M Y') ?? '-'); ?></td>
                                        <td><?php echo e($s->customer?->name ?? '-'); ?></td>
                                        <td class="text-end"><?php echo e(number_format((float) ($s->total_amount ?? 0), 2)); ?></td>
                                        <td class="text-end"><?php echo e(number_format((float) ($s->paid_amount ?? 0), 2)); ?></td>
                                        <td class="text-end"><?php echo e(number_format((float) ($s->balance_amount ?? 0), 2)); ?></td>
                                        <td><span class="badge <?php echo e($badge); ?>"><?php echo e(ucfirst($s->status)); ?></span></td>
                                        <td class="text-center">
                                            <a href="<?php echo e(route('admin.sales.show', $s->id)); ?>"
                                                style="color:#198754; font-size:18px; display:inline-flex; align-items:center;"
                                                data-bs-toggle="tooltip" title="View">
                                                <iconify-icon icon="solar:eye-outline"></iconify-icon>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No sales found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Dashboard'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Fonivo lk\fonivo\resources\views/dashboard.blade.php ENDPATH**/ ?>