<!-- footer.php -->
<?php
    require __DIR__ . '/version.php';
?>
</body>
</html>
<footer>
    <p class="footer-text">
    <?php 
        echo "&copy; " . date("Y") . " " . htmlspecialchars($config['callsign']) . " | <a href='https://github.com/ds2600/arwt' target='_blank'>ARWT</a> ". VERSION;
    ?>
    </p>
</footer>
