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
        <div class="form-group">
            <label for="Search">Szukaj firmy</label>
            <div class="input-group mb-3">
                <input type="search" class="form-control" id="Search" data-bind="value: query, hasFocus: true"
                    aria-describedby="SearchIcon">
                <div class="input-group-append">
                    <span class="input-group-text" id="SearchIcon"><i class="material-icons-outlined"
                            style="font-size: inherit">search</i></span>
                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header">Widoczność</div>
            <div class="list-group list-group-flush" data-bind="foreach: matchingCompanies">
                <div class="list-group-item">
                    <div class="mb-3">
                        <span data-bind="text: name"></span>
                    </div>
                    <form action="../UseCases/HideCompanyUseCase.php" method="post" data-bind="visible: visible">
                        <input type="hidden" name="Id" data-bind="value: id">
                        <button type="submit" class="btn btn-outline-dark mb-3">
                            Ukryj
                            <span data-bind="text: name"></span>
                        </button>
                    </form>
                    <form action="../UseCases/ShowCompanyUseCase.php" method="post" data-bind="visible: !visible">
                        <input type="hidden" name="Id" data-bind="value: id">
                        <button type="submit" class="btn btn-outline-success mb-3">
                            Pokaż
                            <span data-bind="text: name"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function Company(src) {
        const me = this;
        me.id = src.id;
        me.name = src.name;
        me.visible = Boolean(parseInt(src.visible));
    }

    const companiesSource = JSON.parse('<?= json_encode($companies) ?>');
    const companies = companiesSource.map(src => new Company(src));

    function ViewModel() {
        const me = this;
        me.query = ko.observable('');
        me.matchingCompanies = ko.computed(() => {
            return companies.filter(company => company.name.toLowerCase().includes(me.query().toLowerCase()));
        });
    }

    const viewModel = new ViewModel();
    ko.applyBindings(viewModel);
</script>

<?php
include '../../Shared/Views/Footer.php';