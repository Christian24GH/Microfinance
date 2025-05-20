<!DOCTYPE html>
<html lang="en">
<head>
  <style>
    /* Main Container Styles */
    h1 {
      color: #444;
      text-align: center;
      margin-bottom: 30px;
      font-size: 2.5em;
      margin-left: -350px;

    }
    .dashboard-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
      gap: 20px;
    }
    .overview-section{
        background-color: #272c47 ;
    }
    /* Metric Card Styles */
    .metric-card {
      background-color: #ffffff;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      font-size: 1.2em;
      font-weight: bold;
      text-align: center;
      transition: transform 0.2s, box-shadow 0.2s;
    }

    .metric-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    }

    /* Section Headers */
    h2 {
      color: white;
      margin-bottom: 15px;
      font-size: 1.8em;
      border-bottom: 2px solid #ddd;
      padding-bottom: 10px;
    }

    /* Table Styles */
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    thead th {
      background-color: #007bff;
      color: #fff;
      padding: 10px;
      text-align: left;
    }

    tbody td {
      background-color: #fff;
      padding: 12px 10px;
      border-bottom: 1px solid #ddd;
    }

    tbody tr:hover td {
      background-color: #f1f1f1;
    }

    /* Reminders Section */
    .reminders {
      background-color: #fff8e1;
      border-left: 5px solid #ffb300;
      padding: 15px 20px;
      margin-bottom: 20px;
      border-radius: 5px;
      font-weight: bold;
    }

    /* Links in Tables */
    td a {
      color: #007bff;
      text-decoration: none;
      font-weight: bold;
    }

    td a:hover {
      text-decoration: underline;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      h1 {
        font-size: 2em;
      }

      h2 {
        font-size: 1.5em;
      }

      .metric-card {
        font-size: 1em;
        padding: 15px;
      }
    }
  </style>
</head>
<body style="background-color: #5cbc9c !important;">
  <div id="main-content">
    <h1>Welcome to the Dashboard</h1>
    <div class="dashboard-container">
      <div class="overview-section">
        <div class="metric-card">Total Employees: 5</div>
        <div class="metric-card payroll-status">Payroll Status: Processing</div>
        <div class="metric-card total-payroll">Total Payroll: ₱1,250,000</div>
        <div class="reminders">Upcoming: Timesheet Due - May 20th</div>
      </div>

      <div class="overview-section">
        <h2>Employee Statistics</h2>
        <div class="metric-card">New Hires: 5</div>
        <div class="metric-card">Pending Timesheets: 12</div>
      </div>

      <div class="overview-section">
        <h2>Payroll Breakdown (Current)</h2>
        <div class="metric-card">Total Salaries: ₱900,000</div>
        <div class="metric-card">Total Allowances: ₱200,000</div>
        <div class="metric-card">Total Deductions: ₱50,000</div>
        <div class="metric-card">Net Pay: ₱1,050,000</div>
      </div>

      <div class="overview-section">
        <h2>Recent Payroll Activity</h2>
        <table>
          <thead>
            <tr>
              <th>Cycle</th>
              <th>Pay Date</th>
              <th>Total Paid</th>
              <th>Status</th>
              <th>Details</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>2025-04</td>
              <td>2025-04-30</td>
              <td>₱1,100,000</td>
              <td>Completed</td>
              <td><a href="#">View</a></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
