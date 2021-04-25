<?php
include '../../Shared/Views/View.php';
$companies = getAll('select c.id, c.name, c.visible from companies c', []);
?>
    <h1>Firmy</h1>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">Dodaj firmę</div>
                <div class="card-body">
                    <form method="post" action="../UseCases/AddCompany.php">
                        <div class="form-group">
                            <label class="form-label" for="Name">Nazwa</label>
                            <input class="form-control" id="Name" name="Name" required minlength="3">
                        </div>
                        <button class="btn btn-primary" type="submit">Dodaj</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card mb-3">
                <div class="card-header">Widoczność</div>
                <div class="list-group list-group-flush">
                    <?php
                    foreach ($companies as $company) {
                        ?>
                        <div class="list-group-item">
                            <div class="mb-3">
                                <?= $company['name'] ?>
                            </div>
                            <?php
                            if ($company['visible']) {
                                ?>
                                <form action="../UseCases/HideCompanyUseCase.php" method="post">
                                    <input type="hidden" name="Id" value="<?= $company['id'] ?>">
                                    <button type="submit" class="btn btn-outline-dark mb-3">
                                        Ukryj <?= $company['name'] ?></button>
                                </form>
                                <?php
                            } else {
                                ?>
                                <form action="../UseCases/ShowCompanyUseCase.php" method="post">
                                    <input type="hidden" name="Id" value="<?= $company['id'] ?>">
                                    <button type="submit" class="btn btn-outline-success mb-3">
                                        Pokaż <?= $company['name'] ?></button>
                                </form>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

<?php
include '../../Shared/Views/Footer.php';