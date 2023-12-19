<?php
require __DIR__ . '/../autoloader.php';

$config = require __DIR__ . '/../config/config.php';

?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $config['callsign']; ?></title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/showdown@2.1.0/dist/showdown.min.js"></script>
</head>
<body>
    <!-- Sidebar -->
    <?php include __DIR__ . '/../common/sidebar.php'; ?>
    
<!-- 
Put your content in the markdownContent div. The Showdown library will convert the markdown to HTML.
If you need assistance with markdown, see https://stackedit.io/ 
-->
<div id="markdownContent" style="display: none;">
# Page Title
Markdown formatted content should be here.
</div>
    <!-- Rendered Content. Do not edit or remove. -->
    <div id="renderedContent" class="content">
    </div>
    <script src="/../common/showdown.js"></script>
</body>
</html>
