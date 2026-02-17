<div class="app-sidebar">
    <!-- Sidebar Logo -->
    <div class="logo-box">
        <a href="<?php echo e(route('any', 'index')); ?>" class="logo-dark">
            <img src="/images/fonivo2.jpg" class="logo-sm" alt="logo sm">
            <img src="/images/fonivo2.jpg" class="logo-lg" alt="logo dark" style="width: 150px; height: 75px;">
        </a>

        <a href="<?php echo e(route('any', 'index')); ?>" class="logo-light">
            <img src="/images/fonivo2.jpg" class="logo-sm" alt="logo sm">
            <img src="/images/fonivo2.jpg" class="logo-lg" alt="logo light" style="width: 150px; height: 75px;">
        </a>
    </div>

    <div class="scrollbar" data-simplebar>

        <ul class="navbar-nav" id="navbar-nav">

            <li class="menu-title">Menu...</li>

            

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarAdmin" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarAdmin">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:user-circle-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Admin</span>
                </a>
                <div class="collapse" id="sidebarAdmin">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('second', ['admin', 'create'])); ?>">Create</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.users.index')); ?>">View </a>
                        </li>


                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarCategories" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarCategories">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:widget-2-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Categories</span>
                </a>
                <div class="collapse" id="sidebarCategories">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.categories.create')); ?>">Create</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.categories.index')); ?>">View</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarProducts" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarProducts">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:box-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Products</span>
                </a>
                <div class="collapse" id="sidebarProducts">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.products.create')); ?>">Create</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.products.index')); ?>">View</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarSuppliers" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarSuppliers">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:buildings-3-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Suppliers</span>
                </a>
                <div class="collapse" id="sidebarSuppliers">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.suppliers.create')); ?>">Create</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.suppliers.index')); ?>">View</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarCustomers" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarCustomers">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:users-group-rounded-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Customers</span>
                </a>
                <div class="collapse" id="sidebarCustomers">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.customers.create')); ?>">Create</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.customers.index')); ?>">View</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarPhoneStock" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarPhoneStock">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:smartphone-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Phone Stock</span>
                </a>
                <div class="collapse" id="sidebarPhoneStock">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.phone_units.create')); ?>">Add Phone</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.phone_units.index')); ?>">View Stock</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarAccessoryStock" data-bs-toggle="collapse"
                    role="button" aria-expanded="false" aria-controls="sidebarAccessoryStock">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:box-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Accessory Stock</span>
                </a>
                <div class="collapse" id="sidebarAccessoryStock">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.accessory_stock.index')); ?>">View Stock</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarPurchases" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarPurchases">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:clipboard-check-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Purchases</span>
                </a>
                <div class="collapse" id="sidebarPurchases">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.purchases.create')); ?>">Create</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.purchases.index')); ?>">View</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarSales" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarSales">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:cart-3-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Sales</span>
                </a>
                <div class="collapse" id="sidebarSales">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.sales.create')); ?>">Create</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.sales.index')); ?>">View</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarLedgers" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarLedgers">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:book-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Ledgers</span>
                </a>
                <div class="collapse" id="sidebarLedgers">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.ledgers.suppliers.index')); ?>">Supplier
                                Ledger</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.ledgers.customers.index')); ?>">Customer
                                Ledger</a>
                        </li>
                    </ul>
                </div>
            </li>


            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarReports" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarReports">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:chart-2-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Reports</span>
                </a>
                <div class="collapse" id="sidebarReports">
                    <ul class="nav sub-navbar-nav">

                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.reports.profit')); ?>">
                                Profit Report
                            </a>
                        </li>

                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.reports.stock')); ?>">
                                Stock Report
                            </a>
                        </li>

                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.reports.due')); ?>">
                                Due Report
                            </a>
                        </li>

                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.reports.dailySales')); ?>">
                                Daily Sales
                            </a>
                        </li>

                    </ul>
                </div>
            </li>


            <li class="nav-item">
                <a class="nav-link" href="<?php echo e(route('admin.profile.edit')); ?>">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:widget-2-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Profile </span>

                </a>
            </li>

            
        </ul>
    </div>
</div>
<?php /**PATH F:\Personal Projects\Fonivo lk\fonivo\resources\views/layouts/partials/sidebar.blade.php ENDPATH**/ ?>