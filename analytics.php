<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="base.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Document</title>
    <style>
        .graph {
            padding-left: 250px;
            padding-right: 250px;
        }
    </style>
</head>
<body>
    <?php
        session_start();
        include('header.html');
        echo "
        <div id='navbar'>
            <h1>Welcome {$_SESSION['username']}<br>
        </div>";
    ?>
    <div class="graph">
        <h1>Total orders by dishID</h1>
        <canvas id="myChart"></canvas>
    </div>
    <script>
    // Fetch data from the PHP endpoint
    fetch('data.php')
        .then(response => response.json())
        .then(data => {
            // Process the data
            const labels = data.map(item => item.dishID);
            const values = data.map(item => item.quantity);
            console.log(data);
            console.log(values);
            console.log(labels);

            // Create the chart
            const ctx = document.getElementById('myChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Quantity',
                        data: values,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Error fetching data:', error));
</script>
</body>
</html>