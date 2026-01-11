

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Product', 'subtitle' => 'Create'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">New Product</h5>
        </div>

        <div class="card-body">
            <div id="message"></div> 

            <form id="createProductForm" action="<?php echo e(route('admin.products.store')); ?>" method="POST"
                enctype="multipart/form-data">
                <?php echo csrf_field(); ?>

                <div class="row">

                    
                    
                    <div class="col-md-6 mb-3">
                        <label for="product_type" class="form-label">Product Type</label>
                        <select name="product_type" id="product_type" class="form-select" required>
                            <option value="">Select Type</option>
                            <option value="phone" <?php echo e(old('product_type') == 'phone' ? 'selected' : ''); ?>>Phone</option>
                            <option value="bike" <?php echo e(old('product_type') == 'bike' ? 'selected' : ''); ?>>Bike</option>
                        </select>
                        
                    </div>


                     
    <div class="col-md-6 mb-3">
        <label for="product_code" class="form-label">Product Code</label>
        <input type="text" name="product_code" id="product_code" class="form-control"
               value="<?php echo e(old('product_code')); ?>" placeholder="Ex: P1001" required>
    </div>
    
                    
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Product Name / Model</label>
                        <input type="text" name="name" id="name" class="form-control" value="<?php echo e(old('name')); ?>"
                            placeholder="Ex: iPhone 15" required>
                    </div>

                    
                    <div class="col-md-6 mb-3">
                        <label for="owner_name" class="form-label">Owner Name</label>
                        <input type="text" name="owner_name" id="owner_name" class="form-control"
                            value="<?php echo e(old('owner_name')); ?>" placeholder="Ex: John Doe" required>
                    </div>

                    
                    <div class="col-md-6 mb-3">
                        <label for="owner_contact" class="form-label">Owner Contact</label>
                        <input type="text" name="owner_contact" id="owner_contact" class="form-control"
                            value="<?php echo e(old('owner_contact')); ?>" placeholder="Ex: +94771234567" required>
                    </div>

                    
                    <div class="col-md-6 mb-3">
                        <label for="purchase_date" class="form-label">Purchase Date</label>
                        <input type="date" name="purchase_date" id="purchase_date" class="form-control"
                            value="<?php echo e(old('purchase_date')); ?>" required>
                    </div>

                    
                    <div class="col-md-6 mb-3">
                        <label for="cost_price" class="form-label">Cost Price</label>
                        <input type="number" step="0.01" name="cost_price" id="cost_price" class="form-control"
                            value="<?php echo e(old('cost_price')); ?>" placeholder="Ex: 1500.00" required>
                    </div>

                    
                    <div class="col-md-6 mb-3">
                        <label for="selling_price" class="form-label">Selling Price</label>
                        <input type="number" step="0.01" name="selling_price" id="selling_price" class="form-control"
                            value="<?php echo e(old('selling_price')); ?>" placeholder="Ex: 1800.00" required>
                    </div>

                    
                    <div class="col-md-6 mb-3">
                        <label for="stock_status" class="form-label">Stock Status</label>
                        <select name="stock_status" id="stock_status" class="form-select" required>
                            <option value="available" <?php echo e(old('stock_status') == 'available' ? 'selected' : ''); ?>>Available
                            </option>
                            <option value="sold" <?php echo e(old('stock_status') == 'sold' ? 'selected' : ''); ?>>Sold</option>
                            <option value="out_of_stock" <?php echo e(old('stock_status') == 'out_of_stock' ? 'selected' : ''); ?>>Out
                                of Stock</option>
                        </select>
                    </div>

                    
                    <div class="col-md-6 mb-3">
                        <label for="images" class="form-label">Product Images</label>
                        <input type="file" name="images[]" id="images" class="form-control" multiple>
                        <small class="text-muted">You can upload multiple images.</small>
                    </div>
                </div>

                
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Create Product</button>
                </div>
            </form>

        </div>
    </div>

    
    <script>
        document.getElementById('createProductForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let form = this;
            let formData = new FormData(form);

            fetch(form.action, {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                        "Accept": "application/json"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const messageBox = document.getElementById('message');

                    if (data.success) {
                        messageBox.innerHTML = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                                        ${data.message}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>`;
                        form.reset();
                        setTimeout(() => {
                            messageBox.innerHTML = "";
                        }, 5000);
                    } else {
                        let errors = data.errors ? data.errors.join('<br>') : data.message;
                        messageBox.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        ${errors}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>`;
                    }
                })
                .catch(error => {
                    document.getElementById('message').innerHTML =
                        `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Something went wrong. Please try again.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`;
                    console.error(error);
                });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Product Create'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Fonivo lk\fonivo\resources\views/products/create.blade.php ENDPATH**/ ?>