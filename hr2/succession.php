<?php
include ("connections.php");

// INSERT
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $role_id = $_POST['role_id'];
    $role_name = $_POST['role_name'];
    $candidate_name = $_POST['candidate_name'];
    $skills = $_POST['skills'];
    $status = $_POST['status'];

    $sql = "INSERT INTO succession_plans (role_id, role_name, candidate_name, skills, status)
            VALUES ('$role_id', '$role_name', '$candidate_name', '$skills', '$status')";
    $conn->query($sql);
}

// DELETE
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM succession_plans WHERE id=$id");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Succession Management</title>
    <style>
        body {
            background-color: #001f7f;
            color: white;
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
        }
        input, select, textarea, button {
            margin: 5px;
            padding: 8px;
            border-radius: 5px;
        }
        table {
            margin: 20px auto;
            width: 90%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            border: 1px solid white;
        }
        th {
            background-color: #003399;
        }
        tr:nth-child(even) {
            background-color: #002266;
        }
        .delete-btn {
            background: red;
            color: white;
            padding: 5px;
            border: none;
            border-radius: 5px;
        }
        .edit-btn {
            background: orange;
            color: white;
            padding: 5px;
            border: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<h2>Succession Management</h2>

<form method="POST">
    <input type="text" name="role_id" placeholder="Unique Role ID" required>
    <input type="text" name="role_name" placeholder="Role Name" required>
    <input type="text" name="candidate_name" placeholder="Candidate Name" required>
    <textarea name="skills" placeholder="Relevant skills, competencies..." required></textarea>
    <select name="status" required>
        <option value="">Select status</option>
        <option value="Ready Now">Ready Now</option>
        <option value="Ready in 1-2 years">Ready in 1-2 years</option>
        <option value="Ready in 3+ years">Ready in 3+ years</option>
    </select>
    <button type="submit" name="add">Add Plan</button>
</form>

<table>
    <tr>
        <th>Role ID</th>
        <th>Role Name</th>
        <th>Candidate Name</th>
        <th>Skills</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    <?php
    $result = $conn->query("SELECT * FROM succession_plans");
    while ($row = $result->fetch_assoc()):
    ?>
    <tr>
        <td><?= $row['role_id'] ?></td>
        <td><?= $row['role_name'] ?></td>
        <td><?= $row['candidate_name'] ?></td>
        <td><?= $row['skills'] ?></td>
        <td><?= $row['status'] ?></td>
        <td>
            <a href="edit.php?id=<?= $row['id'] ?>" class="edit-btn">Edit</a>
            <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure?');" class="delete-btn">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
