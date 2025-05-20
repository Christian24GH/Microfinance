<?php
    //include __DIR__.'/session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TEST APP</title>
    <link rel="icon" href="img/1.4.png"/>
    <link href="./resources/css/app.css"rel="stylesheet">
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
        <!--Feel free to remove the style="min-height:100vh"-->
        <div class="container-fluid" style="min-height: 100vh">
            <!--Dito mo ilipat yung UI mo-->
        </div>

        <?php
            include __DIR__.'/components/footer.php';
        ?>
    </div>
    <script src="js/sidebar.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>