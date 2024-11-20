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
            padding-left: 50px;
            padding-right: 50px;
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
    <select id="mode">
        <option value="hist_dish">Group orders by dish</option>
        <option value="test">test</option>
    </select>

    <div class="graph">
        <h1>Total orders by dishID</h1>
        <canvas id="myChart"></canvas>
    </div>
    <script>
    document.getElementById('mode').addEventListener('change', function (){
    var selectedMode = this.value;
    console.log(selectedMode);
    // console.log(`data.php?filter=${selectedMode}`);

    // Fetch data from the PHP endpoint
    if (selectedMode == 'hist_dish') {
        fetch(`data.php?mode=${selectedMode}`)
        .then(response => response.json())
        .then(data => {
            // Process the data
            const labels = data.map(item => item.dishName);
            const values = data.map(item => item.quantity);
            // console.log(data);
            // console.log(values);
            // console.log(labels);

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
    } 
    if (selectedMode == 'test'){
        console.log('test');
    }


    })
</script>
</body>
</html>