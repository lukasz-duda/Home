<?php
include '../../../Shared/Views/View.php';

$catId = intval($_REQUEST['CatId']);

$cat = get('SELECT name FROM cats WHERE id = ?', [$catId]);

if ($cat === false) {
    showFinalWarning('Nie znaleziono wybranego kota.');
}

$catName = $cat['name'];

$graphEnd = $_REQUEST['End'] ?? showDate(strtotime(today() . ' + 1 day'));
$graphStart = showDate(strtotime($graphEnd . ' - 1 week'));

$previous = date('Y-m-d', strtotime($graphEnd . ' - 1 week'));
$next = date('Y-m-d', strtotime($graphEnd . ' + 1 week'));

$results = getAll('select date(p.timestamp) as date,
count(p.timestamp) as count
from poop p
where p.cat_id = ?
and p.timestamp >= ?
and p.timestamp < ?
group by date(p.timestamp)', [$catId, $graphStart, $graphEnd]);

$days = [];
$labels = [];
$data = [];

$dayOfWeekLabels = ['pon.', 'wt.', 'śr.', 'czw.', 'pt.', 'sob.', 'niedz.'];

function findCount($list, $date)
{
    foreach ($list as $item) {
        if ($item['date'] == $date) {
            return $item['count'];
        }
    }
    return 0;
}

for ($i = 0; $i < 7; $i++) {
    $day = showDate(strtotime($graphStart . ' + ' . $i . ' days'));
    array_push($days, $day);

    $dayOfWeekIndex = date('N', strtotime($day)) - 1;
    $dayOfWeekLabel = $dayOfWeekLabels[$dayOfWeekIndex];
    array_push($labels, $dayOfWeekLabel);

    array_push($data, findCount($results, $day));
}
?>

<h1><?= $catName ?> - kupa</h1>

<canvas id="Chart" height="200"></canvas>

<script>
    const chartContainer = document.getElementById('Chart').getContext('2d');

    const chartColors = {
        brown: 'rgb(111, 78, 55)'
    };

    const color = Chart.helpers.color;

    const chart = new Chart(chartContainer, {
        type: 'line',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                label: 'Kupa',
                backgroundColor: color(chartColors.brown).alpha(0.5).rgbString(),
                borderColor: chartColors.brown,
                data: <?= json_encode($data) ?>,
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }]
            }
        }
    });
</script>

<div class="btn-group">
    <a class="btn btn-primary" href="pee.php?CatId=<?= $catId ?>&End=<?= $previous ?>">Tydzień wcześniej</a>
    <a class="btn btn-primary" href="pee.php?CatId=<?= $catId ?>&End=<?= $next ?>">Tydzień później</a>
</div>

<?php
include '../../../Shared/Views/Footer.php';