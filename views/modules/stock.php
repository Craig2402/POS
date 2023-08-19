<?php
  function fillProducts(){

    $item = "store_id";
    $value = $_SESSION['storeid'];
    $order = 'id';
    $products = productController::ctrShowProducts($item, $value, $order, true);

    $output = '';
    foreach ($products as $row) {
        $output .= '<option value="' . $row['id'] . '">' . $row['product'] . '</option>';
    }

    return $output;
  }
 ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Add Stock</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="home">Home</a></li>
              <li class="breadcrumb-item active">Add Stock</li>
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
                <h5 class="m-0">Add Stock</h5>
              </div>
              <div class="card-body">
                <form action="" method="post" enctype="multipart/form-data">
                  <div class="row">
                    <div class="col-lg-5">
                      <ul class="list-group">
                        <center><p class="list-group-item list-group-item-info"><b>PRODUCT</b></p></center>
                      </ul>
                      <div class="form-group">
                        <label>Product name</label>
                        <select class="form-control select2" data-dropdown-css-class="select2-purple" style="width: 100%;" name="product" id="stock">
                            <option value="">Select or search</option><?php echo fillProducts();?>
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="sproduct">Selected product</label>
                        <input type="text" class="form-control" name="sproduct" id="sproduct" placeholder="Selected product will appear here" readonly>
                      </div>
                      <div class="form-group">
                        <label for="cstock">Current stock</label>
                        <input type="text" class="form-control" name="cstock" id="cstock" placeholder="Current stock will appear here" readonly>
                      </div>
                      <div class="form-group">
                        <label for="astock">Stock</label>
                        <input type="number" min="1" step="any" class="form-control" name="astock" id="astock" placeholder="Add to stock" required>
                      </div>
                      <div class="card-footer">
                        <div class="text-center">
                          <button type="submit" class="btn btn-primary" name="addStock">Save</button>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-7">
                      <ul class="list-group">
                          <center><p class="list-group-item list-group-item-info"><b>PRODUCT IMAGE</b></p></center>
                          <img class="img-responsive" id="productImage">
                      </ul>
                    </div>
                  </div>
                    <?php
                      $addStock= new productController();
                      $addStock->ctrAddingStock();
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
 