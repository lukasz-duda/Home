<?php
include '../../../Shared/Views/View.php';

$startDate = date('Y-m', time()) . '-01';

$expenses = getAll('select c.name,
       sum(e.value) as sum
from expense_categories c
left outer join expenses e on e.category_id = c.id
where e.timestamp >= ?
group by c.name
order by c.name', [$startDate]);

$labels = "'" . join("', '", array_column($expenses, 'name')) . "'";
$values = join(',', array_column($expenses, 'sum'));
$total = array_sum(array_column($expenses, 'sum'));
?>

    <h1 hidden>Zakupy w bieżącym miesiącu</h1>
    <h4>Zakupy w bieżącym miesiącu</h4>

    <canvas id="Chart" width="400" height="400"></canvas>
    <script>
        var chartContainer = document.getElementById('Chart').getContext('2d');
        var chart = new Chart(chartContainer, {
            type: 'bar',
            data: {
                labels: [<?= $labels ?>],
                datasets: [{
                    label: '<?= showMoney($total) ?>',
                    data: [<?= $values ?>],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>

<?php
include '../../../Shared/Views/Footer.php';