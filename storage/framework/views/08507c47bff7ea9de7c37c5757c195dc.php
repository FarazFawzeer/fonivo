<table class="table table-hover table-centered">
    <thead class="table-light">
        <tr>
            <th>Name</th>
            <th>Code</th>
            <th>Type</th>
            <th>Owner</th>
            <th>Purchase Date</th>
            <th>Cost</th>
            <th>Selling</th>
            <th>Profit</th>
            <th>Image</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr id="product-<?php echo e($product->id); ?>">
                <td>
                    <div class="d-flex align-items-center gap-2">

                        <span><?php echo e($product->name); ?></span>
                    </div>
                </td>

                <td>
                    <div class="d-flex align-items-center gap-2">

                        <span><?php echo e($product->product_code); ?></span>
                    </div>
                </td>


                <td><?php echo e(ucfirst($product->product_type)); ?></td>

                <td>
                    <?php echo e($product->owner_name); ?> <br>
                    <small class="text-muted"><?php echo e($product->owner_contact); ?></small>
                </td>

                <td><?php echo e(\Carbon\Carbon::parse($product->purchase_date)->format('d M Y')); ?></td>

                <td><?php echo e(number_format($product->cost_price, 2)); ?></td>
                <td><?php echo e(number_format($product->selling_price, 2)); ?></td>

                <td class="fw-bold text-success">
                    <?php echo e(number_format($product->selling_price - $product->cost_price, 2)); ?>

                </td>

                <td>
                    <div class="d-flex align-items-center gap-2">
                        <?php
                            $images = is_array($product->images) ? $product->images : [];
                            $image = count($images)
                                ? asset('storage/' . $images[0])
                                : asset('images/default-product.png');
                        ?>

                        <img src="<?php echo e($image); ?>" class="rounded" style="width:40px;height:40px;object-fit:cover;">


                    </div>
                </td>
                <td>
                    <span
                        class="badge  bg-<?php echo e($product->stock_status == 'available'
                            ? 'success'
                            : ($product->stock_status == 'sold'
                                ? 'danger'
                                : 'secondary')); ?>" style="width: 80px;">
                        <?php echo e(ucfirst(str_replace('_', ' ', $product->stock_status))); ?>

                    </span>
                </td>



                <td>
                    <div class="d-flex gap-1">
                        <a href="<?php echo e(route('admin.products.show', $product->id)); ?>" class="btn btn-sm btn-outline-info">
                            <i class="bi bi-eye"></i>
                        </a>

                        <a href="<?php echo e(route('admin.products.edit', $product->id)); ?>"
                            class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil-square"></i>
                        </a>

                        <button class="btn btn-sm btn-outline-danger delete-product" data-id="<?php echo e($product->id); ?>">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="10" class="text-center text-muted">No products found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<div class="d-flex justify-content-end mt-3">
    <?php echo e($products->links()); ?>

</div>
<?php /**PATH F:\Personal Projects\Fonivo lk\fonivo\resources\views/products/index-table.blade.php ENDPATH**/ ?>