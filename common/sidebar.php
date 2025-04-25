<?php
$menu = require __DIR__ . '/../config/menu.php';
?>

<div class="sidebar">
    <!-- Navigation links -->
    <h1><?php echo htmlspecialchars($config['callsign']); ?></h1>
<?php if ($config['gmrs_callsign']): ?>
    <div class="subtitle"><?php echo htmlspecialchars($config['gmrs_callsign']); ?></div>
<?php endif; ?>
    <a href="index.php">Home</a>
<?php   
    foreach ($menu as $menuItem) {
        echo '<a href="' . htmlspecialchars($menuItem['url']) . '">' . htmlspecialchars($menuItem['text']) . '</a>';
    }
?>
    <?php if ($config['uls_search']): ?>
        <a href="license-search.php">License Search</a>
    <?php endif; ?>
    
    <!-- Footer -->
    <?php include __DIR__ . '/../common/footer.php'; ?>
</div>
