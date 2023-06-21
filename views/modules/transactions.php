<?php
require_once 'models/connection.php';
?>
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
                      <th>Transaction ID</th>
                      <th>Amount</th>
                      <th>Payment Method</th>
                      <th>Date</th>
                      <th>Actions</th>

                    </tr> 

                    </thead>
                    <tbody>
                        <?php
                        $pdo = connection::connect();
                        $select = $pdo->prepare('SELECT * FROM payments');
                        $select->execute();
                        $paymentResult = $select->fetchAll();
                        foreach ($paymentResult as $paymentKey => $paymentVal) {
                            if ($paymentVal['amount'] > 0) {
                                echo '<tr>
                                        <td>' . ($paymentKey + 1) . '</td>
                                        <td>' . $paymentVal["paymentid"] . '</td>
                                        <td>' . $paymentVal["amount"] . '</td> 
                                        <td>' . $paymentVal["paymentmethod"] . '</td>
                                        <td>' . $paymentVal["date"] . '</td>
                                        <td><button receipt="' . $paymentVal['paymentid'] . '" class="btn btn-s download-reciept"><i class="fa-solid fa-file-pdf"></i></button>
                                        <button receipt="' . $paymentVal['paymentid'] . '" class="btn btn-s view-receipt"><i class="fa-solid fa-eye"></i></button>
                                        <button receipt="' . $paymentVal['paymentid'] . '" class="btn btn-s"><i class="fa-solid fa-trash"></i></button></td>
                                    </tr>';
                            }
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