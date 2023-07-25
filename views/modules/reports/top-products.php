<?php
$item = null; 
$value = null;

if ($_SESSION['role'] == "Administrator") {
  $item = "store_id";
  if (isset($_GET['store-id'])) {
    $value = $_GET['store-id'];
  }
}else {
  $item = "store_id";
  $value = $_SESSION['storeid'];
}
$order = 'sales';
$product = productController::ctrShowProducts($item, $value, $order, true);
// var_dump($product);
$totalSales = productController::ctrAddingTotalSales();
$color = array(
  'red',      // Red
  'green',    // Green
  'blue',     // Blue
  'yellow',   // Yellow
  'magenta',  // Magenta
  'cyan',     // Cyan
  'purple',   // Purple
  'orange',   // Orange
  'gold',     // Dark Green
  'navy'      // Navy
);
?>

<div class="card">
  <div class="card-header">
    <h3 class="card-title">Best selling products</h3>

    <div class="card-tools">
      <button type="button" class="btn btn-tool" data-card-widget="collapse">
        <i class="fas fa-minus"></i>
      </button>

    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body">
    <div class="row">
      <div class="col-md-8">
        <div class="chart-responsive">
          <canvas id="pieChart" height="150"></canvas>
        </div>
        <!-- ./chart-responsive -->
      </div>
      <!-- /.col -->
      <div class="col-md-4">
        <ul class="chart-legend clearfix">
          <?php
          $numProducts = count($product);
          for ($i = 0; $i < $numProducts; $i++) {
            echo '<li><i class="far fa-circle text-' . $color[$i % 10] . '"></i> ' . $product[$i]['product'] .'<br></li>';
          }
          ?>
        </ul>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.card-body -->
  <div class="card-footer p-0">
    <ul class="nav nav-pills flex-column">
      <?php
      $numDisplayedProducts = min($numProducts, 5);
      for ($i = 0; $i < $numDisplayedProducts; $i++) {
        echo '<li class="nav-item">
                <a href="index.php?route=viewproduct&barcode='.$product[$i]['barcode'].'" class="nav-link">
                <img src="'.$product[$i]["image"].'" class="img-thumbnail" width="60px" style="margin-right:10px"> 
                  ' . $product[$i]['product'] .  '
                  <span class="float-right text-' . $color[$i % 10] . '">
                   
                    ' .$product[$i]['sales'].' units
                  </span>
                </a>
              </li>';
      }
      ?>
    </ul>
  </div>
  <!-- /.footer -->
</div>
<!-- /.card -->

<script>
  //-------------
  // - PIE CHART -
  //-------------
  // Get context with jQuery - using jQuery's .get() method.
  var pieChartCanvas = $('#pieChart').get(0).getContext('2d');
  var pieData = {
    labels: [
      <?php
      for ($i = 0; $i < $numProducts; $i++) {
        echo "'" . $product[$i]['product'] . "'";
        if ($i < $numProducts - 1) {
          echo ",";
        }
      }
      ?>
    ],
    datasets: [{
      data: [
        <?php
        for ($i = 0; $i < $numProducts; $i++) {
          echo $product[$i]['sales'];
          if ($i < $numProducts - 1) {
            echo ",";
          }
        }
        ?>
      ],
      backgroundColor: [
        <?php
        for ($i = 0; $i < $numProducts; $i++) {
          echo "'" . $color[$i % 10] . "'";
          if ($i < $numProducts - 1) {
            echo ",";
          }
        }
        ?>
      ]
    }]
  };
  var pieOptions = {
    legend: {
      display: false
    }
  };
  // Create pie or doughnut chart
  // You can switch between pie and doughnut using the method below.
  // eslint-disable-next-line no-unused-vars
  var pieChart = new Chart(pieChartCanvas, {
    type: 'doughnut',
    data: pieData,
    options: pieOptions
  });

  //-----------------
  // - END PIE CHART -
  //-----------------
</script>
