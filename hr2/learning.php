<?php
include("connections.php");

// Handle form submission
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = $_POST['course_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $instructor = $_POST['instructor'];

    $sql = "INSERT INTO courses (course_id, title, description, start_date, instructor)
            VALUES ('$course_id', '$title', '$description', '$start_date', '$instructor')";

    if ($conn->query($sql) === TRUE) {
        $message = "<p style='color: #00ff99;'>✅ Course added successfully!</p>";
    } else {
        $message = "<p style='color: #ff5050;'>❌ Error: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Learning Management</title>
    <style>
        body {
            background-color: #000080;
            font-family: Arial, sans-serif;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background-color: black;
            padding: 30px;
            border-radius: 12px;
            width: 350px;
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: none;
        }

        input[type="date"] {
            color: #333;
        }

        button {
            background: linear-gradient(90deg, #00ffcc, #0099ff);
            color: black;
            font-weight: bold;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        button:hover {
            background: linear-gradient(90deg, #00cc99, #0066cc);
        }

        .message {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Learning Management</h2>
        <form method="POST" action="">
            <input type="text" name="course_id" placeholder="Unique Course ID" required>
            <input type="text" name="title" placeholder="Course Title" required>
            <textarea name="description" placeholder="Brief Course Description" rows="3" required></textarea>
            <input type="date" name="start_date" required>
            <input type="text" name="instructor" placeholder="Instructor Name" required>
            <button type="submit">Add Course</button>
        </form>
        <div class="message"><?php echo $message; ?></div>
    </div>
</body>
</html>
