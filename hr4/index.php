<?php
    include __DIR__.'/session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TruLend</title>
    <link href="app.css"rel="stylesheet">
    <link rel="stylesheet" href="maincontent.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    
    <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">-->
</head>
<body class="bg-light relative">
    <?php 
        include __DIR__.'\sidebar.php'
    ?>
    <div id="main" class="visually-hidden">
        <?php 
            include __DIR__.'\header.php'
        ?>
        
        <!--Content Here -->
        
        <!--Feel free to remove the style="min-height:100vh"-->
        <div class="container-fluid" style="min-height: 100vh">
            <?php
            // Get the page parameter from the URL
            $page = $_GET['page'] ?? 'dashboard'; // Default to dashboard if no page is specified

            // Include the appropriate page content
            switch ($page) {
                case 'dashboard':
                    include __DIR__ . '/dashboard.php';
                    break;
                case 'payroll':
                    include __DIR__ . '/payroll.php';
                    break;
                case 'hr_analytics':
                    include __DIR__ . '/hr_analytics.php';
                    break;
                case 'core_human_capital':
                    include __DIR__ . '/core_human_capital.php';
                    break;
                case 'compensation_planning':
                    include __DIR__ . '/compensation_planning.php';
                    break;
                default:
                    include __DIR__ . '/dashboard.php'; // Or show an error page
            }
            ?>

        </div>

        <?php
            include __DIR__.'\footer.php';
        ?>
    </div>
    <script src="sidebar.js"></script>
</body>
</html>