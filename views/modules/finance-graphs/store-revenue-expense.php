<?php
$pdo = connection::connect();
$currentYear = date('Y');
$currentMonth = date('m');


$storesQuery = $pdo->prepare("SELECT store_id, store_name FROM store");
$storesQuery->execute();
$storesData = $storesQuery->fetchAll(PDO::FETCH_ASSOC);

$storeData = array();

foreach ($storesData as $store) {
    $paymentsQuery = $pdo->prepare("
        SELECT COALESCE(SUM(amount), 0) AS total_amount
        FROM payments
        WHERE YEAR(date) = :year AND MONTH(date) = :month AND store_id = :store_id
    ");

    $expensesQuery = $pdo->prepare("
        SELECT COALESCE(SUM(amount), 0) AS total_expenses
        FROM expenses
        WHERE YEAR(date) = :year AND MONTH(date) = :month AND store_id = :store_id
    ");

    $ordersQuery = $pdo->prepare("
        SELECT COALESCE(SUM(total), 0) AS total_expenses
        FROM orders
        WHERE YEAR(date) = :year AND MONTH(date) = :month AND store_id = :store_id
    ");

    $paymentsQuery->bindParam(':year', $currentYear, PDO::PARAM_STR);
    $paymentsQuery->bindParam(':month', $currentMonth, PDO::PARAM_STR);
    $paymentsQuery->bindParam(':store_id', $store['store_id'], PDO::PARAM_INT);
    $paymentsQuery->execute();
    $paymentData = $paymentsQuery->fetch(PDO::FETCH_ASSOC);

    $expensesQuery->bindParam(':year', $currentYear, PDO::PARAM_STR);
    $expensesQuery->bindParam(':month', $currentMonth, PDO::PARAM_STR);
    $expensesQuery->bindParam(':store_id', $store['store_id'], PDO::PARAM_INT);
    $expensesQuery->execute();
    $expenseData = $expensesQuery->fetch(PDO::FETCH_ASSOC);

    $ordersQuery->bindParam(':year', $currentYear, PDO::PARAM_STR);
    $ordersQuery->bindParam(':month', $currentMonth, PDO::PARAM_STR);
    $ordersQuery->bindParam(':store_id', $store['store_id'], PDO::PARAM_INT);
    $ordersQuery->execute();
    $orderData = $ordersQuery->fetch(PDO::FETCH_ASSOC);

    $totalRevenue = $paymentData['total_amount'];
    $totalExpenses = $expenseData['total_expenses'] + $orderData['total_expenses'];

    $storeData[] = [
        'store_name' => $store['store_name'],
        'total_revenue' => $totalRevenue,
        'total_expenses' => $totalExpenses
    ];
}
?>

<!-- BAR CHART -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Revenue vs Expenses - Current Month</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="chart">
            <canvas id="monthlyBarGraph"></canvas>
        </div>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->

<script>
    const storeData = <?php echo json_encode($storeData); ?>;
    const labels = storeData.map(data => data.store_name);
    const revenueData = storeData.map(data => data.total_revenue);
    const expensesData = storeData.map(data => data.total_expenses);

    const ctx = document.getElementById('monthlyBarGraph').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Revenue',
                    data: revenueData,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Expenses',
                    data: expensesData,
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });
</script>
