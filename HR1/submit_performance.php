<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ratings = $_POST['ratings'];
    $notes = $_POST['notes'];

    foreach ($ratings as $app_id => $rating) {
        $note = $notes[$app_id] ?? '';
        
        // Check if a record already exists
        $check = $conn->prepare("SELECT id FROM employee_performance WHERE application_id = ?");
        $check->bind_param("i", $app_id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            // Update existing
            $update = $conn->prepare("UPDATE employee_performance SET rating = ?, notes = ? WHERE application_id = ?");
            $update->bind_param("isi", $rating, $note, $app_id);
            $update->execute();
        } else {
            // Insert new
            $insert = $conn->prepare("INSERT INTO employee_performance (application_id, rating, notes) VALUES (?, ?, ?)");
            $insert->bind_param("iis", $app_id, $rating, $note);
            $insert->execute();
        }
    }

    header("Location: index.php?page=samplePerformance&saved=1");
    exit();
}
?>
