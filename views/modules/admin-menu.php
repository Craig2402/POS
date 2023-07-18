<aside class="main-sidebar custom-sidebar">
    <a href="#" class="brand-link">
        <img src="./dist/img/AdminLTELogo.png" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light" >POS</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?php echo $_SESSION['userphoto']; ?>" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block" ><?php echo $_SESSION['username']; ?></a>
            </div>
        </div>

        <div class="sidebar-search">
            <div class="input-group">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                    with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="dashboard" class="nav-link">
                        <i class="fa-solid fa-house-user nav-icon"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pos" class="nav-link">
                        <i class="fa-solid fa-store nav-icon"></i>
                        <p>
                            POS
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fa-solid fa-boxes-stacked"></i>
                        <p>
                            Inventory
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="category" class="nav-link">
                                <i class="fa-solid fa-sitemap nav-icon"></i>
                                <p>
                                    Category
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fa-solid fa-cart-shopping nav-icon"></i>
                                <p>
                                    Products
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="addproduct" class="nav-link">
                                        <i class="fa-solid fa-circle nav-icon"></i>
                                        <p>Add Products</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="products" class="nav-link">
                                        <i class="fa-solid fa-circle nav-icon"></i>
                                        <p>View Products</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="stock" class="nav-link">
                                        <i class="fa-solid fa-circle nav-icon"></i>
                                        <p>Add stock</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="discount" class="nav-link">
                                        <i class="fa-solid fa-circle nav-icon"></i>
                                        <p>Discounts</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fa-solid fa-boxes-packing"></i>
                                <p>
                                    Supply
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="suppliers" class="nav-link">
                                        <i class="fa-solid fa-circle nav-icon"></i>
                                        <p>Supliers</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa-solid fa-circle nav-icon"></i>
                                        <p>Supplier Invoices</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa-solid fa-boxes-packing"></i>
                                        <p>
                                        Orders
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="orders" class="nav-link">
                                                <i class="fa-solid fa-circle nav-icon"></i>
                                                <p>Make order</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="vieworders" class="nav-link">
                                                <i class="fa-solid fa-circle nav-icon"></i>
                                                <p>View orders</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fa-solid fa-right-left"></i>
                                <p>
                                    Return Products
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="returns" class="nav-link">
                                        <i class="fa-solid fa-circle nav-icon"></i>
                                        <p>Make return</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="view-returned" class="nav-link">
                                        <i class="fa-solid fa-circle nav-icon"></i>
                                        <p>View returned</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fa-solid fa-arrow-trend-up nav-icon"></i>
                        <p>
                            Finance
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="finance-dashboard" class="nav-link">
                                <i class="fa-solid fa-circle nav-icon"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="transactions" class="nav-link">
                                <i class="fa-solid fa-circle nav-icon"></i>
                                <p>Transactions</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="taxdis" class="nav-link">
                                <i class="fa-solid fa-circle nav-icon"></i>
                                <p>Tax</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="invoices" class="nav-link">
                                <i class="fa-solid fa-circle nav-icon"></i>
                                <p>Client Invoices</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="expenses" class="nav-link">
                                <i class="fa-solid fa-circle nav-icon"></i>
                                <p>Expenses</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fa-solid fa-coins nav-icon"></i>
                        <p>Sales
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="sales" class="nav-link">
                                <i class="fa-solid fa-circle nav-icon"></i>
                                <p>Manage sales</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fa-solid fa-circle nav-icon"></i>
                                <p>Sales report</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fa-solid fa-warehouse"></i>
                        <p>Stores
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="stores" class="nav-link">
                                <i class="fa-solid fa-circle nav-icon"></i>
                                <p>Add stores</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="manage-stores" class="nav-link">
                                <i class="fa-solid fa-circle nav-icon"></i>
                                <p>Manage stores</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="registration" class="nav-link">
                        <i class="fa-solid fa-users nav-icon"></i>
                        <p>
                            Registration
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="changepassword" class="nav-link">
                        <i class="fa-solid fa-user-shield nav-icon"></i>
                        <p>
                            Change password
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="logout" class="nav-link">
                        <i class="fa-solid fa-arrow-right-from-bracket nav-icon"></i>
                        <p>
                            Log out
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
