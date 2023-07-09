 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Products</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
              <li class="breadcrumb-item active">Product List</li>
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
                <h5 class="m-0">Product List</h5>
              </div>
              <div class="card-body">
              <table id="example1" class="table table-bordered table-striped tables">
                  <thead>
           
                    <tr>
                      
                      <th>#</th>
                      <th>Barcode</th>
                      <th>Product</th>
                      <th>Category</th>
                      <th>Description </th>
                      <th>Stock</th>
                      <th>Purchase Price</th>
                      <th>Sale Price</th>
                      <th>Product Image</th>
                      <th>Last Edit Date</th>
                      <th>Actions</th>

                    </tr> 

                    </thead>
                    <tbody>
                      <?php

                        $item = null; 
                        $value = null;
                        $order='id';
                        $product = productController::ctrShowProducts($item, $value, $order);

                        // var_dump($product);

                        foreach ($product as $key => $val) {

                          $item = "id";
                          $value = $val["idCategory"];
                          
                          $category = categoriesController::ctrShowCategories($item, $value);

                          // var_dump($category["Category"]);

                        echo '

                            <tr>
                            <td>'.($key+1).'</td>
                            <td>'.$val["barcode"].'</td>
                            <td>'.$val["product"].'</td>
                            <td>'.$category["Category"].'</td>
                            <td>'.$val["description"].'</td>';
                            
                            if ($val["stock"] <= 10) {
                              $stock = "<button class='btn btn-danger'>".$val["stock"]."</button>";
                            }elseif ($val["stock"] > 11 && $val["stock"] <= 15) {
                                $stock = "<button class='btn btn-warning'>".$val["stock"]."</button>";
                            } else {
                                $stock = "<button class='btn btn-success'>".$val["stock"]."</button>";
                            }
                            
                            echo '<td>'.$stock.'</td>
                            <td>'.$val["purchaseprice"].'</td>
                            <td>'.$val["saleprice"].'</td>';
                          

                            if ($val["image"] != ""){

                                echo '<td><img src="'.$val["image"].'" class="img-thumbnail" width="40px"></td>';

                            }else{

                                echo '<td><img src="views/img/default/users/anonymous.png" class="img-thumbnail" width="40px"></td>';
                            
                            }

                            echo '<td>'.$val["date"].'</td>

                            <td>

                                <div class="btn-group">

                                  <a href="index.php?route=printbarcode&barcode='.$val["barcode"].'&image='.$val["image"].'" class="nav-link"><button class="btn btn-primary btnPrintProductBarcode"  idProduct="'.$val["barcode"].'" image="'.$val["image"].'"><i class="fa fa-barcode"></i></button></a>

                                  <button class="btn btn-success btnViewProduct"  idProduct="'.$val["barcode"].'" image="'.$val["image"].'"><i class="fa fa-eye"></i></button>';

                                  if (isset($_SESSION['role']) && ($_SESSION['role'] == "Administrator" || $_SESSION['role'] == "Store")) {

                                    echo '<button class="btn btn-warning btnEditProduct" idProduct="'.$val["barcode"].'" data-toggle="modal" data-target="#modalEditProduct"><i class="fa fa-edit"></i></button>';

                                  }

                                  if (isset($_SESSION['role']) && $_SESSION['role'] == "Administrator") {

                                    echo'<button class="btn btn-danger btnDeleteProduct"  idProduct="'.$val["barcode"].'" image="'.$val["image"].'"><i class="fa fa-times"></i></button>';

                                  }else{
                                    echo '<button class="btn btn-danger askDeleteProduct" idProduct="'.$val["barcode"].'" data-toggle="modal" data-target="#askDeleteProduct" ><i class="fa fa-times"></i></button>';
                                  }


                              echo' </div>  

                            </td>

                            </tr>';
                          }
                      ?>
                    </tbody>
                </table>
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

  <div class="modal fade" id="modalEditProduct">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit product</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>         
            <form action="" method="post" enctype="multipart/form-data">
              <div class="card-body">
                  <div class="row">
                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="exampleInputEmail1">Barcode</label>
                              <input type="text" class="form-control" name="editbarcode" id="editbarcode" readonly>
                          </div>
                          <div class="form-group">
                              <label for="exampleInputEmail1">Product Name</label>
                              <input type="text" class="form-control" name="editproductname" id="editproductname">
                          </div>
                          <div class="form-group">
                            <label for="exampleSelectBorder">Category</label>
                            <select class="form-control" name="editcategory">
                              <?php

                                $item = null;
                                $value1 = null;

                                $categories = categoriesController::ctrShowCategories($item, $value1);

                                foreach ($categories as $key => $value) {
                                  echo '<option value="'.$value["id"].'" '.$selected.'>'.$value["Category"].'</option>';
                                }
                                

                              ?>
                            </select>
                          </div>
                          <div class="form-group">
                            <label for="txttaxcat">Tax type</label>
                            <select name="edittaxcat" id="edittaxcat" class="form-control">
                              <option value="">Select a Tax type</option>
                              <?php
                              
                                $item = null;
                                $value1 = null;

                                $tax = taxdisController::ctrShowTaxdis($item,$value1);
                                
                                foreach ($tax as $key => $value) {
                                    
                                  echo '<option value="'.$value["VAT"].'">'.$value["VAT"].'</option>';
                                }

                              ?>
                            </select>
                          </div>
                          <div class="form-group">
                              <label>Description</label>
                              <textarea class="form-control" placeholder= "Edit description" name="editdescription" id="editdescription" rows="4"></textarea>
                          </div>
                          
                      </div>
                      <div class="col-md-6">  
                          <div class="form-group">
                              <label for="exampleInputEmail1">Stock quantity</label>
                              <input type="number" min="1" step="any" class="form-control" name="editstock" id="editstock" readonly>
                          </div>
                          <div class="form-group">
                              <label for="exampleInputEmail1">Purchase price</label>
                              <input type="number" min="1" step="any" class="form-control" name="editpurchaseprice" id="editpurchaseprice">
                          </div>
                          <div class="form-group">
                              <label for="exampleInputEmail1">Sale price</label>
                              <input type="number" min="1" step="any" class="form-control" name="editsaleprice" id="editsaleprice">
                          </div>
                          <div class="form-group">
                              <div class="panel"><label for="exampleInputPassword1">Photo</label></div>
                              <input type="file" class="txtproductimage" name="editImage" id="editImage" >
                              <p class="help-block">Maximum file size 2mb</p>
                              <img src="views/img/products/default/anonymous.png" class="thumbnail preview" width="100px">
                              <input type="hidden" name="currentImage" id="currentImage">
                          </div>
                      </div>               
                  </div>
              </div>
                <!-- /.card-body -->
              <div class="modal-footer justify-content-between">
                  
                  <button type="submit" class="btn btn-default" data-dismiss="modal" name="editproduct">Close</button>
                  <button type="submit" class="btn btn-primary" name="addproduct">Save</button>
              </div>
                <?php

                  $editProduct = new productController();
                  $editProduct -> ctrEditProduct();

                ?>   
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

<!--======================================
module make request
=======================================-->
<!--Request Modal -->
<div class="modal fade" id="askDeleteProduct">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Make request</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="" method="post" enctype="multipart/form-data">
        <div class="modal-body">
          <!-- Delete reason -->
          <div class="form-group">
              <label for="reason">Reason for deletion:</label>
              <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
              <input type="hidden" name="id" id="id" required>
              <input type="hidden" name="type" value="Product deletion">
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" name="makeRequest">Send request</button>
        </div>
        <?php
          $makeRequest = new notificationController();
          $makeRequest -> ctrMakeRequest();
        ?>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<?php
  $deleteProduct = new productController();
  $deleteProduct -> ctrDeleteProduct();
?>
<?php
    $markRead = new notificationController();
    $markRead -> ctrMarkNotificationsRead();
?>