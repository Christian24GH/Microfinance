<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Performance Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #5cbc9c;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }
        .card h4 {
            margin-bottom: 15px;
            font-size: 1.5rem;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .category-label {
            font-weight: bold;
            margin-top: 10px;
        }
        table thead th {
            background-color: #007bff;
            color: #ffffff;
            text-align: center;
            font-weight: 600;
            font-size: 13px;
        }
        table tbody td {
            text-align: center;
            vertical-align: middle;
            font-size: 13px;
        }
        table tbody tr:nth-child(even) {
            background-color: #f3f3f3;
        }
        table tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }
        table {
            border-radius: 10px;
            overflow: hidden;
        }
        table th, table td {
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Employee Performance Dashboard</h2>

        <!-- Individual Performance Metrics -->
        <div class="card mb-4">
            <div class="card-body">
                <h4>Individual Performance</h4>
                <form action="connect.php" method="post">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="employeeName" class="form-label">Employee Name</label>
                            <input type="text" name="employee_name" id="employeeName" class="form-control" placeholder="Enter Employee Name" required>
                        </div>
                        <div class="col-md-3">
                            <label for="jobTitle" class="form-label">Job Title</label>
                            <select name="job_title" id="jobTitle" class="form-select" required>
                                <option value="" disabled selected>Select Job Title</option>
                                <option value="Loan Officer">Loan Officer</option>
                                <option value="Credit Analyst">Credit Analyst</option>
                                <option value="Branch Manager">Branch Manager</option>
                                <option value="Field Officer">Field Officer</option>
                                <option value="Finance Officer">Finance Officer</option>
                                <option value="Collections Specialist">Collections Specialist</option>
                                <option value="Risk Analyst">Risk Analyst</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="performanceScore" class="form-label">Performance Score</label>
                            <input type="number" name="performance_score" id="performanceScore" class="form-control" placeholder="Enter Score (0-100)" required>
                        </div>
                        <div class="col-md-3">
                            <label for="reviewDate" class="form-label">Review Date</label>
                            <input type="date" name="review_date" id="reviewDate" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label class="category-label">Performance Categories</label>
                        <div class="row">
                            <div class="col-md-3">
                                <label>Quality of Work</label>
                                <select name="quality_of_work" id="qualityOfWork" class="form-select" required>
                                    <option value="" disabled selected>Rating</option>
                                    <option value="Exceeds Expectation">Exceeds Expectation</option>
                                    <option value="Meets Expectation">Meets Expectation</option>
                                    <option value="Needs Improvement">Needs Improvement</option>
                                    <option value="Unacceptable">Unacceptable</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Attendance</label>
                                <select name="attendance" id="attendance" class="form-select" required>
                                    <option value="" disabled selected>Rating</option>
                                    <option value="Exceeds Expectation">Exceeds Expectation</option>
                                    <option value="Meets Expectation">Meets Expectation</option>
                                    <option value="Needs Improvement">Needs Improvement</option>
                                    <option value="Unacceptable">Unacceptable</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Reliability/Dependability</label>
                                <select name="reliability" id="reliability" class="form-select" required>
                                    <option value="" disabled selected>Rating</option>
                                    <option value="Exceeds Expectation">Exceeds Expectation</option>
                                    <option value="Meets Expectation">Meets Expectation</option>
                                    <option value="Needs Improvement">Needs Improvement</option>
                                    <option value="Unacceptable">Unacceptable</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Decision Making</label>
                                <select name="decision_making" id="decisionMaking" class="form-select" required>
                                    <option value="" disabled selected>Rating</option>
                                    <option value="Exceeds Expectation">Exceeds Expectation</option>
                                    <option value="Meets Expectation">Meets Expectation</option>
                                    <option value="Needs Improvement">Needs Improvement</option>
                                    <option value="Unacceptable">Unacceptable</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3 w-100">Add Performance</button>
                </form>
            </div>
        </div>

        <!-- Performance Table -->
        <table class="table table-striped mb-4">
            <thead>
                <tr>
                    <th>Employee Name</th>
                    <th>Job Title</th>
                    <th>Performance Score</th>
                    <th>Review Date</th>
                    <th>Quality of Work</th>
                    <th>Attendance</th>
                    <th>Reliability/Dependability</th>
                    <th>Decision Making</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="performanceTableBody">
                <!-- Performance entries will appear here -->
            </tbody>
        </table>
    </div>
</body>
</html>
