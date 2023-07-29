<?php
require_once "../models/connection.php";
require_once "../controllers/product.controller.php";
require_once "../models/product.model.php";
require_once "../controllers/payments.controller.php";
require_once "../models/payment.model.php";
require_once "../controllers/store.controller.php";
require_once "../models/store.model.php";
require_once "../controllers/user.controller.php";
require_once "../models/user.models.php";

session_start();
// error_reporting(0);
$pdo=connection::connect();
$month=date('m');
$totalProducts = 0;
$totalusers = 0;
$totalstores = 0;

if ($_SESSION['role'] == "Administrator") {
    if (isset($_SESSION['storeid'])) {
        $item = "store_id";
        $value = $_SESSION['storeid'];
        $item1 = "store_id";
        $value1 = $_SESSION['storeid'];
        $storeid = $_SESSION['storeid'];
        $order='id';
        $products = productController::ctrShowProducts($item, $value, $order, true);
        $totalProducts = count($products);

        $merch=$pdo->prepare( 'SELECT SUM(stock * purchaseprice) AS total_value FROM products WHERE store_id = :store_id');
        $merch -> bindParam(":store_id", $value, PDO::PARAM_STR);
        $merch->execute();
        $result=$merch->fetch();

        $invoices = PaymentController::ctrShowInvoices($item1, $value1);
    }else {
        $item = 'status';
        $value = 0;
        $storeid = null;
        $item1 = null;
        $value1 = null;

        $merch=$pdo->prepare( 'SELECT SUM(stock * purchaseprice) AS total_value FROM products');
        $merch->execute();
        $result=$merch->fetch();

        $invoices = PaymentController::ctrShowInvoices($item1, $value1);
    }
    $item1 = null;
    $value1 = null;
    $users=userController::ctrShowUsers($item1,$value1);
    $totalusers=count($users);

    $stores=storeController::ctrShowStores($item1,$value1);
    $totalstores=count($stores);

    $totalSales = PaymentController::ctrAddingTotalPayments($month, $storeid);
    if ($totalSales == null) {
        $totalSales = 0;
    }
    //   var_dump($invoices);
}else {
    $storeid = $_SESSION['storeid'];
    $item = "store_id";
    $value = $_SESSION['storeid'];
    $order='id';
    $products = productController::ctrShowProducts($item, $value, $order, true);
    $totalProducts = count($products);

    $merch=$pdo->prepare( 'SELECT SUM(stock * purchaseprice) AS total_value FROM products WHERE store_id = :store_id');
    $merch -> bindParam(":store_id", $value, PDO::PARAM_STR);
    $merch->execute();
    $result=$merch->fetch();

    $totalSales = PaymentController::ctrAddingTotalPayments($month, $storeid);
    if ($totalSales == null) {
        $totalSales = 0;
    }

    $invoices = PaymentController::ctrShowInvoices($item, $value);
    //   var_dump($invoices);
}
$totalQuantity = 0;
$currentMonth = date('m'); // Get the current month

foreach ($invoices as $invoice) {
    $invoiceMonth = date('m', strtotime($invoice['startdate'])); // Get the month of the invoice
    if ($invoiceMonth == $currentMonth) {
        $products = json_decode($invoice['products'], true); // Convert the JSON string to an array
        foreach ($products as $product) {
            $quantity = isset($product['Quantity']) ? intval($product['Quantity']) : 0; // Get the quantity as an integer
            $totalQuantity += $quantity;
        }
    }
}




$storeid = $_SESSION['storeid'];
$currentYear = date('Y');

