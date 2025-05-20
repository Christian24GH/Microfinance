<?php
    //include __DIR__.'/session.php';
    session_start();
    include "components/config.php";
    include "components/settings.php";

    $result = $conn->query("SELECT ci.client_id, ci.first_name, ci.last_name, 
                             a.id, a.email, cs.c_status
                        FROM client_info ci
                        INNER JOIN accounts a ON ci.client_id = a.client_id
                        INNER JOIN client_status cs ON ci.client_id = cs.client_id
                        WHERE a.role_id = 2");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Microfinance</title>
    <link href="./resources/css/app.css"rel="stylesheet">
    <link href="./resources/css/content.css"rel="stylesheet">
    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">-->
</head>
<body class="bg-light relative">
    <?php 
        include __DIR__.'/components/sidebar.php'
    ?>
    <div id="main" class="visually-hidden">
        <?php 
            include __DIR__.'/components/header.php'
        ?>
        
        <!--Content Here -->
        <section class="home-section">
                    <div class="dashboard-content">
                        <div class="info-section">
                            <h3>Clients List</h3>
                                <table class="users-table">
                                    <thead>
                                        <tr>
                                            <th>Client ID</th>
                                            <th>Email</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        while ($row = $result->fetch_assoc()) :
                                        ?>             
                                            <tr>
                                                <td><?= $row["client_id"] ?></td>
                                                <td><?= $row["email"] ?></td>
                                                <td><?= $row["first_name"] ?></td>
                                                <td><?= $row["last_name"] ?></td>
                                                <td><?= $row["c_status"] ?></td>
                                                <td>
                                                <a href="../Core1/view_users.php?id=<?= $row['client_id'] ?>">View</a>
                                                <a href="../Core1/c_approve.php?id=<?= $row['client_id'] ?>">Approve</a>
                                                <a href="../Core1/c_decline.php?id=<?= $row['client_id'] ?>">Decline</a>
                                                </td>
                                            </tr><?php endwhile; ?>
                                        </div>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>

        </div>

        <?php
            include __DIR__.'/components/footer.php';
        ?>
    </div>
    <script src="js/sidebar.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>