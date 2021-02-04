<?php
include 'Configuration.php';

date_default_timezone_set('Europe/Warsaw');

$pdo = newPdo();

function newPdo()
{
    global $dsn, $user, $password;
    $pdo = new PDO($dsn, $user, $password);
    $pdo->query('SET NAMES utf8');
    $pdo->query('SET CHARACTER_SET utf8_unicode_ci');
    return $pdo;
}

function pdo()
{
    global $pdo;
    return $pdo;
}

function get($query, $params)
{
    $statement = pdo()->prepare($query);
    $statement->execute($params);
    return $statement->fetch();
}

function getAll($query, $params)
{
    $statement = pdo()->prepare($query);
    $statement->execute($params);
    return $statement->fetchAll();
}

function showWarning($text)
{
    printf('<div class="alert alert-warning" role="alert">%s</div>', $text);
    exit();
}

function isoDate($time)
{
    return date('Y-m-d', $time);
}

function readText($fileName)
{
    $file = fopen($fileName, "r");
    $text = fread($file, filesize($fileName));
    fclose($file);
    return $text;
}

function findValue($list, $date, $attributeName = 'value')
{
    foreach ($list as $item) {
        if ($item['date'] == $date) {
            return $item[$attributeName];
        }
    }
    return null;
}

$catId = $_REQUEST['CatId'] ? intval($_REQUEST['CatId']) : 1;
$cat = get('SELECT name FROM cats WHERE id = ?', [$catId]);

if ($cat === false) {
    showWarning('Nie znaleziono wybranego kota.');
}

$catName = $cat['name'];
$start = $_REQUEST['Start'] ? $_REQUEST['Start'] : isoDate(strtotime(isoDate(time()) . ' -1 month'));
$end = isoDate(strtotime($start . ' +1 month'));

function query($name)
{
    global $catId, $start, $end;
    return getAll(readText($name . '.sql'), [$catId, $start, $end]);
}

$dailyDemandResult = query('daily_demand');
$poopResult = query('poop');
$peeResult = query('pee');
$megaceResult = query('megace');

$days = [$start];

for ($i = 0; $i < 31; $i++) {
    $day = isoDate(strtotime($days[$i] . ' +1 day'));
    array_push($days, $day);
    if ($day == $end) {
        break;
    }
}

$labels = [];

$dailyDemandData = [];
$poopData = [];
$peeData = [];
$megaceData = [];

$dates = [];
$peeCounts = [];
$poopCounts = [];

$dayOfWeekLNames = ['', 'Poniedziałek', 'Wtorek', 'Środa', 'Czwartek', 'Piątek', 'Sobota', 'Niedziela'];

