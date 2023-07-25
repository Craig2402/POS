<?php
    require_once 'models/connection.php';
    function fillProducts($pdo){
        $pdo = connection::connect();
        $output="";
        $select=$pdo->prepare("SELECT * FROM products WHERE store_id = :store_id AND status = 0");
        $select->bindParam(':store_id', $_SESSION['storeid']);
        $select->execute();
        
        $result=$select->fetchAll();

        foreach($result as $row){
            $output.='<option value="'.$row['barcode'].'">' . $row['product'] . '</option>';
        }


        return $output; ;

    }
 ?>
 <style>
    
    .modal-header {
        background-color: #39b54a;
        color: #fff;
    }
    
    .modal-header h5.modal-title {
        color: #fff;
    }
    
    .modal-content {
        border: none;
        border-radius: 4px;
    }
    
    .modal-body {
        background-color: #f8f8f8;
    }
    
    .modal-footer {
        background-color: #f8f8f8;
    }
    
    .modal-footer .btn-primary {
        background-color: #39b54a;
        color: #fff;
    }
    
    .modal-footer .btn-primary:hover,
    .modal-footer .btn-primary:focus {
        background-color: #00cc00;
        color: #fff;
    }

 </style>
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Point of Sale</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
              <li class="breadcrumb-item active">Point of Sale</li>
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
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h5 class="m-0">POS</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-barcode"></i></span>
                                    </div>
                                    <input type="text" name="txtbarcode" id="scanbarcode" class="form-control" placeholder="Scan Barcode">
                                </div>
                                <div class="form-group">
                                <label>Product name</label>
                                    <select class="form-control select2" data-dropdown-css-class="select2-purple" style="width: 100%;" id="pos-select">
                                        <option value="">Select or search</option><?php echo fillProducts($pdo);?>
                                    </select>
                                </div>
                                <div class="tableFixHead">
                                    <table id="producttable" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Stock</th>
                                                <th>Price</th>
                                                <th>Discount</th>
                                                <th>Qty</th>
                                                <th>Total</th>
                                                <th>Del</th>
                                            </tr>
                                        </thead>
                                        <tbody class="details" id="itemtable">
                                            <input type="hidden" name="productsList" id="productsList">
                                            <!-- <textarea name="productsList" id="productsList" cols="60" rows="10"></textarea> -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Sub Total</span>
                                    </div>
                                    <input type="text" class="form-control" name="subtotal" id="txtsubtotal_id" readonly>
                                    <input type="hidden" class="form-control" id="taxablesubtotal_id" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Kshs</span>
                                    </div>
                                </div>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">VAT</span>
                                    </div>
                                    <input type="text" class="form-control" id="txttaxtotal_id" name="totaltax" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Kshs</span>
                                    </div>
                                </div>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Total Discount</span>
                                    </div>
                                    <input type="text" class="form-control" id="txtdiscounttotal_id" name="totaldiscount" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Kshs</span>
                                    </div>
                                </div>
                                <hr style="height:2px; border-width:0; color:black; background-color:black;">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Total</span>
                                    </div>
                                    <input type="text" class="form-control form-control-lg total" id="txttotal_id" name="total" readonly>
                                    <input type="hidden" class="form-control" id="taxabletotal_id" readonly>
                                    <input type="hidden" class="form-control" id="nontaxabletotal_id" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Kshs</span>
                                    </div>
                                </div>
                                <hr style="height:2px; border-width:0; color:black; background-color:black;">
                                <div class="form-group clearfix" name="paymentmethod">
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" name="r3" id="radioSuccess1" value="cash">
                                        <label for="radioSuccess1">
                                            Cash
                                        </label>
                                    </div>
                                    <div class="icheck-danger d-inline">
                                        <input type="radio" name="r3" id="radioSuccess2"  value="mdogo">
                                        <label for="radioSuccess2">
                                            Lipa MdogoMdogo
                                        </label>
                                    </div>
                                    <div class="icheck-success d-inline">
                                        <input type="radio" name="r3" id="radioSuccess3"  value="M-pesa">
                                        <label for="radioSuccess3">
                                            M-pesa
                                        </label>
                                    </div>
                                </div>
                                <hr style="height:2px; border-width:0; color:black; background-color:black;">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Due</span>
                                    </div>
                                    <input type="text" class="form-control form-control-lg total" id="txtdue_id" name="dueamount" readonly >
                                    <div class="input-group-append">
                                        <span class="input-group-text">Kshs</span>
                                    </div>
                                </div>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Paid</span>
                                    </div>
                                    <input type="text" class="form-control form-control-lg total" id="txtpaid_id" name="txtpaid" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Kshs</span>
                                    </div>
                                </div>
                                <!-- Input fields to be added conditionally -->
                                <div id="additionalInputs" style="display: none;">
                                <hr style="height:2px; border-width:0; color:black; background-color:black;">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Customer Name</span>
                                        </div>
                                        <input type="text" class="form-control" id="cname" name="cname">
                                    </div>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Phone Number</span>
                                        </div>
                                        <input type="text" class="form-control" id="phone" name="phone">
                                    </div>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Identification Number</span>
                                        </div>
                                        <input type="text" class="form-control" id="cid" name="cid">
                                    </div>
                                </div>
                                <!-- End of additional inputs -->

                                <hr style="height:2px; border-width:0; color:black; background-color:black;">
                                <div class="card-footer">
                                    <div class="text-center">
                                        <button name="saveorder" id="submitButton" class="btn btn-primary">Save Order</button>
                                    </div>
                                </div>
                                <?php
                                    $add= new PaymentController();
                                    $add->addPayment();
                                ?>
                            </div>
                        </div>
                    </div>
                    </div>
                </form>
            </div>
            <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
 

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">M-Pesa Payment</h5>
        <!-- Remove the "data-dismiss" attribute from the close button -->
        <button type="button" class="close" aria-label="Close" data-dismiss="modal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="paymentForm" action="" method="POST">
        <div class="modal-body"> 
          <div class="form-group">
            <label for="inputAddress2" class="form-label">Phone Number</label>
            <input type="text" class="form-control" name="phone" placeholder="Enter Phone Number" required>
          </div>
          <div class="form-group">
            <label for="inputAddress" class="form-label">Amount</label>
            <input type="text" class="form-control" name="amount" placeholder="Enter Amount" required>
          </div>
        </div>
        <?php
        $pay = new PaymentController();
        $pay->stkpush();
        ?>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" name="submit">Make Payment</button>
        </div>
      </form>
    </div>
  </div>
</div>

