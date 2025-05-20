<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$database = "microfinance";

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input data
    $employee_name = trim($_POST['employee_name']);
    $job_title = trim($_POST['job_title']);
    $performance_score = (int)$_POST['performance_score'];
    $review_date = $_POST['review_date'];
    $quality_of_work = trim($_POST['quality_of_work']);
    $attendance = trim($_POST['attendance']);
    $reliability = trim($_POST['reliability']);
    $decision_making = trim($_POST['decision_making']);

    // Ensure all fields are filled
    if (
        empty($employee_name) || 
        empty($job_title) || 
        empty($performance_score) || 
        empty($review_date) || 
        empty($quality_of_work) || 
        empty($attendance) || 
        empty($reliability) || 
        empty($decision_making)
    ) {
        echo "<script>
            alert('All fields are required!');
            window.history.back();
        </script>";
        exit;
    }

    // Prepare and bind the SQL statement
    $sql = "INSERT INTO performance (employee_name, job_title, performance_score, review_date, quality_of_work, attendance, reliability, decision_making) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisssss", $employee_name, $job_title, $performance_score, $review_date, $quality_of_work, $attendance, $reliability, $decision_making);

    // Execute and check for errors
    if ($stmt->execute()) {
        echo "<script>
            alert('Employee performance added successfully!');
            window.location.href = 'index.php';
        </script>";
    } else {
        echo "<script>
            alert('Error: " . $stmt->error . "');
            window.history.back();
        </script>";
    }

    $stmt->close();
}

// Close the connection
$conn->close();
?>
