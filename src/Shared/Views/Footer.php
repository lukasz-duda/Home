<?php
include_once __DIR__ . '/View.php';
?>
    <script src="<?= $baseUrl ?>/src/Shared/Views/popper.min.js"></script>
    <script src="<?= $baseUrl ?>/src/Shared/Views/bootstrap.min.js"></script>
    <script>
        document.title = $('h1').first().text();
    </script>
<?php

echo '</div>
</body>
</html>';