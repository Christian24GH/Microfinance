    <?php
    include("connections.php"); // expects $conn as mysqli connection

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Handle form submissions

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';

        if ($action === 'add' || $action === 'edit') {
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $date = $_POST['date'] ?? '';

            if ($title && $date) {
                if ($action === 'add') {
                    $stmt = $conn->prepare("INSERT INTO trainings (title, description, date) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $title, $description, $date);
                    $stmt->execute();
                    $stmt->close();
                } elseif ($action === 'edit') {
                    $id = $_POST['id'] ?? '';
                    $stmt = $conn->prepare("UPDATE trainings SET title = ?, description = ?, date = ? WHERE id = ?");
                    $stmt->bind_param("ssss", $title, $description, $date, $id);
                    $stmt->execute();
                    $stmt->close();
                }
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit;
            }
        } elseif ($action === 'delete') {
            $id = $_POST['id'] ?? '';
            $stmt = $conn->prepare("DELETE FROM trainings WHERE id = ?");
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $stmt->close();
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        }
    }

    $editTraining = null;
    if (isset($_GET['edit'])) {
        $editId = $_GET['edit'];
        $stmt = $conn->prepare("SELECT id, title, description, date FROM trainings WHERE id = ?");
        $stmt->bind_param("s", $editId);
        $stmt->execute();
        $result = $stmt->get_result();
        $editTraining = $result->fetch_assoc();
        $stmt->close();
    }

    // Fetch all trainings
    $trainings = [];
    $result = $conn->query("SELECT id, title, description, date FROM trainings ORDER BY date DESC");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $trainings[] = $row;
        }
        $result->free();
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Training Management System</title>
    <style>
        /* Reset and base styles */
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background:darkblue;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1rem;
        }
        h1 {
            color: #fff;
            margin-bottom: 1rem;
            text-align:center;
        }
        .container {
            background: #fff;
            max-width: 600px;
            width: 100%;
            border-radius: 8px;
            box-shadow: 0 1px 8px rgba(0,0,0,0.15);
            padding: 1.5rem;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        label {
            font-weight: 600;
            margin-bottom: 0.3rem;
        }
        input[type="text"], input[type="date"], textarea {
            padding: 0.5rem;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 1rem;
            width: 100%;
            resize: vertical;
            font-family: inherit;
        }
        textarea {
            min-height: 80px;
        }
        button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 0.7rem 1.2rem;
            font-weight: bold;
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
            width: fit-content;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }
        th, td {
            padding: 0.8rem 0.5rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
            font-weight: 600;
        }
        td.actions {
            display: flex;
            gap: 0.5rem;
        }
        button.edit, button.delete {
            padding: 0.3rem 0.8rem;
            font-size: 0.9rem;
            border-radius: 4px;
        }
        button.edit {
            background-color: #28a745;
        }
        button.edit:hover {
            background-color: #1e7e34;
        }
        button.delete {
            background-color: #dc3545;
        }
        button.delete:hover {
            background-color: #a71d2a;
        }
        @media (max-width: 600px) {
            .container {
                padding: 1rem;
            }
            body {
                padding: 0.5rem;
            }
            th, td {
                font-size: 0.9rem;
            }
            button, input[type="text"], input[type="date"], textarea {
                font-size: 1rem;
            }
            button.edit, button.delete {
                font-size: 0.8rem;
                padding: 0.25rem 0.6rem;
            }
        }
    </style>
    </head>
    <body>
        <h1>Training Management System</h1>
        <div class="container">
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <input type="hidden" name="action" value="<?php echo $editTraining ? 'edit' : 'add'; ?>" />
                <?php if ($editTraining): ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($editTraining['id']); ?>" />
                <?php endif; ?>
                <label for="title">Training Title *</label>
                <input type="text" id="title" name="title" placeholder="Enter training title" required value="<?php echo htmlspecialchars($editTraining['title'] ?? ''); ?>" />
                
                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Enter training description"><?php echo htmlspecialchars($editTraining['description'] ?? ''); ?></textarea>
                
                <label for="date">Date *</label>
                <input type="date" id="date" name="date" required value="<?php echo htmlspecialchars($editTraining['date'] ?? ''); ?>" />
                
                <button type="submit"><?php echo $editTraining ? 'Update Training' : 'Add Training'; ?></button>
                <?php if ($editTraining): ?>
                <a href="<?php echo $_SERVER['PHP_SELF']; ?>" style="margin-left: 1rem; color:#555; font-weight:600; text-decoration:none; padding:0.5rem 1rem; border:1px solid #ccc; border-radius:5px;">Cancel</a>
                <?php endif; ?>
            </form>

            <?php if (count($trainings) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th style="width:120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($trainings as $t): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($t['title']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($t['description'])); ?></td>
                        <td><?php echo htmlspecialchars($t['date']); ?></td>
                        <td class="actions">
                            <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="margin:0;">
                                <input type="hidden" name="edit" value="<?php echo htmlspecialchars($t['id']); ?>" />
                                <button type="submit" class="edit" title="Edit">Edit</button>
                            </form>
                            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="margin:0;" onsubmit="return confirm('Are you sure you want to delete this training?');">
                                <input type="hidden" name="action" value="delete" />
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($t['id']); ?>" />
                                <button type="submit" class="delete" title="Delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p>No training sessions added yet.</p>
            <?php endif; ?>
        </div>
    </body>
    </html>


