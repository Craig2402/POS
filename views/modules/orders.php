

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Orders</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Orders</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
          <!-- /.col-md-6 -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h5 class="m-0">Place Orders</h5>
                        <a href="vieworders"><button type="button" class="btn btn-primary btn-sm" id="viewOrder">View orders</button></a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="product" class="control-label">Product</label>
                                    <select class="form-control" name="product" id="product">
                                        <option value="">--Select a product--</option>
                                        <?php
                                            $item = null;
                                            $value = null;
                                            $order = "id";

                                            $products = productController::ctrShowProducts($item, $value, $order);

                                            foreach ($products as $key => $value) {
                                                echo '<option value="'.$value["barcode"].'">'.$value["product"].'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="total">Total price</label>
                                    <input type="text" class="form-control" name="total" id="total"readonly required>
                                </div>
                                <div class="form-group">
                                    <label for="supplier">Supplier</label>
                                    <select name="supplier" id="supplier" class="form-control" required>
                                        <option value="">--Select a supplier--</option>
                                        <?php
                                            $item = null;
                                            $value = null;

                                            $supplier = supplierController::ctrShowSuppliers($item, $value);
                                            foreach ($supplier as $key => $value) {
                                                echo '<option value="'.$value["supplierid"].'">'.$value["name"].'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="tableFixHead">
                                    <table id="producttable" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Qty</th>
                                                <th>Total</th>
                                                <th>Del</th>
                                            </tr>
                                        </thead>
                                        <tbody class="orders" id="itemtable">
                                            <input type="hidden" name="products" id="products">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary" name="addproducts">Make order</button>
                            </div>
                        </div>
                        <?php
                          $addorder= new orderController();
                          $addorder->ctrCreateOrder();
                        ?>
                    </form>
                </div>
            </div>
    
          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php
    $markRead = new notificationController();
    $markRead -> ctrMarkNotificationsRead();
?>
