

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', [
        'title' => 'Product',
        'subtitle' => 'Details',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="card shadow-sm">
        
        <div class="card-header d-flex justify-content-between align-items-center bg-light">
            <h5 class="mb-0 fw-semibold">Product Overview</h5>
            <a href="<?php echo e(route('admin.products.index')); ?>" class="btn btn-outline-secondary btn-sm">
                Back
            </a>
        </div>

        
        <div class="card-body">

            
            <div class="mb-4">
                <h6 class="text-uppercase text-muted fw-semibold mb-3">Basic Information</h6>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="info-item">
                            <span class="label">Product Type</span>
                            <span class="value"><?php echo e(ucfirst($product->product_type)); ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <span class="label">Product Name</span>
                            <span class="value"><?php echo e($product->name); ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <span class="label">Owner Name</span>
                            <span class="value"><?php echo e($product->owner_name); ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <span class="label">Owner Contact</span>
                            <span class="value"><?php echo e($product->owner_contact); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="mb-4">
                <h6 class="text-uppercase text-muted fw-semibold mb-3">Pricing & Status</h6>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="info-item">
                            <span class="label">Purchase Date</span>
                            <span class="value"><?php echo e($product->purchase_date->format('d M Y')); ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <span class="label">Stock Status</span>
                            <span class="value">
                                <span
                                    class="badge rounded-pill bg-<?php echo e($product->stock_status == 'available'
                                        ? 'success'
                                        : ($product->stock_status == 'sold'
                                            ? 'danger'
                                            : 'warning')); ?>">
                                    <?php echo e(ucfirst(str_replace('_', ' ', $product->stock_status))); ?>

                                </span>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <span class="label">Cost Price</span>
                            <span class="value">Rs. <?php echo e(number_format($product->cost_price, 2)); ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <span class="label">Selling Price</span>
                            <span class="value">Rs. <?php echo e(number_format($product->selling_price, 2)); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            
            <?php if(!empty($product->images) && is_array($product->images)): ?>
                <div class="mb-4">
                    <h6 class="text-uppercase text-muted fw-semibold mb-3">Product Images</h6>
                    <div class="row g-3">
                        <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-3 col-sm-6">
                                <div class="image-box">
                                    <img src="<?php echo e(asset('storage/' . $image)); ?>" class="img-fluid rounded"
                                        alt="Product Image">
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>

    
    <style>
        .info-item {
            background: #f9fafb;
            border-radius: 8px;
            padding: 14px 16px;
            height: 100%;
        }

        .info-item .label {
            display: block;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #6c757d;
            margin-bottom: 4px;
        }

        .info-item .value {
            font-size: 15px;
            font-weight: 500;
            color: #212529;
        }

        .image-box {
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 8px;
            background: #fff;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .image-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, .08);
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Product Details'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Fonivo lk\fonivo\resources\views/products/show.blade.php ENDPATH**/ ?>