<!DOCTYPE html>
<html lang="en">
<head>
  <style>
    * {
      box-sizing: border-box;
    }

    header {
      background-color: #2e86de;
      color: white;
      padding: 20px;
      text-align: center;
    }

    .container003 {
      max-width: 1200px;
      margin: 30px auto;
      padding: 20px;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    h2 {
      color: #2e86de;
      margin-bottom: 20px;
    }

    .summary-cards {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
      margin-bottom: 30px;
    }

    .card {
      flex: 1;
      min-width: 220px;
      background-color: #eaf3ff;
      border-left: 4px solid #2e86de;
      border-radius: 8px;
      padding: 15px;
    }

    .card h3 {
      margin: 0;
      font-size: 16px;
      color: #2c3e50;
    }

    .card p {
      font-size: 22px;
      font-weight: bold;
      margin: 5px 0 0;
      color: #2e86de;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      padding: 12px;
      border-bottom: 1px solid #ddd;
      text-align: left;
    }

    th {
      background-color: #2e86de;
      color: white;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .highlight {
      color: #2e86de;
      font-weight: bold;
    }
  </style>
</head>
<body style="background-color: #5cbc9c !important;"></body>
  <header>
    <h1>Compensation Planning & Administration Dashboard</h1>
  </header>

  <div class="container003">
    <h2>Bonus & Incentive Planning</h2>
    <div class="summary-cards">
      <div class="card">
        <h3>Total Bonus Budget</h3>
        <p>1,000,000</p>
      </div>
      <div class="card">
        <h3>Avg. Incentive per Employee</h3>
        <p>In Progress</p>
      </div>
      <div class="card">
        <h3>Top Performing Dept</h3>
        <p>Finance & Credit Department</p>
      </div>
      <div class="card">
        <h3>Remaining Budget</h3>
        <p>In Progress</p>
      </div>
    </div>

    <h2>Incentive Allocation Table</h2>
    <table>
      <thead>
        <tr>
          <th>Employee Name</th>
          <th>Department</th>
          <th>Performance Score</th>
          <th>Bonus %</th>
          <th>Bonus Amount</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Gon</td>
          <td>Finance</td>
          <td>100</td>
          <td>100%</td>
          <td class="highlight">5,000</td>
        </tr>
        <tr>
          <td>Killua</td>
          <td>Credit Department</td>
          <td>100</td>
          <td>100&</td>
          <td class="highlight">5,000</td>
        </tr>
        <tr>
          <td>Kurapika</td>
          <td>Management</td>
          <td>0</td>
          <td>0%</td>
          <td class="highlight">In Progress</td>
        </tr>
      </tbody>
    </table>

  </div>

  <style>
    * {
      box-sizing: border-box;
    }
    .container04 {
      max-width: 1150px;
      margin: 30px auto;
      background-color: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .benefit-cards {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin-bottom: 30px;
    }

    .card {
      flex: 1;
      min-width: 220px;
      background-color: #eafaf7;
      border-left: 4px solid #272c47;
      border-radius: 8px;
      padding: 15px;
    }

    .card h3 {
      margin: 0 0 8px;
      font-size: 16px;
      color: #2c3e50;
    }

    .card p {
      font-size: 14px;
      color: #555;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      padding: 12px;
      border-bottom: 1px solid #ddd;
      text-align: left;
    }

    th {
      background-color: #272c47;
      color: white;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .status {
      font-weight: bold;
      color: #272c47;
    }
  </style>
<body>

  <header>
    <h1>Benefits Administration</h1>
  </header>

  <div class="container04">
    <h2>Available Benefits</h2>
    <div class="benefit-cards">
      <div class="card">
        <h3>Health Insurance</h3>
        <p>Comprehensive coverage with 3 plan options.</p>
      </div>
      <div class="card">
        <h3>Dental & Vision</h3>
        <p>Annual cleanings, eye exams, and corrective lenses.</p>
      </div>
      <div class="card">
        <h3>Retirement Plan</h3>
        <p>401(k) with company match up to 5%.</p>
      </div>
      <div class="card">
        <h3>Paid Time Off</h3>
        <p>Vacation, personal days, and holidays included.</p>
      </div>
    </div>

    <h2>Employee Benefits Enrollment</h2>
    <table>
      <thead>
        <tr>
          <th>Employee</th>
          <th>Health</th>
          <th>Dental</th>
          <th>Vision</th>
          <th>401(k)</th>
          <th>PTO Balance</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Gon</td>
          <td class="status">Enrolled</td>
          <td class="status">Enrolled</td>
          <td class="status">Enrolled</td>
          <td class="status">Active</td>
          <td>14 days</td>
        </tr>
        <tr>
          <td>Killua</td>
          <td class="status">Enrolled</td>
          <td class="status">Enrolled</td>
          <td class="status">Enrolled</td>
          <td class="status">Active</td>
          <td>15 days</td>
        </tr>
        <tr>
          <td>Kurapika</td>
          <td class="status">Not Enrolled</td>
          <td class="status">Not Enrolled</td>
          <td class="status">Not Enrolled</td>
          <td class="status">Inactive</td>
          <td>8 days</td>
        </tr>
      </tbody>
    </table>

  </div>

  <style>
    * {
      box-sizing: border-box;
    }

    .container05 {
      max-width: 1100px;
      margin: 30px auto;
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }


    .criteria-list {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin-bottom: 30px;
    }

    .criterion {
      flex: 1;
      min-width: 200px;
      background: #eaf2f8;
      border-left: 4px solid #2980b9;
      padding: 15px;
      border-radius: 6px;
    }

    .criterion h3 {
      margin: 0 0 10px;
      font-size: 16px;
      color: #2c3e50;
    }

    .criterion p {
      font-size: 14px;
      color: #555;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #2980b9;
      color: white;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .score {
      font-weight: bold;
      color: #2980b9;
    }


  </style>

<body>


  <header>
    <h1>Job Evaluation</h1>
  </header>

  <div class="container05">
    <h2>Evaluation Criteria</h2>
    <div class="criteria-list">
      <div class="criterion">
        <h3>Skills Required</h3>
        <p>Measures the level and type of skills necessary to perform the job.</p>
      </div>
      <div class="criterion">
        <h3>Responsibility</h3>
        <p>Assesses accountability and decision-making authority.</p>
      </div>
      <div class="criterion">
        <h3>Work Conditions</h3>
        <p>Evaluates physical and mental environment factors.</p>
      </div>
      <div class="criterion">
        <h3>Experience Level</h3>
        <p>Considers prior experience or education needed.</p>
      </div>
    </div>

    <h2>Job Evaluation Table</h2>
    <table>
      <thead>
        <tr>
          <th>Job Title</th>
          <th>Skills</th>
          <th>Responsibility</th>
          <th>Work Conditions</th>
          <th>Experience</th>
          <th>Total Score</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Software Engineer</td>
          <td>In Progress</td>
          <td>In Progress</td>
          <td>In Progress</td>
          <td>In Progress</td>
          <td class="score">In Progress</td>
        </tr>
        <tr>
          <td>HR Manager</td>
          <td>In Progress</td>
          <td>In Progress</td>
          <td>In Progress</td>
          <td>In Progress</td>
          <td class="score">In Progress</td>
        </tr>
        <tr>
          <td>Customer Support</td>
          <td>In Progress</td>
          <td>In Progress</td>
          <td>In Progress</td>
          <td>In Progress</td>
          <td class="score">In Progress</td>
        </tr>
      </tbody>
    </table>

    
  </div>

</body>
</html>