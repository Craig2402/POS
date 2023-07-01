<?php
$pdo = connection::connect();
$currentYear = date('Y');

$paymentsQuery = $pdo->prepare("
  SELECT COALESCE(SUM(amount), 0) AS total_amount, MONTH(date) AS month
  FROM payments
  WHERE YEAR(date) = :year
  GROUP BY MONTH(date)
");

$expensesQuery = $pdo->prepare("
  SELECT COALESCE(SUM(amount), 0) AS total_expenses, MONTH(date) AS month
  FROM expenses
  WHERE YEAR(date) = :year
  GROUP BY MONTH(date)
");

$ordersQuery = $pdo->prepare("
  SELECT COALESCE(SUM(total), 0) AS total_expenses, MONTH(date) AS month
  FROM orders
  WHERE YEAR(date) = :year
  GROUP BY MONTH(date)
");

$paymentsQuery->bindParam(':year', $currentYear, PDO::PARAM_STR);
$paymentsQuery->execute();
$paymentData = $paymentsQuery->fetchAll(PDO::FETCH_ASSOC);

$expensesQuery->bindParam(':year', $currentYear, PDO::PARAM_STR);
$expensesQuery->execute();
$expenseData = $expensesQuery->fetchAll(PDO::FETCH_ASSOC);

$ordersQuery->bindParam(':year', $currentYear, PDO::PARAM_STR);
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
?>

<!-- BAR CHART -->
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Revenue vs Expenses</h3>

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
      <canvas id="monthlyLineGraph"></canvas>
    </div>
  </div>
  <!-- /.card-body -->
</div>
<!-- /.card -->

<script>
  const paymentData = <?php echo json_encode($monthlyPaymentData); ?>;
  const expenseData = <?php echo json_encode($monthlyExpenseData); ?>;

  const labels = paymentData.map(data => data.month);
  const paymentDataset = {
    label: 'Revenue',
    data: paymentData.map(data => data.total_amount),
    borderColor: 'rgba(54,162,235,1)',
    fill: false
  };

  const expenseDataset = {
    label: 'Expenses',
    data: expenseData.map(data => data.total_expenses),
    borderColor: 'rgba(255,99,132,1)',
    fill: false
  };

  const ctx = document.getElementById('monthlyLineGraph').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [paymentDataset, expenseDataset]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>
