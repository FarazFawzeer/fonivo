

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Customer', 'subtitle' => 'View'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Customer List</h5>
            <p class="card-subtitle">All customers in your system with details.</p>
        </div>

        <div class="card-body">
            <!-- Filters -->
            <div class="row mb-3 justify-content-end">
                <div class="col-md-3">
                    <label for="filterType" class="form-label">Type</label>
                    <select id="filterType" class="form-select">
                        <option value="">All</option>
                        <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($type); ?>"><?php echo e(ucfirst($type)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-3 d-none" id="serviceFilterWrapper">
                    <label for="filterService" class="form-label">Service</label>
                    <select id="filterService" class="form-select">
                        <option value="">All</option>
                        <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($service); ?>"><?php echo e($service); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="filterPortal" class="form-label">Portal</label>
                    <select id="filterPortal" class="form-select">
                        <option value="">All</option>
                        <?php $__currentLoopData = $portals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $portal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($portal); ?>"><?php echo e($portal); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive" id="customerTable">
                <?php echo $__env->make('customer.index-table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?> 
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('filterType');
            const serviceWrapper = document.getElementById('serviceFilterWrapper');
            const serviceSelect = document.getElementById('filterService');
            const portalSelect = document.getElementById('filterPortal');

            function fetchFilteredData(url = null) {
                let type = typeSelect.value;
                let service = serviceSelect.value;
                let portal = portalSelect.value;

                url = url || "<?php echo e(route('admin.customers.index')); ?>";
                url += `?type=${type}&service=${service}&portal=${portal}`;

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.text())
                    .then(data => {
                        document.getElementById('customerTable').innerHTML = data;
                    });
            }

            // Filters
            typeSelect.addEventListener('change', function() {
                if (this.value.toLowerCase() === 'corporate') {
                    serviceWrapper.classList.remove('d-none');
                } else {
                    serviceWrapper.classList.add('d-none');
                    serviceSelect.value = '';
                }
                fetchFilteredData();
            });

            serviceSelect.addEventListener('change', function() {
                fetchFilteredData();
            });
            portalSelect.addEventListener('change', function() {
                fetchFilteredData();
            });

            // Pagination AJAX
            document.addEventListener('click', function(e) {
                if (e.target.closest('#customerTable .pagination a')) {
                    e.preventDefault();
                    let url = e.target.getAttribute('href');
                    fetchFilteredData(url);
                }

            });
        });


        document.querySelectorAll('.delete-customer').forEach(button => {
            button.addEventListener('click', function() {
                let customerId = this.dataset.id;

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("<?php echo e(url('admin/customers')); ?>/" + customerId, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>",
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    document.getElementById('customer-' + customerId).remove();
                                    Swal.fire('Deleted!', data.message, 'success');
                                } else {
                                    Swal.fire('Error!', data.message || 'Something went wrong!',
                                        'error');
                                }
                            })
                            .catch(error => {
                                Swal.fire('Error!', 'Something went wrong!', 'error');
                            });
                    }
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Customer View'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Fonivo lk\fonivo\resources\views/customer/view.blade.php ENDPATH**/ ?>