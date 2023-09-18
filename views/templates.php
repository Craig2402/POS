<?php
    session_start();
    require_once 'controllers/activitylog.controller.php';
    require_once 'controllers/packagevalidate.controller.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inventory | System</title>
    
    <link rel="shortcut icon" href="views\img\company\afripos-logo.jpg" type="image/x-icon">

    <!-- Include jQuery via CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- =============================================
    STYLE SHEETS
    =============================================  -->
    <!-- custom stylesheet -->
    <link rel="stylesheet" href="views/css/custom.css">
    <link rel="stylesheet" href="views/css/main.css">
    <link rel="stylesheet" href="views/css/util.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="views/dist/css/adminlte.min.css">
    
    <!-- Theme style -->
    <link rel="stylesheet" href="views/dist/css/adminlte.min.css">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!--  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <!-- Select2 -->
    <link rel="stylesheet" href="views/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="views/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    
    <!-- daterange picker -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="views/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    

    
    <!-- =============================================
    SCRIPTS
    =============================================  -->
    <!-- Include Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables & Plugins -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- Responsive Extension JavaScript -->
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    
    <!-- AdminLTE App -->
    <script src="views/dist/js/adminlte.min.js"></script>
    <script src="views/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <!-- Select2 -->
    <script src="views/plugins/select2/js/select2.full.min.js"></script>

    <!-- daterange picker -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    
    <!-- chart js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="views/plugins/chart.js/Chart.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.js"></script>
    

