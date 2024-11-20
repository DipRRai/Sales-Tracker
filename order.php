<?php
    include('database.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="order.css">
    <link rel="stylesheet" href="orderCSS.css">
    <title>Document</title>
</head>
<body>
    <?php 
            include('header.html');
            $query = "SELECT * FROM menu WHERE uID = " . $_GET['menu'] ;
            $result = mysqli_query($conn, $query);  
            echo "<div id='menu'>";
            while ($row = $result->fetch_assoc()) {
                echo "<div class='menu-item'>
                        <h2>{$row['name']}</h2>
                        <div class='details'>
                            <h3>{$row['description']}</h3>
                            <div id='order_ui>
                                <h3 class='price'>$ {$row['price']}</h3>
                                <div class='ui_select'>
                                    <input type='button' class='increment' value='-'>
                                    <p>0</p>
                                    <input type='button' class='increment' value='+'>
                                </div>
                            </div>
                        </div>
                    </div>";
            }
            echo "</div>";
        ?>
        <button id="submitOrder">Submit Order</button>
        <script src="order.js"></script>
</body>
</html>