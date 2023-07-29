<?php

    $pdo = connection::connect();
    $storeid = $_SESSION['storeid'];

    // Get the current month and year
    $currentMonth = date('m');
    $currentYear = date('Y');

    // Prepare the SQL statement
    $payments = $pdo->prepare("SELECT COUNT(*) AS paymentCount, SUM(amount) AS totalAmount FROM payments WHERE MONTH(date) = :month AND YEAR(date) = :year " . ($storeid ? "AND store_id = :store_id" : "") . "");
    $payments->bindParam(':month', $currentMonth);
    $payments->bindParam(':year', $currentYear);
    if ($storeid) {
        $payments->bindParam(':store_id', $storeid, PDO::PARAM_STR);
    }
    $payments->execute();

    // Fetch the result
    $paymentResult = $payments->fetch();

    // Retrieve the count and total amount
    $paymentCount = $paymentResult['paymentCount'];
    $totalAmount = $paymentResult['totalAmount'];

    // Prepare the SQL statement
    $invoices = $pdo->prepare("SELECT COUNT(*) AS invoicePaymentCount, SUM(total) AS totalInvoiceAmount FROM invoices WHERE MONTH(datecreated) = :month AND YEAR(datecreated) = :year " . ($storeid ? "AND store_id = :store_id" : "") . "");
    $invoices->bindParam(':month', $currentMonth);
    $invoices->bindParam(':year', $currentYear);
    if ($storeid) {
        $invoices->bindParam(':store_id', $storeid, PDO::PARAM_STR);
    }
    $invoices->execute();

    // Fetch the result
    $paymentInvoiceResult = $invoices->fetch();

    // Retrieve the count and total amount
    $invoicePaymentCount = $paymentInvoiceResult['invoicePaymentCount'];
    $totalInvoiceAmount = $paymentInvoiceResult['totalInvoiceAmount'];

    // Prepare the SQL statement
    $paidinvoices = $pdo->prepare("SELECT COUNT(*) AS invoicePaidCount, SUM(total) AS totalPaidInvoiceAmount FROM invoices WHERE MONTH(datecreated) = :month AND YEAR(datecreated) = :year AND dueamount = 0 " . ($storeid ? "AND store_id = :store_id" : "") . "");
    $paidinvoices->bindParam(':month', $currentMonth);
    $paidinvoices->bindParam(':year', $currentYear);
    if ($storeid) {
        $paidinvoices->bindParam(':store_id', $storeid, PDO::PARAM_STR);
    }
    $paidinvoices->execute();

    // Fetch the result
    $paymentInvoiceResult = $paidinvoices->fetch();

    // Retrieve the count and total amount
    $invoicePaidCount = $paymentInvoiceResult['invoicePaidCount'];
    $totalPaidInvoiceAmount = $paymentInvoiceResult['totalPaidInvoiceAmount'];
?>

<style>
.card-custom {
    background-color: #f8f9fa;
    border: 1px solid #ced4da;
    border-radius: 5px;
    transition: transform 0.3s ease-in-out;
}

.card-custom:hover {
    transform: translateY(-5px);
}

.card-custom .card-body {
    padding: 1.5rem;
}

.card-custom .card-title {
    font-size: 1.2rem;
    font-weight: bold;
    margin-bottom: 1rem;
}

.card-custom .card-text {
    font-size: 0.9rem;
    color: #777;
}

.card-custom i.fa-solid {
    color: #007bff;
}

.card-custom p.text-right {
    font-size: 1.2rem;
    font-weight: bold;
    color: #28a745;
}
.custom-heading {
    color: #333;
    font-size: 1.2rem;
    font-weight: bold;
    margin-bottom: 0;
    text-decoration: none;
}
.btn-primary {
    transition: background-color 0.3s ease-in-out;
}

.btn-primary:hover {
    background-color: #fff;
    color: #007bff;
}

</style>

<div class="content-wrapper">
<!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Admin Dashboard</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                        <li class="breadcrumb-item active">Admin Dashboard </li>
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
                <div class="col-lg-4 col-md-6">
                    <div class="card card-custom">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                            <i class="fa-solid fa-money-bill-wave fa-2xl mr-3"></i>
                                <h5 class="card-title mb-0 custom-heading">Payments</h5>
                            </div>
                            <div class="row mt-3">
                                <div class="col-6">
                                    <p class="card-text" id="totalAmount"></p>
                                </div>
                                <div class="col-6">
                                    <p class="card-text text-right" id="paymentCount"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-4 col-md-6">
                    <div class="card card-custom">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-file-invoice fa-2xl mr-3"></i>
                                <h5 class="card-title mb-0 custom-heading">Invoices</h5>
                            </div>
                            <div class="row mt-3">
                                <div class="col-6">
                                    <p class="card-text" id="totalInvoiceAmount"></p>
                                </div>
                                <div class="col-6">
                                    <p class="card-text text-right" id="invoicePaymentCount"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-4 col-md-6">
                    <div class="card card-custom">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                            <i class="fa-solid fa-receipt fa-2xl mr-3"></i>
                                <h5 class="card-title mb-0 custom-heading">Paid Invoices</h5>
                            </div>
                            <div class="row mt-3">
                                <div class="col-6">
                                    <p class="card-text" id="totalPaidInvoiceAmount"></p>
                                </div>
                                <div class="col-6">
                                    <p class="card-text text-right" id="invoicePaidCount"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6">
                  <?php
                    include 'finance-graphs/invoices-period.php';
                  ?>
                </div>
                <div class="col-lg-6">
                  <?php
                    include 'finance-graphs/payments-period.php';
                  ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                  <?php
                    include 'finance-graphs/revenue.php';
                  ?>
                </div>
                <div class="col-lg-6">
                  <?php
                    include 'finance-graphs/daily-monthly.php';
                  ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.content-wrapper -->
<?php
    // $markRead = new notificationController();
    // $markRead -> ctrMarkNotificationsRead();
?>