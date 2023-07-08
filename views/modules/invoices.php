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
                                    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search by customer name or contact">

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover text-nowrap tables">
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
                                                $item = null;
                                                $value = null;

                                                $invoices = PaymentController::ctrSalesDatesRange($initialDate, $finalDate);

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
                                                        echo '<td><button class="btn btn-success btn-xs">Paid</button></td>';
                                                    } elseif ($value["total"] == abs($value['dueamount'])) {
                                                        echo '<td><button class="btn btn-danger btn-xs">Unpaid</button></td>';
                                                    } else {
                                                        echo '<td><button class="btn btn-warning btn-xs">Partially Paid</button></td>';
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
                                                            <button idInvoice="' . $value['invoiceId'] . '" class="btn btn-s viewInvoice"><i class="fa-solid fa-eye"></i></button></td>';
                                                    }

                                                    echo '</tr>';

                                                    $select = $pdo->prepare('SELECT * FROM payments WHERE invoiceId = ?');
                                                    $select->bindParam(1, $value['invoiceId'], PDO::PARAM_STR);
                                                    $select->execute();
                                                    $paymentResult = $select->fetchAll();

                                                    if (count($paymentResult) > 0) {
                                                        echo '<tr class="expandable-body">
                                                                <td colspan="13">
                                                                    <p>
                                                                        <table class="table table-bordered">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>#</th>
                                                                                    <th>Amount</th>
                                                                                    <th>Date</th>
                                                                                    <th>Payment Method</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>';

                                                        foreach ($paymentResult as $paymentKey => $paymentVal) {
                                                            if ($paymentVal['amount'] > 0) {
                                                                echo '<tr>
                                                                        <td>' . ($paymentKey + 1) . '</td>
                                                                        <td>' . $paymentVal["amount"] . '</td>
                                                                        <td>' . $paymentVal["date"] . '</td>
                                                                        <td>' . $paymentVal["paymentmethod"] . '</td>
                                                                    </tr>';
                                                            }
                                                        }

                                                        echo '</tbody>
                                                                        </table>
                                                                    </p>
                                                                </td>
                                                            </tr>';
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
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
<?php
    $markRead = new notificationController();
    $markRead -> ctrMarkNotificationsRead();
?>
