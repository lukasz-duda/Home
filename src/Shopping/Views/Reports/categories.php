<?php
include '../../../Shared/Views/View.php';

$startDate = $_REQUEST['StartDate'];
$endDate = $_REQUEST['EndDate'];

$expenses = getAll('select c.name,
       sum(e.value) as sum
from expense_categories c
left outer join expenses e on e.category_id = c.id
where e.timestamp >= ? and e.timestamp <= ?
group by c.name
order by sum(e.value) desc  ', [$startDate, $endDate]);

$labels = "'" . join("', '", array_column($expenses, 'name')) . "'";
$values = join(',', array_column($expenses, 'sum'));
$total = array_sum(array_column($expenses, 'sum'));

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
        const colors = {
            red: 'rgb(255, 99, 132)',
            orange: 'rgb(255, 159, 64)',
            yellow: 'rgb(255, 205, 86)',
            green: 'rgb(75, 192, 192)',
            blue: 'rgb(54, 162, 235)',
            purple: 'rgb(153, 102, 255)',
            grey: 'rgb(201, 203, 207)'
        };

        const color = Chart.helpers.color;
        const borderColors = [colors.red, colors.blue, colors.yellow, colors.green, colors.purple, colors.orange, colors.grey];
        const backgroundColors = borderColors.map(x => color(x).alpha(0.5).rgbString());

        const chartContainer = document.getElementById('Chart').getContext('2d');

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
                maintainAspectRatio: false
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