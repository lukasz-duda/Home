<?php
include '../../../Shared/Views/View.php';

get('SET SESSION SQL_BIG_SELECTS=1', []);
$expenses = getAll('select years.year, c.id, c.name, round(sum(round(ifnull(e.value, 0), 2))) as sum
from (
         select distinct year(ie.timestamp) as year
         from expenses ie
         order by year(ie.timestamp)
     ) years
         join expense_categories c
         left join expenses e on e.category_id = c.id and year(e.timestamp) = years.year
group by years.year, c.id, c.name', []);

$labels = "'" . join("', '", array_unique(array_column($expenses, 'year'))) . "'";
$categories = array_unique(array_column($expenses, 'name'));
?>

    <h1 hidden>Średnie miesięczne wydatki w roku według kategorii</h1>
    <h4>Średnie miesięczne wydatki w roku według kategorii</h4>

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

        const chartContainer = document.getElementById('Chart').getContext('2d');

        const chart = new Chart(chartContainer, {
            type: 'line',
            data: {
                labels: [<?= $labels ?>],
                datasets: [
                    <?php
                    foreach ($categories as $category) {
                        $data = '';

                        foreach ($expenses as $expense) {
                            if ($expense['name'] == $category) {
                                $year = $expense['year'];
                                $sum = $expense['sum'];
                                $data = $data . "{ x: $year, y: $sum },";
                            }
                        }

                        echo "{
                        label: '$category',
                        data: [$data],
                        fill: false
                        },";
                    }
                    ?>
                ]
            },
            options: {
                maintainAspectRatio: false
            }
        });
    </script>

<?php
include '../../../Shared/Views/Footer.php';