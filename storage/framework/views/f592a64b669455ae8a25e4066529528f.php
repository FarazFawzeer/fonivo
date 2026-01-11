

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Product', 'subtitle' => 'View'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Product List</h5>
            <p class="card-subtitle">All phones and bikes in your inventory.</p>
        </div>

        <div class="card-body">

            <!-- Filters -->
       <!-- Filters -->
<div class="row mb-3 justify-content-end">
    <div class="col-md-3">
        <label class="form-label">Product Type</label>
        <select id="filterProductType" class="form-select">
            <option value="">All</option>
            <option value="phone">Phone</option>
            <option value="bike">Bike</option>
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label">Stock Status</label>
        <select id="filterStockStatus" class="form-select">
            <option value="">All</option>
            <option value="available">Available</option>
            <option value="sold">Sold</option>
            <option value="out_of_stock">Out of Stock</option>
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label">Search</label>
        <input type="text" id="productSearch" class="form-control" placeholder="Search by name, code, owner...">
    </div>
</div>

            <!-- Table -->
            <div class="table-responsive" id="productTable">
                <?php echo $__env->make('products.index-table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>
    </div>

 <script>
document.addEventListener('DOMContentLoaded', function () {

    const typeFilter = document.getElementById('filterProductType');
    const stockFilter = document.getElementById('filterStockStatus');
    const searchInput = document.getElementById('productSearch');

    function fetchProducts(url = null) {
        let type = typeFilter.value;
        let stock = stockFilter.value;
        let search = searchInput.value;

        url = url || "<?php echo e(route('admin.products.index')); ?>";
        url += `?product_type=${type}&stock_status=${stock}&search=${encodeURIComponent(search)}`;

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.text())
        .then(html => {
            document.getElementById('productTable').innerHTML = html;
        });
    }

    // Filters
    typeFilter.addEventListener('change', fetchProducts);
    stockFilter.addEventListener('change', fetchProducts);

    // Search input
    searchInput.addEventListener('keyup', function() {
        fetchProducts();
    });

    // Pagination AJAX
    document.addEventListener('click', function (e) {
        if (e.target.closest('#productTable .pagination a')) {
            e.preventDefault();
            fetchProducts(e.target.getAttribute('href'));
        }
    });
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Product View'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Fonivo lk\fonivo\resources\views/products/index.blade.php ENDPATH**/ ?>