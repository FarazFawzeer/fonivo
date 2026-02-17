

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Ledgers', 'subtitle' => 'Customer'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Customer Ledger</h5>
            <p class="card-subtitle mb-0">Sales (Debit) vs Payments (Credit) with running balance.</p>
        </div>

        <div class="card-body">
            <form method="GET" action="<?php echo e(route('admin.ledgers.customers.index')); ?>" class="row g-2 mb-3">
                <div class="col-md-4">
                    <select name="customer_id" class="form-select" required>
                        <option value="">Select Customer</option>
                        <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($c->id); ?>" <?php echo e((string)request('customer_id') === (string)$c->id ? 'selected' : ''); ?>>
                                <?php echo e($c->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <input type="date" name="from" class="form-control" value="<?php echo e(request('from')); ?>" placeholder="From">
                </div>

                <div class="col-md-3">
                    <input type="date" name="to" class="form-control" value="<?php echo e(request('to')); ?>" placeholder="To">
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button class="btn btn-primary w-100" type="submit">View</button>
                    <a href="<?php echo e(route('admin.ledgers.customers.index')); ?>" class="btn btn-light w-100">Reset</a>
                </div>
            </form>

            <?php if($customer): ?>
                <div class="row mb-3">
                    <div class="col-md-4"><b>Customer:</b> <?php echo e($customer->name); ?></div>
                    <div class="col-md-3"><b>Sales (Debit):</b> <?php echo e(number_format((float)($summary['total_debit'] ?? 0),2)); ?></div>
                    <div class="col-md-3"><b>Payments (Credit):</b> <?php echo e(number_format((float)($summary['total_credit'] ?? 0),2)); ?></div>
                    <div class="col-md-2">
                        <b>Balance:</b>
                        <span class="<?php echo e(((float)($summary['balance'] ?? 0)) > 0 ? 'text-danger' : 'text-success'); ?>">
                            <?php echo e(number_format((float)($summary['balance'] ?? 0),2)); ?>

                        </span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-centered">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Ref</th>
                                <th>Note</th>
                                <th class="text-end">Debit</th>
                                <th class="text-end">Credit</th>
                                <th class="text-end">Balance</th>
                                <th class="text-center" style="width:80px;">Link</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($r['date']?->format('d M Y') ?? '-'); ?></td>
                                    <td><b><?php echo e($r['ref']); ?></b></td>
                                    <td class="text-muted"><?php echo e($r['note'] ?? '-'); ?></td>
                                    <td class="text-end"><?php echo e(number_format((float)$r['debit'],2)); ?></td>
                                    <td class="text-end"><?php echo e(number_format((float)$r['credit'],2)); ?></td>
                                    <td class="text-end"><?php echo e(number_format((float)$r['balance'],2)); ?></td>
                                    <td class="text-center">
                                        <?php if($r['type'] === 'sale'): ?>
                                            <a href="<?php echo e(route('admin.sales.show', $r['related_id'])); ?>"
                                                style="color:#198754; font-size:18px; display:inline-flex; align-items:center;"
                                                data-bs-toggle="tooltip" title="View Invoice">
                                                <iconify-icon icon="solar:eye-outline"></iconify-icon>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="7" class="text-center text-muted">No records found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info mb-0">Select a customer to view ledger.</div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Customer Ledger'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Fonivo lk\fonivo\resources\views/admin/ledgers/customers.blade.php ENDPATH**/ ?>