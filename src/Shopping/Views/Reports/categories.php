<?php
include '../../../Shared/Views/View.php';

$startDate = $_REQUEST['StartDate'];
$endDate = $_REQUEST['EndDate'];

$expenses = getAll('select c.name,
       (
           select ifnull(sum(e.value), 0)
           from expenses e
           where e.category_id = c.id
             and e.timestamp >= ?
             and e.timestamp <= ?
       ) as sum,
       c.color
from expense_categories c
group by c.id, c.name, c.ordinal, c.color
order by c.ordinal', [$startDate, $endDate]);

$labels = "'" . join("', '", array_column($expenses, 'name')) . "'";
$values = join(',', array_column($expenses, 'sum'));
$total = array_sum(array_column($expenses, 'sum'));
$colors = "'" . join("', '", array_column($expenses, 'color')) . "'";

$previousMonthStartDate = date('Y-m-d', strtotime($startDate . " -1 month")) . 'T00:00:00';
$previousMonthEndDate = date('Y-m-t', strtotime($startDate . " -1 month")) . 'T23:59:59';
$nextMonthStartDate = date('Y-m-d', strtotime($startDate . " +1 month")) . 'T00:00:00';
$nextMonthEndDate = date('Y-m-t', strtotime($startDate . " +1 month")) . 'T23:59:59';
?>

<h1 hidden>Kategorie zakupów</h1>
<h4>Kategorie zakupów</h4>
<p>
    <?php
    echo 'Razem: ' . showMoney($total);
    echo '<br />od ' . showDate(strtotime($startDate)) . ' do ' . showDate(strtotime($endDate)) . '<br />';
    ?>
</p>

<canvas id="Chart" height="200"></canvas>

<script>
    const color = Chart.helpers.color;
    const borderColors = [<?= $colors ?>];
    const backgroundColors = borderColors.map(x => color(x).alpha(0.5).rgbString());

    const chartContainer = document.getElementById('Chart').getContext('2d');

    Chart.Tooltip.positioners.custom = function (elements, position) {
        if (!elements.length) {
            return false;
        }

        return {
            x: position.x,
            y: position.y
        }
    };

    const chart = new Chart(chartContainer, {
        type: 'bar',
        data: {
            labels: [<?= $labels ?>],
            datasets: [{
                label: 'Suma zł',
                data: [<?= $values ?>],
                backgroundColor: backgroundColors,
                borderColor: borderColors,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        min: 0,
                        max: 500,
                        stepSize: 100,
                    }
                }]
            },
            tooltips: {
                position: 'custom'
            }
        }
    });
</script>

<div class="btn-group">
    <a class="btn btn-primary"
        href="categories.php?StartDate=<?= $previousMonthStartDate ?>&EndDate=<?= $previousMonthEndDate ?>">Miesiąc
        wcześniej</a>
    <a class="btn btn-primary"
        href="categories.php?StartDate=<?= $nextMonthStartDate ?>&EndDate=<?= $nextMonthEndDate ?>">Miesiąc
        później</a>
</div>

<?php
include '../../../Shared/Views/Footer.php';