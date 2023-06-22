<?php
include __DIR__ . '/../../../Configuration.php';
include __DIR__ . '/../../../Shared/Views/ViewUtils.php';

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
$start = $_REQUEST['Start'] ?? showDate(strtotime(showDate(time()) . ' - 1 month'));
$end = showDate(strtotime($start . ' + 1 month + 1 day'));

function query($name)
{
    global $catId, $start, $end;
    return getAll(readText($name . '.sql'), [$catId, $start, $end]);
}

$dailyDemandResult = query('daily_demand');
$poopResult = query('poop');
$peeResult = query('pee');
$medicineResults = query('medicine');
$observationResult = query('observation');

function hasMedicine($date)
{
    global $medicineResults;
    foreach ($medicineResults as $medicine) {
        if ($medicine['date'] == $date) {
            return true;
        }
    }
    return null;
}

function dayMedicine($date)
{
    global $medicineResults;
    $dayMedicine = '';
    foreach ($medicineResults as $medicine) {
        if ($medicine['date'] == $date) {
            $unit = $medicine['unit'] == 'kropla' ? 'krople' : $medicine['unit'];
            $dayMedicine = $dayMedicine . $medicine['name'] . ' ' . floatval($medicine['dose']) . ' ' . $unit . ', ';
        }
    }
    return rtrim($dayMedicine, ', ');
}

$days = [$start];

$dateDiff = strtotime($end) - strtotime($start);
$daysCount = round($dateDiff / 60 / 60 / 24) - 1;

for ($i = 0; $i < $daysCount; $i++) {
    $day = showDate(strtotime($days[$i] . ' + 1 day'));
    array_push($days, $day);
    if ($day == $end) {
        break;
    }
}

$labels = [];

$dailyDemandData = [];
$poopData = [];
$peeData = [];
$medicineData = [];
$observationData = [];

$dates = [];
$peeCounts = [];
$poopCounts = [];
$observations = [];
$medicines = [];

$dayOfWeekLNames = ['', 'Poniedziałek', 'Wtorek', 'Środa', 'Czwartek', 'Piątek', 'Sobota', 'Niedziela'];

foreach ($days as $day) {
    $dayOfWeek = date('N', strtotime($day));

    array_push($labels, $dayOfWeek == 1 ? $day : '');
    $dailyDemand = intval(findValue($dailyDemandResult, $day));
    array_push($dailyDemandData, $dailyDemand);
    array_push($dates, $dayOfWeekLNames[$dayOfWeek] . ' - ' . $day);

    array_push($medicineData, hasMedicine($day));
    array_push($medicines, dayMedicine($day));

    $poop = intval(findValue($poopResult, $day));
    $poopCount = intval(findValue($poopResult, $day, 'count'));
    array_push($poopData, $poop ? ($poopCount > 1 ? 15 : 10) : null);
    array_push($poopCounts, $poopCount);

    $pee = intval(findValue($peeResult, $day));
    $peeCount = intval(findValue($peeResult, $day, 'count'));
    array_push($peeCounts, $peeCount);
    array_push($peeData, $pee ? 25 + $peeCount * 5 : null);

    $observation = findValue($observationResult, $day, 'notes');
    array_push($observations, $observation);
    array_push($observationData, $observation ? 50 : null);
}
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        <?= $catName ?>
    </title>
    <link rel="shortcut icon" sizes="192x192" href="pets.png">
    <link rel="stylesheet" href="<?= baseUrl() ?>/src/Shared/Views/bootstrap.min.css">
    <link href="<?= baseUrl() ?>/src/Shared/Views/material-icons.css" rel="stylesheet">
    <link href="<?= baseUrl() ?>/src/Shared/Views/theme.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand navbar-light bg-light mb-3">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= baseUrl() ?>/src/CatFeeding/Views/cat.php?Id=<?= $catId ?>"><i
                            class="material-icons-outlined cat-<?= $catId ?>">pets</i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" title="Wstecz"
                        href="timeline.php?CatId=<?= $catId ?>&Start=<?= showDate(strtotime($start . ' -1 month')) ?>"><i
                            class="material-icons-outlined">arrow_back</i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" title="Naprzód"
                        href="timeline.php?CatId=<?= $catId ?>&Start=<?= showDate(strtotime($start . ' +1 month')) ?>"><i
                            class="material-icons-outlined">arrow_forward</i></a>
                </li>
                <li class="nav-item">
                    <form class="nav-link">
                        <div class="input-group">
                            <input name="CatId" type="hidden" value="<?= $catId ?>">
                            <input class="form-control" id="Search" type="date" name="Start" value="<?= $start ?>"
                                aria-describedby="SearchIcon">
                            <div class="input-group-append">
                                <button type="submit" class="input-group-text" id="SearchIcon"><i
                                        class="material-icons-outlined" style="font-size: inherit">search</i></button>
                            </div>
                        </div>
                    </form>
                </li>
            </ul>
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
        let observation = new Image();
        observation.src = 'observation.png';

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
        const observations = <?= json_encode($observations) ?>;
        const medicines = <?= json_encode($medicines) ?>;

        function wordWrap(str, maxWidth) {
            var newLineStr = "\n";
            done = false;
            res = '';
            while (str.length > maxWidth) {
                found = false;
                for (i = maxWidth - 1; i >= 0; i--) {
                    if (testWhite(str.charAt(i))) {
                        res = res + [str.slice(0, i), newLineStr].join('');
                        str = str.slice(i + 1);
                        found = true;
                        break;
                    }
                }
                if (!found) {
                    res += [str.slice(0, maxWidth), newLineStr].join('');
                    str = str.slice(maxWidth);
                }

            }

            return res + str;
        }

        function testWhite(x) {
            var white = new RegExp(/^\s$/);
            return white.test(x.charAt(0));
        }

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
                    label: 'Leki',
                    data: <?= json_encode($medicineData) ?>,
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
                }, {
                    type: 'scatter',
                    label: 'Obserwacje',
                    data: <?= json_encode($observationData) ?>,
                    borderColor: chartColors.blue,
                    backgroundColor: chartColors.blue,
                    pointRadius: 4,
                    pointHoverRadius: 7,
                    pointStyle: observation
                }]
            },

            options: {
                responsive: chartResponsive,
                tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        title: function (tooltipItems) {
                            return dates[tooltipItems[0].index];
                        },
                        label: function (tooltipItem) {
                            switch (tooltipItem.datasetIndex) {
                                case 0:
                                    return 'Zjadła ' + tooltipItem.value + '% zapotrzebowania';
                                case 1:
                                    let dayMedicines = medicines[tooltipItem.index];
                                    return dayMedicines.split(', ');
                                case 2:
                                    let poopCount = poopCounts[tooltipItem.index];
                                    let poopCountSuffix = (poopCount === 1) ? 'raz' : 'razy';
                                    return 'Kupa ' + poopCount + ' ' + poopCountSuffix;
                                case 3:
                                    let peeCount = peeCounts[tooltipItem.index];
                                    let peeCountSuffix = (peeCount === 1) ? 'raz' : 'razy';
                                    return 'Siku ' + peeCount + ' ' + peeCountSuffix;
                                default:
                                    return '';
                            }
                        },
                        footer: function (tooltipItems) {
                            let observation = observations[tooltipItems[0].index] || '';
                            return wordWrap(observation, 40);
                        }
                    }
                },
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
</body>

</html>