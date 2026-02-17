<div class="app-sidebar">
    <!-- Sidebar Logo -->
    <div class="logo-box">
        <a href="{{ route('any', 'index') }}" class="logo-dark">
            <img src="/images/fonivo2.jpg" class="logo-sm" alt="logo sm">
            <img src="/images/fonivo2.jpg" class="logo-lg" alt="logo dark" style="width: 150px; height: 75px;">
        </a>

        <a href="{{ route('any', 'index') }}" class="logo-light">
            <img src="/images/fonivo2.jpg" class="logo-sm" alt="logo sm">
            <img src="/images/fonivo2.jpg" class="logo-lg" alt="logo light" style="width: 150px; height: 75px;">
        </a>
    </div>

    <div class="scrollbar" data-simplebar>

        <ul class="navbar-nav" id="navbar-nav">

            <li class="menu-title">Menu...</li>

            {{-- <li class="nav-item">
                    <a class="nav-link" href="{{ route('any', 'index') }}">
                         <span class="nav-icon">
                                      <iconify-icon icon="solar:user-circle-outline"></iconify-icon>
                         </span>
                         <span class="nav-text"> Admin </span>
                      

                         
                    </a>
               </li> --}}

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
                            <a class="sub-nav-link" href="{{ route('second', ['admin', 'create']) }}">Create</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.users.index') }}">View </a>
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
                            <a class="sub-nav-link" href="{{ route('admin.categories.create') }}">Create</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.categories.index') }}">View</a>
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
                            <a class="sub-nav-link" href="{{ route('admin.products.create') }}">Create</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.products.index') }}">View</a>
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
                            <a class="sub-nav-link" href="{{ route('admin.suppliers.create') }}">Create</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.suppliers.index') }}">View</a>
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
                            <a class="sub-nav-link" href="{{ route('admin.customers.create') }}">Create</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.customers.index') }}">View</a>
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
                            <a class="sub-nav-link" href="{{ route('admin.phone_units.create') }}">Add Phone</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.phone_units.index') }}">View Stock</a>
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
                            <a class="sub-nav-link" href="{{ route('admin.accessory_stock.index') }}">View Stock</a>
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
                            <a class="sub-nav-link" href="{{ route('admin.purchases.create') }}">Create</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.purchases.index') }}">View</a>
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
                            <a class="sub-nav-link" href="{{ route('admin.sales.create') }}">Create</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.sales.index') }}">View</a>
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
                            <a class="sub-nav-link" href="{{ route('admin.ledgers.suppliers.index') }}">Supplier
                                Ledger</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.ledgers.customers.index') }}">Customer
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
                            <a class="sub-nav-link" href="{{ route('admin.reports.profit') }}">
                                Profit Report
                            </a>
                        </li>

                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.reports.stock') }}">
                                Stock Report
                            </a>
                        </li>

                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.reports.due') }}">
                                Due Report
                            </a>
                        </li>

                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.reports.dailySales') }}">
                                Daily Sales
                            </a>
                        </li>

                    </ul>
                </div>
            </li>


            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.profile.edit') }}">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:widget-2-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Profile </span>

                </a>
            </li>

            {{-- <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarAuthentication" data-bs-toggle="collapse" role="button"
                         aria-expanded="false" aria-controls="sidebarAuthentication">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:user-circle-outline"></iconify-icon>
                         </span>
                         <span class="nav-text"> Authentication </span>
                    </a>
                    <div class="collapse" id="sidebarAuthentication">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['auth','signin']) }}">Sign In</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['auth','signup']) }}">Sign Up</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['auth','password']) }}">Reset Password</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['auth','lock-screen']) }}">Lock Screen</a>
                              </li>
                         </ul>
                    </div>
               </li>

               <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarError" data-bs-toggle="collapse" role="button"
                         aria-expanded="false" aria-controls="sidebarError">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:danger-outline"></iconify-icon>
                         </span>
                         <span class="nav-text"> Error Pages</span>
                    </a>
                    <div class="collapse" id="sidebarError">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['pages','404']) }}">Pages 404</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['pages','404-alt']) }}">Pages 404 Alt</a>
                              </li>
                         </ul>
                    </div>
               </li>

               <li class="menu-title">UI Kit...</li>

               <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarBaseUI" data-bs-toggle="collapse" role="button"
                         aria-expanded="false" aria-controls="sidebarBaseUI">
                         <span class="nav-icon"><iconify-icon icon="solar:leaf-outline"></iconify-icon></span>
                         <span class="nav-text"> Base UI </span>
                    </a>
                    <div class="collapse" id="sidebarBaseUI">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','accordion']) }}">Accordion</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','alerts']) }}">Alerts</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','avatar']) }}">Avatar</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','badge']) }}">Badge</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','breadcrumb']) }}">Breadcrumb</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','buttons']) }}">Buttons</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','card']) }}">Card</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','carousel']) }}">Carousel</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','collapse']) }}">Collapse</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','dropdown']) }}">Dropdown</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','list-group']) }}">List Group</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','modal']) }}">Modal</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','tabs']) }}">Tabs</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','offcanvas']) }}">Offcanvas</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','pagination']) }}">Pagination</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','placeholders']) }}">Placeholders</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','popovers']) }}">Popovers</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','progress']) }}">Progress</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','scrollspy']) }}">Scrollspy</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','spinners']) }}">Spinners</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','toasts']) }}">Toasts</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','tooltips']) }}">Tooltips</a>
                              </li>
                         </ul>
                    </div>
               </li>

               <li class="nav-item">
                    <a class="nav-link" href="{{ route ('second' , ['pages','charts']) }}">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:chart-square-outline"></iconify-icon>
                         </span>
                         <span class="nav-text"> Apex Charts </span>
                    </a>
               </li>

               <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarForms" data-bs-toggle="collapse" role="button"
                         aria-expanded="false" aria-controls="sidebarForms">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:box-outline"></iconify-icon>
                         </span>
                         <span class="nav-text"> Forms </span>
                    </a>
                    <div class="collapse" id="sidebarForms">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['forms','basic']) }}">Basic Elements</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['forms','flatpicker']) }}">Flatpicker</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['forms','validation']) }}">Validation</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['forms','fileuploads']) }}">File Upload</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['forms','editors']) }}">Editors</a>
                              </li>
                         </ul>
                    </div>
               </li>

               <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarTables" data-bs-toggle="collapse" role="button"
                         aria-expanded="false" aria-controls="sidebarTables">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:checklist-outline"></iconify-icon>
                         </span>
                         <span class="nav-text"> Tables </span>
                    </a>
                    <div class="collapse" id="sidebarTables">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['tables','basic']) }}">Basic Tables</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['tables','gridjs']) }}">Grid Js</a>
                              </li>
                         </ul>
                    </div>
               </li>

               <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarIcons" data-bs-toggle="collapse" role="button"
                         aria-expanded="false" aria-controls="sidebarIcons">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:crown-star-outline"></iconify-icon>
                         </span>
                         <span class="nav-text"> Icons </span>
                    </a>
                    <div class="collapse" id="sidebarIcons">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['icons','boxicons']) }}">Boxicons</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['icons','solar']) }}">Solar Icons</a>
                              </li>
                         </ul>
                    </div>
               </li>

               <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarMaps" data-bs-toggle="collapse" role="button"
                         aria-expanded="false" aria-controls="sidebarMaps">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:map-outline"></iconify-icon>
                         </span>
                         <span class="nav-text"> Maps </span>
                    </a>
                    <div class="collapse" id="sidebarMaps">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['maps','google']) }}">Google Maps</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['maps','vector']) }}">Vector Maps</a>
                              </li>
                         </ul>
                    </div>
               </li>

               <li class="menu-title">Other</li>

               <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarLayouts" data-bs-toggle="collapse" role="button"
                         aria-expanded="false" aria-controls="sidebarLayouts">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:window-frame-outline"></iconify-icon>
                         </span>
                         <span class="nav-text"> Layouts </span>
                    </a>
                    <div class="collapse" id="sidebarLayouts">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['layouts-demo','dark-sidenav']) }}" target="_blank">Dark
                                        Sidenav</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['layouts-demo','dark-topnav']) }}" target="_blank">Dark
                                        Topnav</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['layouts-demo','small-sidenav']) }}" target="_blank">Small
                                        Sidenav</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['layouts-demo','hidden-sidenav']) }}" target="_blank">Hidden
                                        Sidenav</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" target="_blank" href="{{ route ('second' , ['layouts-demo','dark']) }}">
                                        <span class="nav-text">Dark Mode</span>
                                        <span class="badge badge-soft-danger badge-pill text-end">Hot</span>
                                   </a>
                              </li>
                         </ul>
                    </div>
               </li>

               <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarMultiLevelDemo" data-bs-toggle="collapse" role="button"
                         aria-expanded="false" aria-controls="sidebarMultiLevelDemo">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:share-circle-outline"></iconify-icon>
                         </span>
                         <span class="nav-text"> Menu Item </span>
                    </a>
                    <div class="collapse" id="sidebarMultiLevelDemo">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="javascript:void(0);">Menu Item 1</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link  menu-arrow" href="#sidebarItemDemoSubItem"
                                        data-bs-toggle="collapse" role="button" aria-expanded="false"
                                        aria-controls="sidebarItemDemoSubItem">
                                        <span> Menu Item 2 </span>
                                   </a>
                                   <div class="collapse" id="sidebarItemDemoSubItem">
                                        <ul class="nav sub-navbar-nav">
                                             <li class="sub-nav-item">
                                                  <a class="sub-nav-link" href="javascript:void(0);">Menu Sub item</a>
                                             </li>
                                        </ul>
                                   </div>
                              </li>
                         </ul>
                    </div>
               </li>

               <li class="nav-item">
                    <a class="nav-link disabled" href="javascript:void(0);">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:library-outline"></iconify-icon>
                         </span>
                         <span class="nav-text"> Disable Item </span>
                    </a>
               </li> --}}
        </ul>
    </div>
</div>
