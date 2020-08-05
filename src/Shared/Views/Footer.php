<?php
include_once __DIR__ . '/View.php';
?>
    <script src="<?= $baseUrl ?>/src/Shared/Views/popper.min.js"></script>
    <script src="<?= $baseUrl ?>/src/Shared/Views/bootstrap.min.js"></script>
    <script>
        document.title = $('h1').first().text() || 'Dom';
    </script>

    <div class="btn-group">
        <a class="btn btn-light material-icons-outlined" href="<?= $_SERVER['HTTP_REFERER'] ?>">arrow_back</a>
    </div>
<?php

echo '</div>
</body>
</html>';