<?php
require __DIR__ . '/../autoloader.php';

$config = require __DIR__ . '/../config/config.php';

?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $config['callsign']; ?></title>
    <link rel="stylesheet" type="text/css" href="/css/arwt.css">
    <link rel="stylesheet" type="text/css" href="/css/custom.css">
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
# Custom Colors  

You can customize the colors and the overall font of ARWT by editing the /css/custom.css file. This file is loaded after the primary structure CSS, so feel free to add your own customizations here.

```
body {
    font-family: Arial, sans-serif;
    background-color: #F8F8F8;
    color: #1A1A1A;
}

.sidebar {
    background-color: #EFEFEF;
}

.sidebar a {
    text-decoration: none;
    color: #1A1A1A;
}

.sidebar a:hover {
    background-color: #DDD;
}

.footer-text {
    color: #666;
}
```



</div>
    <!-- Rendered Content. Do not edit or remove. -->
    <div id="renderedContent" class="content">
    </div>
    <script src="/js/showdown.js"></script>
</body>
</html>
