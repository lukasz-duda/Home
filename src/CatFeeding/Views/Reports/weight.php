<?php
include '../../../Shared/Views/View.php';

$catId = intval($_REQUEST['CatId']);

$cat = get('SELECT name FROM cats WHERE id = ?', [$catId]);

if ($cat === false) {
    showFinalWarning('Nie znaleziono wybranego kota.');
}

$catName = $cat['name'];

$graphStart = $_REQUEST['Start'] ?? date('Y-m-01', strtotime(today() . ' - 1 year'));
$queryStart = date('Y-m-01', strtotime($graphStart . ' - 1 year'));

$previous = date('Y-m-01', strtotime($graphStart . ' - 1 year'));
$next = date('Y-m-01', strtotime($graphStart . ' + 1 year'));

$results = getAll("select date_format(w.date, '%Y-%m') as date,
    avg(w.weight) as weight
from weight w
where w.cat_id = ? and w.date > ?
group by year(w.date), month(w.date)", [$catId, $queryStart]);

$labels = [];
$data = [];

function findWeight($list, $date)
{
    foreach ($list as $item) {
        if ($item['date'] == $date) {
            return $item['weight'];
        }
    }
    return findLastBefore($list, $date);
}

function findLastBefore($list, $date) {
    for ($i = 0; $i < count($list); $i++) {
        $item = $list[$i];
        $itemDate = strtotime($item['date']);
        $selectedDate = strtotime($date);
        if($itemDate > $selectedDate && $i > 0) {
            return $list[$i-1]['weight'];
        }
    }
    
    return null;
}

for ($i = 0; $i <= 12; $i++) {
    $date = date('Y-m', strtotime($graphStart . ' + ' . $i . ' months'));
    array_push($labels, $date);
    $weight = findWeight($results, $date);
    array_push($data, $weight);
}

?>

<h1><?= $catName ?> - waga</h1>

<canvas id="Chart" height="200"></canvas>

<script>
    const chartContainer = document.getElementById('Chart').getContext('2d');

    const chartColors = {
        blue: 'rgb(54, 162, 235)'
    };

    const color = Chart.helpers.color;

    const chart = new Chart(chartContainer, {
        type: 'line',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                label: 'Waga',
                backgroundColor: color(chartColors.blue).alpha(0.5).rgbString(),
                borderColor: chartColors.blue,
                data: <?= json_encode($data) ?>,
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        stepSize: 0.5,
                        suggestedMin: 3.5,
                        suggestedMax: 5
                    }
                }]
            }
        }
    });
</script>

<div class="btn-group">
    <a class="btn btn-primary" href="weight.php?CatId=<?= $catId ?>&Start=<?= $previous ?>">Rok wcześniej</a>
    <a class="btn btn-primary" href="weight.php?CatId=<?= $catId ?>&Start=<?= $next ?>">Rok później</a>
</div>

<?php
include '../../../Shared/Views/Footer.php';