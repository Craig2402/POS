<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inventory | System</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

   <!-- chart js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="views/plugins/chart.js/Chart.min.js"></script>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.js"></script>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="views/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="views/dist/css/adminlte.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="views/plugins/sweetalert2//sweetalert2.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="views/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="views/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="views/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="views/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="views/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="views/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Morris chart -->
<link rel="stylesheet" href="views/bower_components/morris.js/morris.css">
   <!-- Ionicons -->
   <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="views/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- daterange picker -->
  <!-- <script src="views/plugins/daterangepicker/daterangepicker.css"></script> -->
  <!-- pdf generator -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    .tableFixHead{
        overflow: scroll;
        height:520px;
    }
    .tableFixHead thead h{
        position: sticky;
        top: 0;
        z-index: 1;
    }
    table{
        border-collapse: collapse;
    }
    th,td{
        padding: 8px 16px;
    }
    th{
        background: #eee;
    }
</style>

</head>
<body class="hold-transition sidebar-mini">
<?php
if (isset($_SESSION['beginSession']) && $_SESSION['beginSession'] == 'ok') {
    if (isset($_SESSION['role']) && $_SESSION['role'] == "Administrator") {
    include 'modules/header.php';
    include 'modules/admin-menu.php';

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
            $_GET['route'] == "taxdis" ||
            $_GET['route'] == "pos" ||
            $_GET['route']=="transactions" ||
            $_GET['route'] == "discount" ||
            $_GET['route'] == "invoices" ||
            $_GET['route'] == "payment" ||
            $_GET['route'] == "sales" ||
            $_GET['route'] == "stock" ||
            $_GET['route'] == "suppliers" ||
            $_GET['route'] == "expenses" ||
            $_GET['route'] == "finance-dashboard" ||
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
} else {
  echo '<div class="login-page">';
  include 'modules/login.php';
  echo '</div>';
}
?>

</div>
<!-- ./wrapper -->

 <!-- Include the Inputmask library CSS file -->
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.css">

 <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- Include the Inputmask library JavaScript file and jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>

<!-- <script src="views/plugins/moment/moment.min.js"></script> -->
<!-- jQuery -->
<script src="views/plugins/jquery/jquery.min.js"></script>

<!-- date-range-picker -->
<!-- <script src="views/plugins/daterangepicker/daterangepicker.js"></script> -->
<!-- Bootstrap 4 -->
<script src="views/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="views/dist/js/adminlte.min.js"></script>
<script src="views/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables & Plugins -->
<script src="views/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="views/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="views/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="views/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="views/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="views/plugins/jszip/jszip.min.js"></script>
<script src="views/plugins/pdfmake/pdfmake.min.js"></script>
<script src="views/plugins/pdfmake/vfs_fonts.js"></script>
<script src="views/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="views/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="views/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<!-- jQuery Knob Chart -->
<script src="views/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- overlayScrollbars -->
<script src="views/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="views/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


<!-- Select2 -->
<script src="views/plugins/select2/js/select2.full.min.js"></script>
<!-- bs-custom-file-input -->
<script src="views/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>



<script src="views/js/user.js"></script>
<script src="views/js/categories.js"></script>
<script src="views/js/product.js"></script>
<script src="views/js/taxdis.js"></script>
<script src="views/js/pos.js"></script>
<script src="views/js/discount.js"></script>
<script src="views/js/invoices.js"></script>
<script src="views/js/payment.js"></script>
<script src="views/js/sales.js"></script>
<script src="views/js/supplier.js"></script>
<script src="views/js/expenses.js"></script>

<!-- datatable js -->
<script>
  $(function () {
      //DataTable initialization
      $("#example1").DataTable({
          "responsive": true,
          "lengthChange": false,
          "autoWidth": false,
          "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  });  
  
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
</script>
</body>
</html>
