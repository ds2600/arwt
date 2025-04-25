<?php
require __DIR__ . '/../common/init.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $config['callsign']; ?></title>
    <link rel="stylesheet" type="text/css" href="/css/arwt.css">
    <link rel="stylesheet" type="text/css" href="/css/custom.css">
</head>
<body>
    <!-- Sidebar -->
    <?php include __DIR__ . '/../common/sidebar.php'; ?>
    
    <!-- HTML Content should go in the renderedContent div -->
    <div id="renderedContent" class="content">
    <h1>Page Title</h1>
    <img src="https://placehold.co/600x400" alt="placeholder image" style="display:block; margin-left:auto; margin-right:auto;">
    <p style="text-align:center; margin-top:2em;">Page content goes here.</p>
    </div>
</body>
</html>
