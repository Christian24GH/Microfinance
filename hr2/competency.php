<?php 
include("connections.php"); 
?>

<!DOCTYPE html>
<html>
<head>
    <title>Competency Management System</title>
    <style>
        body {
            background-color: darkblue;
            color: white;
            font-family: Arial;
        }
        .container {
            width: 400px;
            margin: 0 auto;
            margin-top: 30px;
            background-color: lightblue;
            padding: 20px;
            border-radius: 10px;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin: 5px 0 15px;
        }
        button {
            background-color: #4fc3f7;
            border: none;
            padding: 10px;
            cursor: pointer;
        }
        h3 {
            border-bottom: 1px solid #444;
            padding-bottom: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <h3>Add New Competency</h3>
    <form method="POST">
        <input type="text" name="competency_name" placeholder="Competency Name" required>
        <button type="submit" name="add_competency">Add Competency</button>
    </form>

    <h3>Add New Employee</h3>
    <form method="POST">
        <input type="text" name="employee_name" placeholder="Employee Name" required>
        <button type="submit" name="add_employee">Add Employee</button>
    </form>

    <h3>Assign Competency to Employee</h3>
    <form method="POST">
        <select name="employee_id" required>
            <option value="">Select an employee</option>
            <?php
            $result = $conn->query("SELECT id, name FROM employees");
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['id']}'>{$row['name']}</option>";
            }
            ?>
        </select>

        <select name="competency_id" required>
            <option value="">Select a competency</option>
            <?php
            $result = $conn->query("SELECT id, name FROM competencies");
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['id']}'>{$row['name']}</option>";
            }
            ?>
        </select>

        <input type="number" name="level" min="1" max="5" value="3" required placeholder="Competency Level (1-5)">
        <button type="submit" name="assign_competency">Assign Competency</button>
    </form>

    <h3>Employees and Their Competencies</h3>
    <?php
    $query = "SELECT e.name AS employee, c.name AS competency, ec.level
              FROM employee_competencies ec
              JOIN employees e ON ec.employee_id = e.id
              JOIN competencies c ON ec.competency_id = c.id
              ORDER BY e.name";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<p><strong>{$row['employee']}</strong> - {$row['competency']} (Level {$row['level']})</p>";
        }
    } else {
        echo "<p>No employees added yet.</p>";
    }
    ?>
</div>

<?php
// Handle form submissions
if (isset($_POST['add_employee'])) {
    $name = $_POST['employee_name'];
    $stmt = $conn->prepare("INSERT INTO employees (name) VALUES (?)");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->close();
    header("Location: competency.php");
    exit();
}

if (isset($_POST['add_competency'])) {
    $name = $_POST['competency_name'];
    $stmt = $conn->prepare("INSERT INTO competencies (name) VALUES (?)");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->close();
    header("Location: competency.php");
    exit();
}

if (isset($_POST['assign_competency'])) {
    $employee_id = $_POST['employee_id'];
    $competency_id = $_POST['competency_id'];
    $level = $_POST['level'];

    $stmt = $conn->prepare("INSERT INTO employee_competencies (employee_id, competency_id, level) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $employee_id, $competency_id, $level);
    $stmt->execute();
    $stmt->close();
    header("Location: competency.php");
    exit();
}
?>

</body>
</html>