</head>
<body class="hold-transition sidebar-mini">
<?php
if (isset($_SESSION['beginSession']) && $_SESSION['beginSession'] == 'ok') {
    
    $element="paymentvalidation";
    $table= "customers";
    $countAll=null;
    $organisationcode=$_SESSION["organizationcode"];

    $days=packagevalidateController::ctrPackageValidate($element, $table, $countAll, $organisationcode);

    if($days<=0){
        include 'modules/renewal.php';
    }else{
            if (isset($_SESSION['role']) && $_SESSION['role'] == "Administrator" || $_SESSION['role'] == 404) {
                include 'modules/header.php';
                include 'modules/main-menu.php';
        
                if (isset($_GET['route'])) {
                    // Check if the requested route is valid for the Administrator role
                    if ($_GET['route'] == "dashboard" ||
                        $_GET['route'] == "renewal" ||
                        $_GET['route'] == "registration" ||
                        $_GET['route'] == "addproduct"||
                        $_GET['route'] == "products" ||
                        $_GET['route'] == "changepassword" ||
                        $_GET['route'] == "category" ||
                        $_GET['route'] == "printbarcode" ||
                        $_GET['route'] == "viewproduct" ||
                        $_GET['route'] == "tax" ||
                        $_GET['route'] == "pos" ||
                        $_GET['route']=="transactions" ||
                        $_GET['route'] == "discount" ||
                        $_GET['route'] == "invoices" ||
                        $_GET['route'] == "payment" ||
                        $_GET['route'] == "sales" ||
                        $_GET['route'] == "sales-reports" ||
                        $_GET['route'] == "stock" ||
                        $_GET['route'] == "suppliers" ||
                        $_GET['route'] == "expenses" ||
                        $_GET['route'] == "orders" ||
                        $_GET['route'] == "returns" ||
                        $_GET['route'] == "vieworders" ||
                        $_GET['route'] == "stores" ||
                        $_GET['route'] == "manage-stores" ||
                        $_GET['route'] == "view-returned" ||
                        $_GET['route'] == "finance-dashboard" ||
                        $_GET['route'] == "logs"||
                        $_GET['route'] == "logout"
                    ) {
                        include "modules/" . $_GET['route'] . ".php";
                    } else {
                        include "modules/404.php";
                    }
                } else {
                    include "modules/dashboard.php"; // Default page for Administrator
                }
                include 'modules/footer.php';
                echo '</div>';


            }elseif (isset($_SESSION['role']) && $_SESSION['role'] == "Supervisor") {
            include 'modules/header.php';
            include 'modules/main-menu.php';

            if (isset($_GET['route'])) {
                // Check if the requested route is valid for the Administrator role
                if ($_GET['route'] == "dashboard" ||
                    $_GET['route'] == "registration" ||
                    $_GET['route'] == "addproduct"||
                    $_GET['route'] == "products" ||
                    $_GET['route'] == "changepassword" ||
                    $_GET['route'] == "category" ||
                    $_GET['route'] == "printbarcode" ||
                    $_GET['route'] == "viewproduct" ||
                    $_GET['route'] == "tax" ||
                    $_GET['route'] == "pos" ||
                    $_GET['route']=="transactions" ||
                    $_GET['route'] == "discount" ||
                    $_GET['route'] == "invoices" ||
                    $_GET['route'] == "payment" ||
                    $_GET['route'] == "sales" ||
                    $_GET['route'] == "stock" ||
                    $_GET['route'] == "suppliers" ||
                    $_GET['route'] == "expenses" ||
                    $_GET['route'] == "orders" ||
                    $_GET['route'] == "returns" ||
                    $_GET['route'] == "vieworders" ||
                    $_GET['route'] == "stores" ||
                    $_GET['route'] == "manage-stores" ||
                    $_GET['route'] == "view-returned" ||
                    $_GET['route'] == "finance-dashboard" ||
                    $_GET['route'] == "logout"
                ) {
                    include "modules/" . $_GET['route'] . ".php";
                } else {
                    include "modules/404.php";
                }
            } else {
                include "modules/dashboard.php"; // Default page for supervisor
            }
            include 'modules/footer.php';
            echo '</div>';
        } elseif (isset($_SESSION['role']) && $_SESSION['role'] == "Seller") {
            include 'modules/header.php';
            include 'modules/seller-menu.php';

        if (isset($_GET['route'])) {
            // Check if the requested route is valid for the Seller role
            if ($_GET['route'] == "dashboard" ||
                $_GET['route'] == "products" ||
                $_GET['route'] == "changepassword" ||
                $_GET['route'] == "viewproduct" ||
                $_GET['route'] == "pos" ||
                $_GET['route'] == "invoices" ||
                $_GET['route'] == "stk_initiate" ||
                $_GET['route'] == "payment" ||
                $_GET['route'] == "logout"
            ) {
            include "modules/" . $_GET['route'] . ".php";
            } else {
            include "modules/404.php";
            }
        } else {
            include "modules/pos.php"; // Default page for Seller
        }
        include 'modules/footer.php';
        echo '</div>';
        } else {
        include 'modules/header.php';
        include 'modules/store-menu.php';

        if (isset($_GET['route'])) {
            // Check if the requested route is valid for the Store role
            if ($_GET['route'] == "products" ||
                $_GET['route'] == "changepassword" ||
                $_GET['route'] == "category" ||
                $_GET['route'] == "addproduct"||
                $_GET['route'] == "viewproduct" ||
                $_GET['route'] == "printbarcode" ||
                $_GET['route'] == "stock" ||
                $_GET['route'] == "discount" ||
                $_GET['route'] == "logout"
            ) {
            include "modules/" . $_GET['route'] . ".php";
            } else {
            include "modules/404.php";
            }
        } else {
            include "modules/stock.php"; // Default page for Store
        }
        include 'modules/footer.php';
        echo '</div>';
        }
    }
}
else {
  echo '<div class="login-page">';
  include 'modules/login.php';
  echo '</div>';
}

?>

</div>
<!-- ./wrapper -->





<script src="views/js/user.js"></script>
<script src="views/js/categories.js"></script>
<script src="views/js/product.js"></script>
<script src="views/js/tax.js"></script>
<script src="views/js/pos.js"></script>
<script src="views/js/discount.js"></script>
<script src="views/js/invoices.js"></script>
<script src="views/js/payment.js"></script>
<script src="views/js/sales.js"></script>
<script src="views/js/supplier.js"></script>
<script src="views/js/expenses.js"></script>
<script src="views/js/orders.js"></script>
<script src="views/js/returns.js"></script>
<script src="views/js/notifications.js"></script>
<script src="views/js/store.js"></script>
<script src="views/js/graphs.js"></script>
<script src="views/js/header.js"></script>
<script src="views/js/main.js"></script>



<!-- datatable js -->
<script>
//   $(function () {
//       //DataTable initialization
//       $("#example1").DataTable({
//           "responsive": true,
//           "lengthChange": false,
//           "autoWidth": false
//       }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
//   });  
  
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })


$('#example1').DataTable({
    pagingType: 'full_numbers',
    responsive: true,
    stateSave: true
});
</script>
</body>
</html>
