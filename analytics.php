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
        <option value="n/a">Select metric</option>
        <option value="hist_dish">Group total orders by dish</option>
        <option value="date">Total orders by date</option>
    </select>

    <div class="graph">
        <h1>Total orders by dishID</h1>
        <canvas id="myChart"></canvas>
    </div>
<script src="analyticsJS.js"></script>
</body>
</html>