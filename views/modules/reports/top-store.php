<?php
$pdo = connection::connect();
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
    $paymentsQuery->bindParam(':store_id', $store['store_id'], PDO::PARAM_INT);
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
        <h3 class="card-title">Top Performing Stores</h3>
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
                    $numDisplayedStores = min(count($storeData), 5);
                    for ($i = 0; $i < $numDisplayedStores; $i++) {
                        echo '<li><i class="far fa-circle text-' . $color[$i % 10] . '"></i> ' . $storeData[$i]['store_name'] . '<br></li>';
                    }
                    ?>
                </ul>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.card-body -->

    <!-- New Section: Display top performing stores as a list -->
    <div class="card-footer p-0">
        <ul class="nav nav-pills flex-column">
            <?php
            $numDisplayedStores = min(count($storeData), 5);
            for ($i = 0; $i < $numDisplayedStores; $i++) {
                echo '<li class="nav-item">
                        <a href="index.php?store-id=' . $storeData[$i]['store_id'] . '" class="nav-link">
                            <img src="' . $storeData[$i]["logo"] . '" class="img-thumbnail" width="60px" style="margin-right:10px"> 
                            ' . $storeData[$i]['store_name'] . '
                            <span class="float-right text-' . $color[$i % 10] . '">
                                Ksh ' . number_format($storeData[$i]['total_revenue'], 2) . '
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
            for ($i = 0; $i < $numDisplayedStores; $i++) {
                echo "'" . $storeData[$i]['store_name'] . "'";
                if ($i < $numDisplayedStores - 1) {
                    echo ",";
                }
            }
            ?>
        ],
        datasets: [{
            data: [
                <?php
                for ($i = 0; $i < $numDisplayedStores; $i++) {
                    echo $storeData[$i]['total_revenue'];
                    if ($i < $numDisplayedStores - 1) {
                        echo ",";
                    }
                }
                ?>
            ],
            backgroundColor: [
                <?php
                for ($i = 0; $i < $numDisplayedStores; $i++) {
                    echo "'" . $color[$i % 10] . "'";
                    if ($i < $numDisplayedStores - 1) {
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
