<?php
include('database.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['order'])) { 

        $orderData = json_decode($_POST['order'], true);
        if (is_array($orderData) && isset($orderData[0])) {
            $orderID = $orderData[0];
            //echo $orderID . '<br>';
            
            $query_quantity_oID = "SELECT * FROM pending_orders WHERE oID =" . $orderData[0];
            $result_quantity_oID = mysqli_query($conn, $query_quantity_oID);  
            $pendingOrders = [];
            while ($row = $result_quantity_oID->fetch_assoc()) {
                $pendingOrders[] = $row; // Save each row into the array
            }
            
            // Now process the saved array
            foreach ($pendingOrders as $row) {
                $insert_stmt = "INSERT INTO fulfilled_orders (oid, dishID, quantity) VALUES (". $row['oID'] . "," . $row['dishID'] . "," . $row['quantity'] . ")";
                mysqli_query($conn, $insert_stmt); 

                $query_quantity_oID = "DELETE FROM pending_orders WHERE oID =" . $orderData[0] . " and dishID =" . $row['dishID'];
                $result_quantity_oID = mysqli_query($conn, $query_quantity_oID);  
                //echo "order id : " . $row['oID'] . " dishID : " . $row['dishID'] . " quantity : " . $row['quantity'] . "<br>";
            }
        } else {
            echo "Invalid order data format.";
        }
    } else {
        echo "No 'order' data received.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="base.css">
    <link rel="stylesheet" href="index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <?php
            include('header.html');
    ?>
    <div id="navbar">
        <h1>Pending Orders</h1>
    </div>
    <div id="test"></div>
    <?php
        include('database.php');
        date_default_timezone_set('Australia/Melbourne');

        function timeAgo($datetime) {
            $time = strtotime($datetime);
            $currentTime = time();
            $timeDifference = $currentTime - $time;

            if ($timeDifference < 60) {
                return $timeDifference . " seconds ago";
            } elseif ($timeDifference < 3600) {
                return floor($timeDifference / 60) . " minutes ago";
            } elseif ($timeDifference < 86400) {
                return floor($timeDifference / 3600) . " hours ago";
            } else {
                return floor($timeDifference / 86400) . " days ago";
            }
        }
        


        $query_oID = "SELECT DISTINCT oID FROM pending_orders";
        $result_oID = mysqli_query($conn, $query_oID);

        while ($row_result_oID = $result_oID->fetch_assoc()){     
            $query_quantity_oID = "SELECT * FROM pending_orders WHERE oID =" . $row_result_oID['oID'];
            $result_quantity_oID = mysqli_query($conn, $query_quantity_oID);  
            echo '
            <div class="list-group orders">
                <a href="#" class="list-group-item list-group-item-action" data-oID="' . $row_result_oID['oID'] . '">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"> Order no: ' . $row_result_oID['oID'] . '</h5>
                    </div>
            ';

            while ($oID_row = $result_quantity_oID -> fetch_assoc()) {

                $query_dish_name = "SELECT * FROM menu WHERE dishID =" . $oID_row['dishID'];
                $result_dish_name = mysqli_query($conn, $query_dish_name);
                $dish_name = $result_dish_name->fetch_assoc();   
                if ($dish_name['name']){
                    echo '
                    <div class="dishset">                    
                        <p class="mb-0" style="font-style: italic;">' . $dish_name['name'] . ' </p>
                        <p class="mb-0" style="font-weight: bold;">  Quantity : ' . $oID_row['quantity'] . ' </p>
                    </div>
                    ';
                }

            }
            $query_order_date = "SELECT order_date FROM orders WHERE oID = " .  $row_result_oID['oID'];
            $result_order_date = mysqli_query($conn, $query_order_date);
            $date_row = $result_order_date->fetch_assoc();
            $formattedDate = timeAgo($date_row['order_date']);
           echo '
            <small class="text-body-secondary">' . $formattedDate . '</small>
            </a>
            </div>
            ';
        }
    ?>
    <script>
        var orders = document.getElementsByClassName('orders');
        console.log(orders);

        for (var i = 0; i < orders.length; i++) {
            orders[i].addEventListener("dblclick", function () {
                var orderNumber = this.querySelector('a').getAttribute('data-oID');
                console.log('Order number: ' + orderNumber); // Print order number in console
                
                const orderData = [];
                orderData.push(orderNumber);
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'storefront.php';
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'order';
                input.value = JSON.stringify(orderData);
                console.log(input.value);

                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
                this.remove();
            });
        }
    </script>
</body>
</html>