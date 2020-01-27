<?php
include '../../../Shared/Views/View.php';

$startDate = date('Y-m', time()) . '-01';

$fuel = getAll("select y.year,
       round(sum(round(e.value, 2))) as sum
from car_expenses e
         join (
    select distinct year(ie.timestamp) as year
    from car_expenses ie
    order by year(ie.timestamp)
) y on y.year = YEAR(e.timestamp)
where e.name = 'Olej napędowy'
group by y.year", []);

$other = getAll("select y.year,
       round(sum(round(e.value, 2))) as sum
from car_expenses e
         join (
    select distinct year(ie.timestamp) as year
    from car_expenses ie
    order by year(ie.timestamp)
) y on y.year = YEAR(e.timestamp)
where e.name not in ('Olej napędowy', 'Samochód')
group by y.year", []);

$labels = "'" . join("', '", array_column($fuel, 'year')) . "'";
$fuelValues = join(',', array_column($fuel, 'sum'));
$otherValues = join(',', array_column($other, 'sum'));
?>

    <h1 hidden>Roczne wydatki na samochód</h1>
    <h4>Roczne wydatki na samochód</h4>

    <canvas id="Chart" width="400" height="400"></canvas>
    <script>
        var chartColors = {
            red: 'rgb(255, 99, 132)',
            orange: 'rgb(255, 159, 64)',
            yellow: 'rgb(255, 205, 86)',
            green: 'rgb(75, 192, 192)',
            blue: 'rgb(54, 162, 235)',
            purple: 'rgb(153, 102, 255)',
            grey: 'rgb(201, 203, 207)'
        };

        var color = Chart.helpers.color;
        var chartContainer = document.getElementById('Chart').getContext('2d');


        var options = {
            maintainAspectRatio: false,
            elements: {
                line: {
                    tension: 0.000001
                }
            }
        };

        var chart = new Chart(chartContainer, {
            type: 'line',
            data: {
                labels: [<?= $labels ?>],
                datasets: [{
                    label: 'Olej napędowy',
                    backgroundColor: color(chartColors.red).alpha(0.5).rgbString(),
                    borderColor: chartColors.red,
                    data: [<?= $fuelValues ?>],
                    fill: false
                }, {
                    label: 'Eksploatacja',
                    backgroundColor: color(chartColors.blue).alpha(0.5).rgbString(),
                    borderColor: chartColors.blue,
                    data: [<?= $otherValues ?>],
                    fill: false
                }]
            },
            options: options
        });
    </script>

<?php
include '../../../Shared/Views/Footer.php';