$paymentsQuery = $pdo->prepare("
  SELECT COALESCE(SUM(amount), 0) AS total_amount, MONTH(date) AS month
  FROM payments
  WHERE YEAR(date) = :year
  " . ($storeid ? "AND store_id = :store_id" : "") . "
  GROUP BY MONTH(date)
");

$expensesQuery = $pdo->prepare("
  SELECT COALESCE(SUM(amount), 0) AS total_expenses, MONTH(date) AS month
  FROM expenses
  WHERE YEAR(date) = :year
  " . ($storeid ? "AND store_id = :store_id" : "") . "
  GROUP BY MONTH(date)
");

$ordersQuery = $pdo->prepare("
  SELECT COALESCE(SUM(total), 0) AS total_expenses, MONTH(date) AS month
  FROM orders
  WHERE YEAR(date) = :year
  " . ($storeid ? "AND store_id = :store_id" : "") . "
  GROUP BY MONTH(date)
");

$paymentsQuery->bindParam(':year', $currentYear, PDO::PARAM_STR);
if ($storeid) {
    $paymentsQuery->bindParam(':store_id', $storeid, PDO::PARAM_STR);
}
$paymentsQuery->execute();
$paymentData = $paymentsQuery->fetchAll(PDO::FETCH_ASSOC);

$expensesQuery->bindParam(':year', $currentYear, PDO::PARAM_STR);
if ($storeid) {
    $expensesQuery->bindParam(':store_id', $storeid, PDO::PARAM_STR);
}
$expensesQuery->execute();
$expenseData = $expensesQuery->fetchAll(PDO::FETCH_ASSOC);

$ordersQuery->bindParam(':year', $currentYear, PDO::PARAM_STR);
if ($storeid) {
    $ordersQuery->bindParam(':store_id', $storeid, PDO::PARAM_STR);
}
$ordersQuery->execute();
$orderData = $ordersQuery->fetchAll(PDO::FETCH_ASSOC);


$months = [
  'January', 'February', 'March', 'April', 'May', 'June',
  'July', 'August', 'September', 'October', 'November', 'December'
];

$monthlyPaymentData = [];
$monthlyExpenseData = [];

foreach ($months as $month) {
  $monthData = [
    'month' => $month,
    'total_amount' => 0
  ];

  foreach ($paymentData as $data) {
    if (intval($data['month']) === array_search($month, $months) + 1) {
      $monthData['total_amount'] = $data['total_amount'];
      break;
    }
  }

  $monthlyPaymentData[] = $monthData;
}

foreach ($months as $month) {
  $monthData = [
    'month' => $month,
    'total_expenses' => 0
  ];

  foreach ($expenseData as $data) {
    if (intval($data['month']) === array_search($month, $months) + 1) {
      $monthData['total_expenses'] += $data['total_expenses']; // Accumulate expenses
      break;
    }
  }

  foreach ($orderData as $data) {
    if (intval($data['month']) === array_search($month, $months) + 1) {
      $monthData['total_expenses'] += $data['total_expenses']; // Accumulate orders
      break;
    }
  }

  $monthlyExpenseData[] = $monthData;
}





$currentYear = date('Y');
$storesQuery = $pdo->prepare("SELECT store_id, store_name, logo FROM store");
$storesQuery->execute();
$storesData = $storesQuery->fetchAll(PDO::FETCH_ASSOC);
$storeData = array();

foreach ($storesData as $store) {
    $paymentsQuery = $pdo->prepare("
        SELECT COALESCE(SUM(amount), 0) AS total_amount
        FROM payments
        WHERE YEAR(date) = :year AND store_id = :store_id
    ");
    $paymentsQuery->bindParam(':year', $currentYear, PDO::PARAM_STR);
    $paymentsQuery->bindParam(':store_id', $store['store_id'], PDO::PARAM_STR);
    $paymentsQuery->execute();
    $paymentData = $paymentsQuery->fetch(PDO::FETCH_ASSOC);

    $totalRevenue = $paymentData['total_amount'];

    $storeData[] = [
        'store_id' => $store['store_id'],
        'store_name' => $store['store_name'],
        'logo' => $store['logo'],
        'total_revenue' => $totalRevenue,
    ];
}
usort($storeData, function ($a, $b) {
    return $b['total_revenue'] - $a['total_revenue'];
});

$numDisplayedStores = min(count($storeData), 5);





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



// Return the data as JSON
$data = [
    'totalAmount' => $totalAmount,
    'paymentCount' => $paymentCount,
    'totalInvoiceAmount' => $totalInvoiceAmount,
    'invoicePaymentCount' => $invoicePaymentCount,
    'totalPaidInvoiceAmount' => $totalPaidInvoiceAmount,
    'invoicePaidCount' => $invoicePaidCount,

    'storeData' => array_slice($storeData, 0, $numDisplayedStores),

    'monthlyPayment' => $monthlyPaymentData,
    'monthlyExpense' => $monthlyExpenseData,

    'totalProducts' => $totalProducts,
    'totalMerchandise' => $result['total_value'],
    'totalSales' => $totalSales[0],
    'totalUsers' => $totalusers,
    'totalStores' => $totalstores,
    'totalQuantity' => $totalQuantity,
    'session' => $_SESSION['storeid']
];
echo json_encode($data);
?>
