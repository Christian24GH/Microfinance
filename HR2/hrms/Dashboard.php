<?php
  include __DIR__.'/components/session.php';
  // Simulated HR data for demo purposes
  $employees = [
      ['name' => 'Alice', 'department' => 'Sales', 'hire_date' => '2023-05-10'],
      ['name' => 'Bob', 'department' => 'Engineering', 'hire_date' => '2024-01-15'],
      ['name' => 'Charlie', 'department' => 'HR', 'hire_date' => '2023-11-12'],
      ['name' => 'Diana', 'department' => 'Engineering', 'hire_date' => '2024-04-03'],
      ['name' => 'Eve', 'department' => 'Marketing', 'hire_date' => '2024-03-22'],
      ['name' => 'Frank', 'department' => 'Sales', 'hire_date' => '2023-12-05'],
  ];

  // Calculate total employees
  $totalEmployees = count($employees);

  // Simulate leaves today count (static value)
  $leavesToday = 3;

  // Calculate new hires this month (for current month)
  $newHiresThisMonth = 0;
  $currentYearMonth = date('Y-m');
  foreach ($employees as $emp) {
      if (substr($emp['hire_date'], 0, 7) === $currentYearMonth) {
          $newHiresThisMonth++;
      }
  }

  // Calculate count of employees per department for chart
  $departments = [];
  foreach ($employees as $emp) {
      $dept = $emp['department'];
      if (!isset($departments[$dept])) {
          $departments[$dept] = 0;
      }
      $departments[$dept]++;
  }

  // Prepare data for chart
  $departmentLabels = json_encode(array_keys($departments));
  $departmentValues = json_encode(array_values($departments));

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR 2</title>
    <link rel="stylesheet" href="css/app.css"/>
    <link rel="stylesheet" href="css/sidebar.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
      /* Reset some default */
      body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      }
      h1 {
        text-align: center;
        margin-bottom: 1rem;
        color: #2c3e50;
      }
      .dashboard-container {
        max-width: 900px;
        margin: auto;
      }
      .cards {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        margin-bottom: 2rem;
      }
      .card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        flex: 1 1 250px;
        margin: 10px;
        padding: 20px;
        text-align: center;
        transition: transform 0.2s ease;
      }
      .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
      }
      .card .number {
        font-size: 3rem;
        font-weight: bold;
        color: #3498db;
      }
      .card .label {
        margin-top: 0.5rem;
        font-size: 1.1rem;
        color: #555;
      }
      .chart-container {
        background: #fff;
        border-radius: 10px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      }
      @media (max-width: 600px) {
        .cards {
          flex-direction: column;
          align-items: center;
        }
        .card {
          width: 80%;
          margin: 10px 0;
        }
      }
    </style>
</head>
<body class="bg-light">
  <?php 
        include __DIR__.'/components/sidebar.php'
  ?>
  <div id="main" class="ps-0 rounded-end visually-hidden">
    <?php 
        include __DIR__.'/components/header.php'
    ?>
    <div class="dashboard-container">
        <h1>HR Dashboard</h1>
        <div class="cards">
          <div class="card" style="border-top: 5px solid #3498db;">
            <div class="number"><?php echo $totalEmployees; ?></div>
            <div class="label">Total Employees</div>
          </div>
          <div class="card" style="border-top: 5px solid #e74c3c;">
            <div class="number"><?php echo $leavesToday; ?></div>
            <div class="label">Leaves Today</div>
          </div>
          <div class="card" style="border-top: 5px solid #2ecc71;">
            <div class="number"><?php echo $newHiresThisMonth; ?></div>
            <div class="label">New Hires This Month</div>
          </div>
        </div>

        <div class="chart-container px-5">
          <h2>Employee Distribution by Department</h2>
          <div class="container w-50">
            <canvas id="deptChart"></canvas>
          </div>
        </div>
    </div>
  </div>
  <!-- Chart.js CDN  -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const ctx = document.getElementById('deptChart').getContext('2d');
    const deptChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: <?php echo $departmentLabels; ?>,
        datasets: [{
          label: 'Employees',
          data: <?php echo $departmentValues; ?>,
          backgroundColor: [
            '#3498db',
            '#e74c3c',
            '#2ecc71',
            '#f1c40f',
            '#9b59b6',
            '#34495e',
          ],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              font: {
                size: 14
              }
            }
          }
        }
      }
    });
  </script>
  <script src="js/sidebar.js"></script>
</body>
</html>