

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Product', 'subtitle' => 'Edit'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Edit Product</h5>
        </div>

        <div class="card-body">
            <div id="message"></div>

            <form id="editProductForm" action="<?php echo e(route('admin.products.update', $product->id)); ?>" method="POST"
                enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="row">
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Product Type</label>
                        <select name="product_type" class="form-select" required>
                            <option value="phone" <?php echo e($product->product_type == 'phone' ? 'selected' : ''); ?>>Phone</option>
                            <option value="bike" <?php echo e($product->product_type == 'bike' ? 'selected' : ''); ?>>Bike</option>
                        </select>
                    </div>


                    
<div class="col-md-6 mb-3">
    <label class="form-label">Product Code</label>
    <input type="text" name="product_code" class="form-control"
           value="<?php echo e(old('product_code', $product->product_code)); ?>" required>
</div>


                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Product Name / Model</label>
                        <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $product->name)); ?>"
                            required>
                    </div>

                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Owner Name</label>
                        <input type="text" name="owner_name" class="form-control"
                            value="<?php echo e(old('owner_name', $product->owner_name)); ?>" required>
                    </div>

                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Owner Contact</label>
                        <input type="text" name="owner_contact" class="form-control"
                            value="<?php echo e(old('owner_contact', $product->owner_contact)); ?>" required>
                    </div>

                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Purchase Date</label>
                        <input type="date" name="purchase_date" class="form-control"
                            value="<?php echo e(old('purchase_date', $product->purchase_date->format('Y-m-d'))); ?>" required>
                    </div>

                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Cost Price</label>
                        <input type="number" step="0.01" name="cost_price" class="form-control"
                            value="<?php echo e(old('cost_price', $product->cost_price)); ?>" required>
                    </div>

                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Selling Price</label>
                        <input type="number" step="0.01" name="selling_price" class="form-control"
                            value="<?php echo e(old('selling_price', $product->selling_price)); ?>" required>
                    </div>

                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stock Status</label>
                        <select name="stock_status" class="form-select" required>
                            <option value="available" <?php echo e($product->stock_status == 'available' ? 'selected' : ''); ?>>
                                Available</option>
                            <option value="sold" <?php echo e($product->stock_status == 'sold' ? 'selected' : ''); ?>>Sold</option>
                            <option value="out_of_stock" <?php echo e($product->stock_status == 'out_of_stock' ? 'selected' : ''); ?>>
                                Out of Stock</option>
                        </select>
                    </div>

                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Add More Images</label>
                        <input type="file" name="images[]" class="form-control" multiple>
                    </div>
                </div>

                

<?php if(!empty($product->images) && is_array($product->images)): ?>
    <div class="row mb-3">
        <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-2 mb-2">
                <img src="<?php echo e(asset('storage/' . $img)); ?>" class="img-fluid rounded" style="width:100px;height:100px;object-fit:cover;">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remove_images[]" value="<?php echo e($key); ?>" id="removeImage<?php echo e($key); ?>">
                    <label class="form-check-label" for="removeImage<?php echo e($key); ?>">
                        Remove
                    </label>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>


                <div class="d-flex justify-content-end gap-2">
                    <a href="<?php echo e(route('admin.products.index')); ?>" class="btn btn-secondary" style="width: 120px;">
                        Back
                    </a>

                    <button type="submit" class="btn btn-primary" style="width: 120px;">
                        Update
                    </button>
                </div>

            </form>
        </div>
    </div>

    
    <script>
        document.getElementById('editProductForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('message').innerHTML =
                        `<div class="alert alert-${data.success ? 'success' : 'danger'}">${data.message}</div>`;
                });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Product Edit'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Fonivo lk\fonivo\resources\views/products/edit.blade.php ENDPATH**/ ?>