foreach ($days as $day) {
    $dayOfWeek = date('N', strtotime($day));

    array_push($labels, $dayOfWeek == 1 ? $day : '');
    $dailyDemand = intval(findValue($dailyDemandResult, $day));
    array_push($dailyDemandData, $dailyDemand);
    array_push($dates, $dayOfWeekLNames[$dayOfWeek] . ' - ' . $day);

    $poop = intval(findValue($poopResult, $day));
    array_push($poopData, $poop ? 10 : null);
    $poopCount = intval(findValue($poopResult, $day, 'count'));
    array_push($poopCounts, $poopCount);

    $pee = intval(findValue($peeResult, $day));
    array_push($peeData, $pee ? 20 : null);
    $peeCount = intval(findValue($peeResult, $day, 'count'));
    array_push($peeCounts, $peeCount);

    $megace = floatval(findValue($megaceResult, $day));
    array_push($megaceData, $megace ? $megace : null);
}

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $catName ?></title>
    <link rel="shortcut icon" sizes="192x192" href="pets.png">
    <link href="material-icons.css" rel="stylesheet">
    <link href="bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar {
            background-color: rgba(153, 102, 255, 0.2) !important;
        }

        .nav-link, .navbar-brand {
            color: rgb(153, 102, 255) !important;
            font-size: 24px;
        }

        .nav-link:hover, .nav-link.active {
            color: rgb(63, 49, 95) !important;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-sm navbar-light bg-light mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php?CatId=1"><i
                    class="material-icons-outlined">pets</i></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= $catId == 1 ? 'active' : '' ?>" href="index.php?CatId=1">Szyszka</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $catId == 2 ? 'active' : '' ?>" href="index.php?CatId=2">Mgiełka</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" title="Wstecz"
                       href="index.php?CatId=<?= $catId ?>&Start=<?= isoDate(strtotime($start . ' -1 month')) ?>"><i
                                class="material-icons-outlined">arrow_back</i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" title="Naprzód"
                       href="index.php?CatId=<?= $catId ?>&Start=<?= isoDate(strtotime($start . ' +1 month')) ?>"><i
                                class="material-icons-outlined">arrow_forward</i></a>
                </li>
            </ul>
            <form class="d-flex">
                <input name="CatId" type="hidden" value="<?= $catId ?>">
                <input class="form-control me-2" type="date" name="Start" value="<?= $start ?>">
                <button class="btn btn-outline-primary" type="submit">Pokaż</button>
            </form>
        </div>
    </div>
</nav>
<div class="container" id="ChartContainer">
    <canvas id="Chart"></canvas>
</div>
<script src="Chart.min.js"></script>
<script>
    let poop = new Image();
    poop.src = 'poop.png';
    let urine = new Image();
    urine.src = 'pee.png';
    let syringe = new Image();
    syringe.src = 'syringe.png';

    Chart.defaults.global.defaultFontSize = 16;

    const chartColors = {
        red: 'rgb(255, 99, 132)',
        orange: 'rgb(255, 159, 64)',
        yellow: 'rgb(255, 205, 86)',
        green: 'rgb(75, 192, 192)',
        blue: 'rgb(54, 162, 235)',
        purple: 'rgb(153, 102, 255)',
        grey: 'rgb(201, 203, 207)'
    };
    const backgroundColors = {
        red: 'rgb(255, 99, 132, 0.2)',
        orange: 'rgb(255, 159, 64, 0.2)',
        yellow: 'rgb(255, 205, 86, 0.2)',
        green: 'rgb(75, 192, 192, 0.2)',
        blue: 'rgb(54, 162, 235, 0.2)',
        purple: 'rgba(153,102,255,0.2)',
        grey: 'rgb(201, 203, 207, 0.2)'
    };

    const chartElement = document.getElementById('Chart');

    const chartResponsive = window.innerWidth > 1000;
    if (!chartResponsive) {
        chartElement.style.width = '1000px';
        chartElement.style.display = 'block';
        const chartContainerElement = document.getElementById('ChartContainer');
        chartContainerElement.style.overflowX = 'auto';
    }

    const dates = <?= json_encode($dates) ?>;
    const peeCounts = <?= json_encode($peeCounts) ?>;
    const poopCounts = <?= json_encode($poopCounts) ?>;

    var chartContainer = chartElement.getContext('2d');
    var chart = new Chart(chartContainer, {
            type: 'line',

            data: {
                labels: <?= json_encode($labels) ?>,
                datasets: [{
                    label: 'Zapotrzebowanie dzienne [%]',
                    data: <?= json_encode($dailyDemandData) ?>,
                    borderColor: chartColors.purple,
                    backgroundColor: backgroundColors.purple,
                    fill: true,
                    pointRadius: 4,
                    pointHoverRadius: 7
                }, {
                    type: 'scatter',
                    label: 'Megace [ml]',
                    data: <?= json_encode($megaceData) ?>,
                    borderColor: chartColors.green,
                    backgroundColor: chartColors.green,
                    pointRadius: 4,
                    pointHoverRadius: 7,
                    pointStyle: syringe
                }, {
                    type: 'scatter',
                    label: 'Kupa',
                    data: <?= json_encode($poopData) ?>,
                    borderColor: chartColors.orange,
                    backgroundColor: chartColors.orange,
                    pointRadius: 4,
                    pointHoverRadius: 7,
                    pointStyle: poop
                }, {
                    type: 'scatter',
                    label: 'Siku',
                    data: <?= json_encode($peeData) ?>,
                    borderColor: chartColors.yellow,
                    backgroundColor: chartColors.yellow,
                    pointRadius: 4,
                    pointHoverRadius: 7,
                    pointStyle: urine
                }]
            },

            options: {
                responsive: chartResponsive,
                tooltips: {
                    mode: 'index',
                    intersect: false, callbacks: {
                        title: function (tooltipItems) {
                            return dates[tooltipItems[0].index];
                        }
                        ,
                        label: function (tooltipItem) {
                            switch (tooltipItem.datasetIndex) {
                                case  0:
                                    return 'Zjadła ' + tooltipItem.value + '% zapotrzebowania';
                                case  1:
                                    return 'Megace ' + tooltipItem.value.toString().replace('.', ',') + ' ml';
                                case  2:
                                    let poopCount = poopCounts[tooltipItem.index];
                                    let poopCountSuffix = (poopCount === 1) ? 'raz' : 'razy';
                                    return 'Kupa ' + poopCount + ' ' + poopCountSuffix;
                                case  3:
                                    let peeCount = peeCounts[tooltipItem.index];
                                    let peeCountSuffix = (peeCount === 1) ? 'raz' : 'razy';
                                    return 'Siku ' + peeCount + ' ' + peeCountSuffix;
                                default:
                                    return '';
                            }
                        }
                    }
                }
                ,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            max: 120
                        }
                    }]

                }
            }
        })
    ;
</script>
<script src="bootstrap.bundle.min.js"></script>
</body>
</html>