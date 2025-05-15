<?php
    include __DIR__.'/components/session.php';
    include "components/config.php";
    //include "components/settings.php";

    /*$result = $conn->query("SELECT li.client_id, li.amount, li.month, li.terms, li.purpose,
                             u.id
                        FROM loan_info li
                        INNER JOIN users u ON li.client_id = u.client_id
                        WHERE u.role_id = 2");
    */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="./css/app.css"rel="stylesheet">
    <link href="./css/sidebar.css"rel="stylesheet">
    <link href="./resources/css/content.css"rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="./js/sidebar.js"></script>
</head>
<body>
    <?php 
        include __DIR__.'/components/sidebar copy.php'
    ?>
    <div id="main" class="ps-0 rounded-end visually-hidden">
        <?php 
            include __DIR__.'/components/header.php'
        ?>
        <div class="container-fluid px-0">
            <section class="home-section">
                <div class="dashboard-content">
                    <div class="info-section">
                        <h3>Users</h3>
                            <table class="users-table">
                                <thead>
                                    <tr>
                                        <th>Client ID</th>
                                        <th>Amount</th>
                                        <th>Month</th>
                                        <th>Terms</th>
                                        <th>Purpose</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
          
                                <?php
                                    while ($row = $result->fetch_assoc()) :
                                    ?>             
                                        <tr>
                                            <td><?= $row["client_id"] ?></td>
                                            <td><?= $row["amount"] ?></td>
                                            <td><?= $row["month"] ?></td>
                                            <td><?= $row["terms"] ?></td>
                                            <td><?= $row["purpose"] ?></td>
                                            <td>
                                            <a href="../testapp/approve_users.php?id=<?= $row['client_id'] ?>">Approve</a>
                                            <a href="../testapp/decline_users.php?id=<?= $row['client_id'] ?>">Decline</a>
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
    </div>
    
</body>
</html>