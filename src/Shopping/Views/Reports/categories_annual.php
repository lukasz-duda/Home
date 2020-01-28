<?php
include '../../../Shared/Views/View.php';

$expenses = getAll('select y.year,
       c.name,
       round(sum(round(e.value, 2))) as sum
from expenses e
         join (
    select distinct year(ie.timestamp) as year
    from expenses ie
    order by year(ie.timestamp)
) y on y.year = YEAR(e.timestamp)
         join expense_categories c on c.id = e.category_id
group by y.year, c.name  ', []);

?>

    <h1 hidden>Roczne wydatki wg kategorii</h1>
    <h4>Roczne wydatki wg kategorii</h4>

    <canvas id="Chart" height="200"></canvas>
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
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: '',
                    data: [],
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