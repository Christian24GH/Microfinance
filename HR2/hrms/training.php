<?php
// Simple Training Management System using PHP and JSON file for storage.
    include __DIR__.'/components/session.php';
    $dataFile = 'trainings.json';

    // Load trainings from JSON file or initialize empty array
    if (file_exists($dataFile)) {
        $trainings = json_decode(file_get_contents($dataFile), true);
        if (!is_array($trainings)) {
            $trainings = [];
        }
    } else {
        $trainings = [];
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
                    $newTraining = [
                        'id' => uniqid(),
                        'title' => $title,
                        'description' => $description,
                        'date' => $date,
                    ];
                    $trainings[] = $newTraining;
                } elseif ($action === 'edit') {
                    $id = $_POST['id'] ?? '';
                    foreach ($trainings as &$t) {
                        if ($t['id'] === $id) {
                            $t['title'] = $title;
                            $t['description'] = $description;
                            $t['date'] = $date;
                            break;
                        }
                    }
                    unset($t);
                }
                file_put_contents($dataFile, json_encode($trainings, JSON_PRETTY_PRINT));
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit;
            }
        } elseif ($action === 'delete') {
            $id = $_POST['id'] ?? '';
            $trainings = array_filter($trainings, function ($t) use ($id) {
                return $t['id'] !== $id;
            });
            file_put_contents($dataFile, json_encode(array_values($trainings), JSON_PRETTY_PRINT));
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        }
    }

    $editTraining = null;
    if (isset($_GET['edit'])) {
        $editId = $_GET['edit'];
        foreach ($trainings as $t) {
            if ($t['id'] === $editId) {
                $editTraining = $t;
                break;
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Training Management System</title>
    <link rel="stylesheet" href="css/app.css"/>
    <link rel="stylesheet" href="css/sidebar.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        /* Reset and base styles */
        h1 {
            color: #007BFF;
            margin-bottom: 1rem;
            text-align:center;
        }
        .ced-form {
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
<body class="bg-light">
    <?php 
        include __DIR__.'/components/sidebar.php'
    ?>
    <div id="main" class="ps-0 rounded-end visually-hidden">
        <?php 
            include __DIR__.'/components/header.php'
        ?>
        <div class="container mt-2">
        <h1>Training Management System</h1>
        <div class="container gap-1">
            <form classs="ced-form"  method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <input type="hidden" class="mb-1" name="action" value="<?php echo $editTraining ? 'edit' : 'add'; ?>" />
                <?php if ($editTraining): ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($editTraining['id']); ?>" />
                <?php endif; ?>
                <label for="title">Training Title *</label>
                <input type="text" class="mb-1" id="title" name="title" placeholder="Enter training title" required value="<?php echo htmlspecialchars($editTraining['title'] ?? ''); ?>" />
                
                <label for="description">Description</label>
                <textarea id="description" name="description" class="mb-1" placeholder="Enter training description"><?php echo htmlspecialchars($editTraining['description'] ?? ''); ?></textarea>
                
                <label for="date">Date *</label>
                <input type="date" id="date" name="date" class="mb-2" required value="<?php echo htmlspecialchars($editTraining['date'] ?? ''); ?>" />
                
                <button type="submit" class="btn  btn-primary"><?php echo $editTraining ? 'Update Training' : 'Add Training'; ?></button>

                <?php if ($editTraining): ?>
                    <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-primary">Cancel</a>
                <?php endif; ?>
            </form>
                
            <div class="container-fluid mt-2 px-0">
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
                                    <button type="submit" class="edit btn" title="Edit">Edit</button>
                                </form>
                                <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="margin:0;" onsubmit="return confirm('Are you sure you want to delete this training?');">
                                    <input type="hidden" name="action" value="delete" />
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($t['id']); ?>" />
                                    <button type="submit" class="delete btn" title="Delete">Delete</button>
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
        </div>
    </div>
    </div>
    <script src="js/sidebar.js"></script>
</body>
</html>

