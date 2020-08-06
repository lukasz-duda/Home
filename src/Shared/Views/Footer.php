<?php
include_once __DIR__ . '/View.php';
?>
    <script src="<?= baseUrl() ?>/src/Shared/Views/popper.min.js"></script>
    <script src="<?= baseUrl() ?>/src/Shared/Views/bootstrap.min.js"></script>
    <script>
        document.title = $('h1').first().text() || 'Dom';
    </script>

    <div class="alert alert-warning" role="alert" style="display: <?= tooLong() ? 'block' : 'none' ?>">
        Przetwarzanie trwało <?= timeSpent() ?> s.
    </div>
<?php

echo '</div>
</body>
</html>';