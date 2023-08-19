<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="./dist/img/AdminLTELogo.png" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">POS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src=<?php echo $_SESSION['userphoto'];?> class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo $_SESSION['username'];?></a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
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
            <a href="stock" class="nav-link">
              <i class="fa-solid fa-plus"></i> 
              <p>
                Add Stock
              </p>
            </a>
          </li>
        <li class="nav-item">
            <a href="category" class="nav-link">
              <i class="fa-solid fa-sitemap"></i>
              <p>
                Category
              </p>
            </a>
          </li>
          <li class="nav nav-item">
                <a href="" class="nav-link"><i class="fa-solid fa-cart-shopping"></i>
                  <p>
                    Products
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="addproduct" class="nav-link">
                          <i class="fa-solid fa-cart-plus"></i>
                            <p>Add Products</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="products" class="nav-link">
                          <i class="fa-solid fa-eye"></i>
                            <p>View Products</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="discount" class="nav-link">
                          <i class="fa-solid fa-percent"></i>
                            <p> Add discount</p>
                        </a>
                    </li>
                </ul>
          </li>
          <li class="nav-item">
            <a href="changepassword" class="nav-link">
              <i class="fa-solid fa-user-shield"></i>
              <p>
                Change password
                
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="logout" class="nav-link">
              <i class="fa-solid fa-arrow-right-from-bracket"></i>
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




  <style>
  .link  {
    text-decoration: none;
  }
  .brand-text:hover {
    color: black;
  }
  .brand-text {
    color: black;
  }
  
</style>
  <?php
  
  $table = "customers";
  $item = "organizationcode";
  $value = $_SESSION['organizationcode'];
  $conn = connection::connectbilling()->prepare("SELECT * FROM `$table` WHERE $item = :$item");
  
  $conn->bindParam(':' . $item, $value, PDO::PARAM_STR);
  
  $conn->execute();
  
  $organizationData = $conn->fetch(PDO::FETCH_ASSOC); // Fetch the result into an associative array

  $conn -> closeCursor();

  $conn = null;
  ?>
<aside class="main-sidebar custom-sidebar">
    <a href="#" class="brand-link link">
        <img src="./img/store/959.jpg" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light" ><?php echo $organizationData['organizationname'] ?></span>
    </a>

    <div class="sidebar">
    <?php
        $item = "store_id";
        $value = $_SESSION['storeid'];
        $store = storeController::ctrShowStores($item, $value);
        ?>

        <?php if ($_SESSION['storeid'] !== null) { ?>
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?php echo $store['logo']; ?>" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <div class="d-block link"><?php echo $store['store_name']; ?></div>
            </div>
        </div>
    <?php } ?>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                    with font-awesome or any other icon font library -->
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
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

