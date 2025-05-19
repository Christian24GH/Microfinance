<?php
    include __DIR__.'/session.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trulend</title>
    <link rel="icon" href="img/1.4.png"/>
    <link href="./resources/app.css"rel="stylesheet">
    <link href="./node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light relative">
    <?php 
        include __DIR__.'/components/sidebar.php'
    ?>
    <div id="main" class="visually-hidden">
        <?php 
            include __DIR__.'/components/header.php'
        ?>





        
        <?php
            include __DIR__.'/components/footer.php';
        ?>
    </div>
    <script src="./js/sidebar.js"></script>
    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>