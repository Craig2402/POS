<?php
require_once 'models/connection.php';
?>

<style>
    /* Custom CSS */
    .custom-table {
        border-radius: 10px; /* Curved edges */
        box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1); /* Box shadow */
        background-color: white; /* White background */
        color: #2C394B; /* Text color */
        font-family: 'Segoe UI', sans-serif; /* Modern font */
    }

    .custom-table th {
        background-color: #F2F4F6; /* Header background */
    }

    .custom-table tbody tr:nth-child(even) {
        background-color: #F5F7F9; /* Alternating row background */
    }

</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Invoices</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                        <li class="breadcrumb-item active">Invoices</li>
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
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <button type="button" class="btn btn-default float-right dates" id="daterange-btn">
                                <span>
                                    <i class="far fa-calendar-alt"></i> Date range
                                </span>
                                <i class="fas fa-caret-down"></i>
                            </button>
                            <h5 class="m-0">Invoices List</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <!-- <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search by customer name or contact"> -->

                                    <!-- <div class="table-responsive"> -->
                                        <table id="example1" class="table-striped tables display" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Product(s)</th>
                                                    <th>Customer Name</th>
                                                    <th>Phone Number</th>
                                                    <th>Date</th>
                                                    <th>DueDate</th>
                                                    <th>Status</th>
                                                    <th>Total Tax</th>
                                                    <th>Total</th>
                                                    <th>Discount</th>
                                                    <th>Due Amount</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="invoiceTableBody">
                                                <?php
                                                if (isset($_GET['initialDate'])) {
                                                    $initialDate = $_GET['initialDate'];
                                                    $finalDate = $_GET['finalDate'];
                                                } else {
                                                    $initialDate = null;
                                                    $finalDate = null;
                                                }
                                                $pdo = connection::connect();

                                                $invoices = PaymentController::ctrSalesDatesRange($initialDate, $finalDate);
                                                // var_dump($invoices);

                                                foreach ($invoices as $key => $value) {
                                                    $jsonArray = $value["products"];
                                                    $data = json_decode($jsonArray, true);

                                                    echo '
                                                    <tr data-widget="expandable-table" aria-expanded="false">
                                                        <td>' . ($key + 1) . '</td>
                                                        <td>';
                                                    for ($i = 0; $i < count($data); $i++) {
                                                        $productName = $data[$i]['productName'];
                                                        $quantity = $data[$i]['Quantity'];
                                                        echo $productName . ' (' . $quantity . ')' . " , ";
                                                    }

                                                    echo '</td>
                                                        <td>' . $value["customername"] . '</td>
                                                        <td>' . $value["phone"] . '</td>
                                                        <td>' . $value["startdate"] . '</td>
                                                        <td>' . $value["duedate"] . '</td>';

                                                    if ($value["dueamount"] == 0) {
                                                        echo '<td><button class="btn btn-success btn-sm">Paid</button></td>';
                                                    } elseif ($value["total"] == abs($value['dueamount'])) {
                                                        echo '<td><button class="btn btn-danger btn-sm">Unpaid</button></td>';
                                                    } else {
                                                        echo '<td><button class="btn btn-warning btn-sm">Partially Paid</button></td>';
                                                    }

                                                    echo '<td>' . $value["totaltax"] . '</td>
                                                        <td>' . $value["total"] . '</td>
                                                        <td>' . $value["discount"] . '</td>
                                                        <td>' . $value["dueamount"] . '</td>';

                                                    if ($value["dueamount"] != 0) {
                                                        echo '<td><button idInvoice="' . $value['invoiceId'] . '" class="btn btn-s downloadinvoice"><i class="fa-solid fa-file-pdf"></i></button>
                                                                <button idInvoice="' . $value['invoiceId'] . '" class="btn btn-s viewInvoice"><i class="fa-solid fa-eye"></i></button>
                                                                <button idInvoice="' . $value['invoiceId'] . '" class="btn btn-s addPayment"><i class="fa-solid fa-check"></i></button></td>';
                                                    } else {
                                                        echo '<td><button idInvoice="' . $value['invoiceId'] . '" class="btn btn-s downloadinvoice"><i class="fa-solid fa-file-pdf"></i></button>
                                                            <button idInvoice="' . $value['invoiceId'] . '" class="btn btn-s viewInvoice" data-toggle="modal" data-target="#viewInvoiceModal"><i class="fa-solid fa-eye"></i></button></td>';
                                                    }

                                                    echo '</tr>';

                                                    // $select = $pdo->prepare('SELECT * FROM payments WHERE invoiceId = ?');
                                                    // $select->bindParam(1, $value['invoiceId'], PDO::PARAM_STR);
                                                    // $select->execute();
                                                    // $paymentResult = $select->fetchAll();

                                                    // if (count($paymentResult) > 0) {
                                                    //     echo '<tr class="expandable-body">
                                                    //             <td colspan="13">
                                                    //                 <p>
                                                    //                     <table class="table table-hover">
                                                    //                         <thead>
                                                    //                             <tr>
                                                    //                                 <th>#</th>
                                                    //                                 <th>Amount</th>
                                                    //                                 <th>Date</th>
                                                    //                                 <th>Payment Method</th>
                                                    //                                 <th>actions</th>
                                                    //                             </tr>
                                                    //                         </thead>
                                                    //                         <tbody>';

                                                    //     foreach ($paymentResult as $paymentKey => $paymentVal) {
                                                    //         if ($paymentVal['amount'] > 0) {
                                                    //             echo '<tr>
                                                    //                     <td>' . ($paymentKey + 1) . '</td>
                                                    //                     <td>' . $paymentVal["amount"] . '</td>
                                                    //                     <td>' . $paymentVal["date"] . '</td>
                                                    //                     <td>' . $paymentVal["paymentmethod"] . '</td>
                                                    //                     <td><button receipt="' . $paymentVal['paymentid'] . '" class="btn btn-s download-reciept"><i class="fa-solid fa-file-pdf"></i></button>
                                                    //                     <button receipt="' . $paymentVal['paymentid'] . '" class="btn btn-s view-receipt"><i class="fa-solid fa-eye"></i></button></td>
                                                    //                 </tr>';
                                                    //         }
                                                    //     }

                                                    //     echo '</tbody>
                                                    //                     </table>
                                                    //                 </p>
                                                    //             </td>
                                                    //         </tr>';
                                                    // }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    <!-- </div> -->
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

<!-- View Invoice Modal -->
<div id="viewInvoiceModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="viewInvoiceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
    <!-- Modal content-->
    <div class="modal-content">
      <form role="form" method="POST">
        <div class="modal-header">
            <h4 class="modal-title">View Invoice</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
            <div class="modal-body">
                <div class="modalBodyContent">
                    <div class="row">
                        <div class="col-sm-3">
                            <strong>Invoice to:</strong><br>
                            <strong>Number:</strong>
                        </div>
                        <div class="col-sm-3 invoice-name-number">

                        </div>
                        <div class="col-sm-3">
                            <strong>Document Date:</strong><br>
                            <strong>Due Date:</strong>
                        </div>
                        <div class="col-sm-3 invoice-dates">
                        </div>
                    </div>
                    <!-- Add some spacing -->
                    <div class="my-4"></div>
                    <!-- Table row -->
                    <div class="row">
                        <div class="col-12 table-responsive custom-table">
                            <table id="invoice-table" class="w-100">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product</th>
                                        <th>Barcode</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody id="invoice-table-body">
                                </tbody>
                            </table>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->

                    <!-- Add some spacing -->
                    <div class="my-4"></div>

                    <div class="row">
                        <!-- accepted payments column -->
                        <div class="col-6">
                        </div>
                        <!-- /.col -->
                        <div class="col-6">
                            <div class="row">
                                <div class="col-sm-6 text-end">
                                    Total without VAT:
                                </div>
                                <div class="col-sm-6 text-end subtotal">
                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 text-end">
                                    VAT:
                                </div>
                                <div class="col-sm-6 text-end vat">
                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 text-end">
                                    Discount:
                                </div>
                                <div class="col-sm-6 text-end discount">
                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 text-end">
                                    <strong class="fs-5">Total:</strong> <!-- Add fs-5 class for bigger text -->
                                </div>
                                <div class="col-sm-6 text-end">
                                    <strong class="fs-5 total"></strong> <!-- Add fs-5 class for bigger text -->
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 text-end">
                                    Due:
                                </div>
                                <div class="col-sm-6 text-end due">
                                    
                                </div>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                    
                    <!-- Add some spacing -->
                    <div class="my-4"></div>

                    <div class="row">
                        <div class="col-8 table-responsive custom-table">
                            <h4>Related payments:</h4>
                            <table id="payment-table" class="w-100">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Number</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="payment-table-body">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <div class="dropup-center dropup">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                        Actions
                    </button>
                    <ul class="dropdown-menu">
                        <li><a id="view-pdf-link" class="dropdown-item" href="#">View PDF</a></li>
                        <li><a id="download-pdf-link" class="dropdown-item" href="#">Download PDF</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Draggable Modal -->
<div class="modal" id="draggableModalm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Draggable Modal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <!-- Main content -->
                <div class="invoice p-3 mb-3">
                <!-- title row -->
                <div class="row">
                    <div class="col-12">
                    <h4>
                        <i class="fas fa-globe"></i> AdminLTE, Inc.
                        <small class="float-right">Date: 2/10/2014</small>
                    </h4>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- info row -->
                <div class="row invoice-info">
                    <div class="col-sm-4 invoice-col">
                    From
                    <address>
                        <strong>Admin, Inc.</strong><br>
                        795 Folsom Ave, Suite 600<br>
                        San Francisco, CA 94107<br>
                        Phone: (804) 123-5432<br>
                        Email: info@almasaeedstudio.com
                    </address>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-4 invoice-col">
                    To
                    <address>
                        <strong>John Doe</strong><br>
                        795 Folsom Ave, Suite 600<br>
                        San Francisco, CA 94107<br>
                        Phone: (555) 539-1037<br>
                        Email: john.doe@example.com
                    </address>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-4 invoice-col">
                    <b>Invoice #007612</b><br>
                    <br>
                    <b>Order ID:</b> 4F3S8J<br>
                    <b>Payment Due:</b> 2/22/2014<br>
                    <b>Account:</b> 968-34567
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->

                <!-- Table row -->
                <div class="row">
                    <div class="col-12 table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                        <th>Qty</th>
                        <th>Product</th>
                        <th>Serial #</th>
                        <th>Description</th>
                        <th>Subtotal</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                        <td>1</td>
                        <td>Call of Duty</td>
                        <td>455-981-221</td>
                        <td>El snort testosterone trophy driving gloves handsome</td>
                        <td>$64.50</td>
                        </tr>
                        <tr>
                        <td>1</td>
                        <td>Need for Speed IV</td>
                        <td>247-925-726</td>
                        <td>Wes Anderson umami biodiesel</td>
                        <td>$50.00</td>
                        </tr>
                        <tr>
                        <td>1</td>
                        <td>Monsters DVD</td>
                        <td>735-845-642</td>
                        <td>Terry Richardson helvetica tousled street art master</td>
                        <td>$10.70</td>
                        </tr>
                        <tr>
                        <td>1</td>
                        <td>Grown Ups Blue Ray</td>
                        <td>422-568-642</td>
                        <td>Tousled lomo letterpress</td>
                        <td>$25.99</td>
                        </tr>
                        </tbody>
                    </table>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->

                <div class="row">
                    <!-- accepted payments column -->
                    <div class="col-6">
                    <p class="lead">Payment Methods:</p>
                    <img src="../../dist/img/credit/visa.png" alt="Visa">
                    <img src="../../dist/img/credit/mastercard.png" alt="Mastercard">
                    <img src="../../dist/img/credit/american-express.png" alt="American Express">
                    <img src="../../dist/img/credit/paypal2.png" alt="Paypal">

                    <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                        Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem
                        plugg
                        dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
                    </p>
                    </div>
                    <!-- /.col -->
                    <div class="col-6">
                    <p class="lead">Amount Due 2/22/2014</p>

                    <div class="table-responsive">
                        <table class="table">
                        <tr>
                            <th style="width:50%">Subtotal:</th>
                            <td>$250.30</td>
                        </tr>
                        <tr>
                            <th>Tax (9.3%)</th>
                            <td>$10.34</td>
                        </tr>
                        <tr>
                            <th>Shipping:</th>
                            <td>$5.80</td>
                        </tr>
                        <tr>
                            <th>Total:</th>
                            <td>$265.24</td>
                        </tr>
                        </table>
                    </div>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->

                <!-- this row will not appear when printing -->
                <div class="row no-print">
                    <div class="col-12">
                    <a href="invoice-print.html" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
                    <button type="button" class="btn btn-success float-right"><i class="far fa-credit-card"></i> Submit
                        Payment
                    </button>
                    <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                        <i class="fas fa-download"></i> Generate PDF
                    </button>
                    </div>
                </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>


<?php
    $markRead = new notificationController();
    $markRead -> ctrMarkNotificationsRead();
?>
