<?php
$pdo = connection::connect();
// Get the current year
$currentYear = date('Y');

// Prepare the query to fetch payments for the current year
$paymentsQuery = $pdo->prepare("
  SELECT COALESCE(SUM(amount), 0) AS total_amount, MONTH(date) AS month
  FROM payments
  WHERE YEAR(date) = :year
  GROUP BY MONTH(date)
");

// Prepare the query to fetch expenses for the current year
$expensesQuery = $pdo->prepare("
  SELECT COALESCE(SUM(amount), 0) AS total_expenses, MONTH(date) AS month
  FROM expenses
  WHERE YEAR(date) = :year
  GROUP BY MONTH(date)
");

$paymentsQuery->bindParam(':year', $currentYear, PDO::PARAM_STR);
$paymentsQuery->execute();
$paymentData = $paymentsQuery->fetchAll(PDO::FETCH_ASSOC);

$expensesQuery->bindParam(':year', $currentYear, PDO::PARAM_STR);
$expensesQuery->execute();
$expenseData = $expensesQuery->fetchAll(PDO::FETCH_ASSOC);

// Create an array with all months of the year
$months = [
  'January', 'February', 'March', 'April', 'May', 'June',
  'July', 'August', 'September', 'October', 'November', 'December'
];

// Initialize arrays to store the monthly payment and expense data
$monthlyPaymentData = [];
$monthlyExpenseData = [];

// Loop through all months and populate the payment and expense data
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
      $monthData['total_expenses'] = $data['total_expenses'];
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
  // Retrieve the payment and expense data from the server-side
  const paymentData = <?php echo json_encode($monthlyPaymentData); ?>;
  const expenseData = <?php echo json_encode($monthlyExpenseData); ?>;

  // Extract labels and dataset values from payment data
  const labels = paymentData.map(data => data.month);
  const paymentDataset = {
    label: 'Revenue',
    data: paymentData.map(data => data.total_amount),
    borderColor: 'rgba(54,162,235,1)',
    fill: false
  };

  // Extract dataset values from expense data
  const expenseDataset = {
    label: 'Expenses',
    data: expenseData.map(data => data.total_expenses),
    borderColor: 'rgba(255,99,132,1)',
    fill: false
  };

  // Create the line chart
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
