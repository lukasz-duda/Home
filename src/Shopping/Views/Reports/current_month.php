<?php
include '../../../Shared/Views/View.php';

$startDate = date('Y-m', time()) . '-01';

$expenses = getAll('select c.name,
       sum(e.value) as sum
from expense_categories c
left outer join expenses e on e.category_id = c.id
where e.timestamp >= ?
group by c.name
order by sum(e.value) desc  ', [$startDate]);

$labels = "'" . join("', '", array_column($expenses, 'name')) . "'";
$values = join(',', array_column($expenses, 'sum'));
$total = array_sum(array_column($expenses, 'sum'));
?>

    <h1 hidden>Zakupy w bieżącym miesiącu</h1>
    <h4>Zakupy w bieżącym miesiącu</h4>
    <p>
        <?php
        echo 'Razem: ' . showMoney($total);
        ?>
    </p>

    <canvas id="Chart" width="400" height="400"></canvas>
    <script>
        var colors = {
            red: 'rgb(255, 99, 132)',
            orange: 'rgb(255, 159, 64)',
            yellow: 'rgb(255, 205, 86)',
            green: 'rgb(75, 192, 192)',
            blue: 'rgb(54, 162, 235)',
            purple: 'rgb(153, 102, 255)',
            grey: 'rgb(201, 203, 207)'
        };

        var color = Chart.helpers.color;
        var borderColors = [colors.red, colors.blue, colors.yellow, colors.green, colors.purple, colors.orange, colors.grey];
        var backgroundColors = borderColors.map(x => color(x).alpha(0.5).rgbString());

        var chartContainer = document.getElementById('Chart').getContext('2d');

        var chart = new Chart(chartContainer, {
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

<?php
include '../../../Shared/Views/Footer.php';