<div class="app-sidebar">
    <!-- Sidebar Logo -->
    <div class="logo-box">
        <a href="<?php echo e(route('any', 'index')); ?>" class="logo-dark">
            <img src="/images/vacayguider.png" class="logo-sm" alt="logo sm">
            <img src="/images/vacayguider.png" class="logo-lg" alt="logo dark" style="width: 150px; height: 75px;">
        </a>

        <a href="<?php echo e(route('any', 'index')); ?>" class="logo-light">
            <img src="/images/vacayguider.png" class="logo-sm" alt="logo sm">
            <img src="/images/vacayguider.png" class="logo-lg" alt="logo light" style="width: 150px; height: 75px;">
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
                <a class="nav-link menu-arrow" href="#sidebarCustomer" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarCustomer">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:user-circle-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Customer</span>
                </a>
                <div class="collapse" id="sidebarCustomer">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('second', ['customer', 'create'])); ?>">Create</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.customers.index')); ?>">View </a>
                        </li>


                    </ul>
                </div>
            </li>

             <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarProducts" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarProducts">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:user-circle-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Products</span>
                </a>
                <div class="collapse" id="sidebarProducts">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.products.create')); ?>">Create</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.products.index')); ?>">View </a>
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