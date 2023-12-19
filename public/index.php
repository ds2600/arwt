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
# Hello ARWT!
### What is ARWT?
ARWT (Amateur Radio Website Template) is a basic web site template to help amateur radio operators make their sites a little prettier.  

### Why ARWT?
While we all enjoy the simplicity of plain HTML, it's nice to have a little style.  ARWT is a simple template that you can use to spruce up your amateur radio web presence.

### How do I use ARWT?
If you're reading this in a web browser and it looks all pretty, that means you've gotten ARWT up and running.  You'll want to make sure your call sign is being displayed in the upper left corner - if it's not, update your config file.

If that looks good, to start all you have to do is use markdown to edit the content in this &lt;div&gt; right here, and you're good to go.  

If you need help with markdown, you can check out [this handy guide](https://www.markdownguide.org/basic-syntax/) and play around with it on [Stackedit](https://stackedit.io).

### How do I add additional content using markdown?
1. Copy the */public/md-page-template.php* file and rename it to whatever you want to call your page. Make sure it stays in the *public* folder.
2. Open the */config/menu.php* file and follow the existing formatting to add a new entry to the menu.  Make sure the *url* matches the name of the file you created in step 1.
3. Save everything and you're done!  

### How do I add additional content using pure HTML?
1. Copy the */public/html-page-template.php* file and rename it to whatever you want to call your page. Make sure it stays in the *public* folder.
2. Open the */config/menu.php* file and follow the existing formatting to add a new entry to the menu.  Make sure the *url* matches the name of the file you created in step 1.
3. Save everything and you're done!  

### How do I use the FCC ULS Search?
This is a little more complicated, but I will be updating with instructions soon.  

Since it's a relatively complex page, **it is recommended that you do not modify the *public/fcc-uls-search.php* file**.

### Anything else?
If you find any bugs or if there are specific features you'd like to see added, create a [Github](https://www.github.com/ds2600/arwt) issue. I'll likely eventually put a roadmap of features up, just for my own sanity.  

If you'd like to contribute, feel free to fork the repo and submit a pull request.  I'll review it and merge it in if it looks good.  I'm not a professional developer, so I'm sure there are plenty of things that could be done better.  I'm open to suggestions.  

The whole project is being release under the MIT License, basically you can do whatever you want with it with some minor caveats.  See the LICENSE file for more details. I'd appreciate you leaving the repo link in the footer, but it's not required.

</div>
    <!-- Rendered Content. Do not edit or remove. -->
    <div id="renderedContent" class="content">
    </div>
    <script src="/js/showdown.js"></script>
</body>
</html>
