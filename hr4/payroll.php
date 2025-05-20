<!DOCTYPE html>
<html lang="en">
<head>
    <title>Payroll Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container2 {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            margin: 30px auto;
            max-width: 1000px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        table {
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: #fff;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tbody tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body style="background-color: #5cbc9c !important;">
    <div class="container2">
        <h2 class="text-center">Payroll</h2>
<form id="payrollForm" class="row g-3" method="POST" action="save_payroll.php">

    <input type="hidden" id="payrollId" name="payroll_id">
    <div class="col-md-4">
        <label for="employeeIdInput" class="form-label">Employee ID</label>
        <input type="text" name="employee_id" id="employeeIdInput" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label for="employeeName" class="form-label">Employee Name</label>
        <input type="text" name="employee_name" id="employeeName" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label for="payPeriod" class="form-label">Pay Period</label>
        <input type="date" name="pay_period" id="payPeriod" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label for="grossPay" class="form-label">Gross Pay (₱)</label>
        <input type="number" step="0.01" name="gross_pay" id="grossPay" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label for="deductions" class="form-label">Deductions (₱)</label>
        <input type="number" step="0.01" name="deductions" id="deductions" class="form-control" required>
    </div>
    <div class="col-md-4 d-flex align-items-end">
        <button type="submit" id="submitButton" class="btn btn-primary w-100">Add Payroll</button>
    </div>
</form>
        <table id="payrollTable">
            <thead>
                <tr>
                    <th>Payroll ID</th>
                    <th>Employee ID</th>
                    <th>Employee Name</th>
                    <th>Pay Period</th>
                    <th>Gross Pay (₱)</th>
                    <th>Deductions (₱)</th>
                    <th>Net Pay (₱)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <script src="payroll.js"></script>


    <title>Timesheet Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .container3 {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
        h1 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }
        #timesheetTable {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        #timesheetTable th, #timesheetTable td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        #timesheetTable th {
            background-color: #007bff;
            color: #fff;
            font-weight: bold;
        }
        #timesheetTable tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .chart-container {
            margin-bottom: 30px;
        }
    </style>

    <div class="container3">
        <h1>Timesheet Dashboard</h1>
        <!-- Chart Section -->
        <div class="chart-container">
            <canvas id="timesheetChart"></canvas>
        </div>
        <!-- Combined Table Section -->
        <table id="timesheetTable">
            <thead>
                <tr>
                    <th>Employee Name</th>
                    <th>Date</th>
                    <th>Attendance Status</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data will be added dynamically -->
            </tbody>
        </table>
    </div>
    
    <script src="timesheet.js"></script>

    <title>Compliance and Legal Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <script defer src="compliance.js"></script>
    <style>
        body {
    background-color: #5cbc9c;
    font-family: Arial, sans-serif;
}

.container5 {
    max-width: 1000px;
    margin: auto;
    background-color: #ffffff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
}

h2 {
    color: #343a40;
    margin-bottom: 30px;
}

.card {
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
}

.card h3 {
    color: #495057;
    margin-bottom: 20px;
}

.table-striped th {
    background-color: #f1f3f5;
}

.table-striped td, .table-striped th {
    padding: 15px;
}

.list-group-item {
    font-weight: 500;
    background-color: #f8f9fa;
    border-radius: 5px;
    margin-bottom: 10px;
}

    </style>

    <div class="container5 my-5">
        <h2 class="text-center">Compliance and Legal Dashboard</h2>
        
        <!-- Compliance Dashboard -->
        <div class="card p-4 mb-4">
            <h3>Compliance Dashboard</h3>
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="complianceType" class="form-label">Compliance Type</label>
                    <select id="complianceType" class="form-select">
                        <option value="" disabled selected>Select Compliance Type</option>
                        <option value="Labor Law">Labor Law</option>
                        <option value="Health and Safety">Health and Safety</option>
                        <option value="Data Privacy">Data Privacy</option>
                        <option value="Tax Regulation">Tax Regulation</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="description" class="form-label">Description</label>
                    <input type="text" id="description" class="form-control" placeholder="Enter Description">
                </div>
                <div class="col-md-2">
                    <label for="dueDate" class="form-label">Due Date</label>
                    <input type="date" id="dueDate" class="form-control">
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" class="form-select">
                        <option value="Pending">Pending</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
            </div>
            <button class="btn btn-primary mt-3 w-100" onclick="addCompliance()">Add Compliance</button>
        </div>

        <table class="table table-striped mb-5">
            <thead>
                <tr>
                    <th>Compliance Type</th>
                    <th>Description</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="complianceTableBody">
                <!-- Compliance entries will appear here -->
            </tbody>
        </table>

        <!-- Legal Dashboard -->
        <div class="card p-4">
            <h3>Legal Dashboard</h3>
            <ul id="legalRules" class="list-group">
                <li class="list-group-item">All clients must undergo thorough background checks.</li>
                <li class="list-group-item">Regular audits are mandatory for all branches.</li>
                <li class="list-group-item">Data privacy laws must be strictly followed.</li>
                <li class="list-group-item">Clients must be educated about their financial rights.</li>
            </ul>
        </div>
    </div>

    <style>
        body {
            background-color: #5cbc9c;
        }
        .container-section {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            margin: 30px auto;
            max-width: 1000px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        h2, h1 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        table {
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: #fff;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tbody tr:hover {
            background-color: #f1f1f1;
        }
    </style>

   <div class="container-section">
    <h2>Benefits and Deductions Dashboard</h2>
    <form id="benefitsForm" class="row g-3">
        <div class="col-md-6">
           <label for="employeeId" class="form-label">Employee Id</label>
           <input type="text" id="employeeId" class="form-control" required placeholder="Enter employee Id">
        </div>
        <div class="col-md-6">
           <label for="EmployeeName" class="form-label">Employee Name</label>
           <input type="text" id="EmployeeName" class="form-control" required placeholder="Enter employee name">
        </div>
        
        <div class="col-md-6">
            <label for="benefitType" class="form-label">Benefit Type</label>
            <select id="benefitType" class="form-control" required>
                <option value="" disabled selected>Select Benefit Type</option>
                <option value="Health Insurance">Health Insurance</option>
                <option value="Retirement Fund">Retirement Fund</option>
                <option value="Life Insurance">Life Insurance</option>
                <option value="Paid Leave">Paid Leave</option>
            </select>
        </div>
        <div class="col-md-6">
            <label for="benefitAmount" class="form-label">Amount (₱)</label>
            <input type="number" step="0.01" id="benefitAmount" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label for="deductionType" class="form-label">Deduction Type</label>
            <select id="deductionType" class="form-control" required>
                <option value="" disabled selected>Select Deduction Type</option>
                <option value="Tax">Tax</option>
                <option value="Social Security">Social Security</option>
                <option value="Health Contributions">Health Contributions</option>
                <option value="Retirement Contribution">Retirement Contribution</option>
            </select>
        </div>
        <div class="col-md-6">
            <label for="deductionAmount" class="form-label">Amount (₱)</label>
            <input type="number" step="0.01" id="deductionAmount" class="form-control" required>
        </div>
        <div class="col-md-12">
            <button type="button" class="btn btn-primary w-100" onclick="addBenefitDeduction()">Add Entry</button>
        </div>
    </form>
    <table id="benefitsTable">
        <thead>
            <tr>
                <th>Employee Id</th>
                <th>Employee Name</th>
                <th>Benefit Type</th>
                <th>Amount (₱)</th>
                <th>Deduction Type</th>
                <th>Amount (₱)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    <script src="benefits&deduction.js"></script>
</div>





</body>
</html>




