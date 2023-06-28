

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
                    <h5 class="m-0">Place Orders</h5>
                </div>
                <div class="card-body">
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
                                <label for="quantity">Quantity</label>
                                <input type="number" class="form-control" name="quantity" id="quantity" min="1">
                            </div>
                            <div class="form-group">
                                <label for="supplier">Supplier</label>
                                <select name="supplier" id="supplier" class="form-control">
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
                            <div class="card-footer">
                            <div class="text-center">
                                <button type="button" class="btn btn-primary" name="addproduct">Add to list</button>
                            </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="products" class="control-label">Products and descriptions</label>
                                <textarea class="form-control" placeholder= "Enter description" name="products" id="products" rows="25"></textarea>
                            </div>
                        </div>
                    </div>
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
