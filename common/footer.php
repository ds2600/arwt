<!-- footer.php -->
<?php
    require __DIR__ . '/version.php';
?>
<footer>
    <p class="footer-text">
    <?php 
        echo "&copy; " . date("Y") . " " . htmlspecialchars($config['callsign']) . " | <a href='https://github.com/your-username/your-repository' target='_blank'>ARWT</a> ". VERSION;
    ?>
    </p>
</footer>
