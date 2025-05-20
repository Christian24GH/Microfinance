<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .container01 { background-color: #ffffff; padding: 15px; margin-top: 20px; border-radius: 15px; text-align: center; box-shadow: 0 10px 20px rgba(0,0,0,0.15); }
        select { background-color: #f9f9fb; border-color: #d1d5db; color: #374151; transition: border-color 0.2s, box-shadow 0.2s; }
        select:focus { border-color: #6366f1; outline: none; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25); }
        .info-section { background-color: #f9f9fb; border-radius: 12px; padding: 15px; margin-bottom: 20px; display: none; }
        .info-section h2 { color: #374151; }
        .info-section div { padding: 5px 0; color: #4b5563; }
        .info-section div strong { color: #111827; }
    </style>
</head>
<body style="background-color: #5cbc9c !important;"></body>
    <div class="container01">
        <h1 class="text-2xl font-bold mb-4">Employee Data</h1>
        <select id="employeeSelect" class="w-full mb-4 p-2 border rounded-lg">
            <option value="">Choose an Employee</option>
            <option value="gon">Gon</option>
            <option value="killua">Killua</option>
            <option value="kurapika">Kurapika</option>
        </select>

        <div id="gon" class="info-section">
            <h2 class="text-xl font-semibold mb-2">Gon</h2>
            <div class="grid grid-cols-2 gap-4">
                <div><strong>Full Name:</strong> Gon</div>
                <div><strong>Birthdate:</strong> May 5, 2003</div>
                <div><strong>Email:</strong> gon@example.com</div>
                <div><strong>Contact Number:</strong> +123456789</div>
                <div><strong>Address:</strong> Caloocan City</div>
                <div><strong>Job Title:</strong> Loan Officer</div>
                <div><strong>Department:</strong> Finance</div>
                <div><strong>Employee ID:</strong> 001</div>
                <div><strong>Date Hired:</strong> January 1, 2015</div>
                <div><strong>Employment Type:</strong> Full-Time</div>
            </div>
        </div>

        <div id="killua" class="info-section">
            <h2 class="text-xl font-semibold mb-2">Killua</h2>
            <div class="grid grid-cols-2 gap-4">
                <div><strong>Full Name:</strong> Killua</div>
                <div><strong>Birthdate:</strong> July 7, 2003</div>
                <div><strong>Email:</strong> killua@example.com</div>
                <div><strong>Contact Number:</strong> +987654321</div>
                <div><strong>Address:</strong> Quezon City</div>
                <div><strong>Job Title:</strong> Credit Analyst</div>
                <div><strong>Department:</strong> Credit Department</div>
                <div><strong>Employee ID:</strong> 002</div>
                <div><strong>Date Hired:</strong> May 10, 2015</div>
                <div><strong>Employment Type:</strong> Full-Time</div>
            </div>
        </div>

        <div id="kurapika" class="info-section">
            <h2 class="text-xl font-semibold mb-2">Kurapika</h2>
            <div class="grid grid-cols-2 gap-4">
                <div><strong>Full Name:</strong> Kurapika</div>
                <div><strong>Birthdate:</strong> April 4, 2000</div>
                <div><strong>Email:</strong> kurapika@example.com</div>
                <div><strong>Contact Number:</strong> +1122334455</div>
                <div><strong>Address:</strong> Caloocan City</div>
                <div><strong>Job Title:</strong> Branch Manager</div>
                <div><strong>Department:</strong> Management</div>
                <div><strong>Employee ID:</strong> 003</div>
                <div><strong>Date Hired:</strong> March 20, 2012</div>
                <div><strong>Employment Type:</strong> Full-Time</div>
            </div>
        </div>
    </div>

    <script>
        const selectElement = document.getElementById('employeeSelect');
        const sections = document.querySelectorAll('.info-section');
        
        selectElement.addEventListener('change', (event) => {
            sections.forEach(section => section.style.display = 'none');
            const selectedValue = event.target.value;
            if (selectedValue) {
                document.getElementById(selectedValue).style.display = 'block';
            }
        });
    </script>


  <style>
    * {
      box-sizing: border-box;
    }
    header {
      background-color: #272c47;
      color: white;
      padding: 20px;
      text-align: center;
      margin-top: 15px;
    }

    .container02 {
      max-width: 1200px;
      margin: 30px auto;
      padding: 20px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    h2 {
      color: #272c47;
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
      padding: 20px;
      background-color: #e8f0fe;
      border-left: 5px solid #272c47;
      border-radius: 8px;
    }

    .card h3 {
      margin: 0 0 10px;
      font-size: 18px;
      color: #1a1a1a;
    }

    .card p {
      font-size: 24px;
      font-weight: bold;
      color: #272c47;
    }

    .report-table {
      margin-top: 30px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th, td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #272c47;
      color: white;
    }

    .chart-placeholder {
      margin-top: 40px;
      height: 300px;
      background-color: #f0f4f8;
      border: 2px dashed #ccc;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #999;
      font-style: italic;
    }
  </style>

  <header>
    <h1>Reports and Analytics</h1>
  </header>

  <div class="container02">
    <h2>Dashboard Overview</h2>
    
    <div class="summary-cards">
      <div class="card">
        <h3>Total Employees</h3>
        <p>5</p>
      </div>
      <div class="card">
        <h3>Attendance Rate</h3>
        <p>90%</p>
      </div>
      <div class="card">
        <h3>Training Completion</h3>
        <p>In Progress</p>
      </div>
      <div class="card">
        <h3>Avg. Performance Rating</h3>
        <p>80%</p>
      </div>
    </div>

    <h2>Report Logs</h2>
    <div class="report-table">
      <table>
        <thead>
          <tr>
            <th>Report Name</th>
            <th>Generated On</th>
            <th>Type</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Monthly Attendance Summary</td>
            <td>In Progress</td>
            <td>PDF</td>
            <td>In Progress</td>
          </tr>
          <tr>
            <td>Q1 Performance Analysis</td>
            <td>In Progress</td>
            <td>Excel</td>
            <td>In Progress</td>
          </tr>
          <tr>
            <td>Training Feedback Report</td>
            <td>In Progress</td>
            <td>PDF</td>
            <td>In Progress</td>
          </tr>
        </tbody>
      </table>
    </div>

    <h2>Analytics Charts</h2>
    <div class="chart-placeholder">
      [Insert Chart or Graph Here]
    </div>

  </div>




</body>
</html>