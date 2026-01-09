<table class="table table-hover table-centered">
    <thead class="table-light">
        <tr>
            <th>Name</th>
            <th>Code</th>
            <th>Email</th>
            <th>Contact</th>
            <th>Other Phone</th>
            <th>WhatsApp</th>
            <th>DOB</th>
            <th>Type</th>
            <th>Company</th>
            <th>Address</th>
            <th>Country</th>
            <th>Service</th>
            <th>Heard Us</th>
            <th>DOE</th>
            <th>Portal</th>
            <th>Updated</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr id="customer-<?php echo e($customer->id); ?>">
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <img src="<?php echo e(asset('/images/users/avatar-6.jpg')); ?>" alt="<?php echo e($customer->name); ?>"
                            class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                        <span><?php echo e($customer->name); ?></span>
                    </div>
                </td>
                <td><?php echo e($customer->customer_code ?? '-'); ?></td>
                <td><?php echo e($customer->email ?? '-'); ?></td>
                <td><?php echo e($customer->contact ?? '-'); ?></td>
                <td><?php echo e($customer->other_phone ?? '-'); ?></td>
                <td><?php echo e($customer->whatsapp_number ?? '-'); ?></td>
                <td><?php echo e($customer->date_of_birth ? \Carbon\Carbon::parse($customer->date_of_birth)->format('d M Y') : '-'); ?>

                </td>
                <td><?php echo e(ucfirst($customer->type)); ?></td>
                <td><?php echo e($customer->company_name ?? '-'); ?></td>
                <td><?php echo e($customer->address); ?></td>
                <td><?php echo e($customer->country ?? '-'); ?></td>
                <td><?php echo e($customer->service ?? '-'); ?></td>
                <td><?php echo e($customer->heard_us ?? '-'); ?></td>
                <td><?php echo e($customer->date_of_entry ? \Carbon\Carbon::parse($customer->date_of_entry)->format('d M Y, h:i A') : '-'); ?>

                </td>
                <td><?php echo e($customer->portal ?? '-'); ?></td>
                <td><?php echo e($customer->updated_at->format('d M Y, h:i A')); ?></td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm delete-customer" data-id="<?php echo e($customer->id); ?>">
                        Delete
                    </button>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="16" class="text-center text-muted">No customers found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Pagination -->
<div class="d-flex justify-content-end mt-3">
    <?php echo e($customers->links()); ?>

</div>
<?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/customer/index-table.blade.php ENDPATH**/ ?>