<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="process_order.css">
    <title>Document</title>
</head>
<body>
    <?php
        include('header.html');
    ?>
    <h1>Your order is on its way!</h1>
    <?php
    include('database.php');
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the JSON order data
        $orderData = json_decode($_POST['order'], true);

        // Begin transaction to ensure all operations happen together
        $conn->begin_transaction();

        // Insert a new order and get the generated oID
        $query = "INSERT INTO orders (order_date) VALUES (NOW())";
        if ($conn->query($query)) {
            $oID = $conn->insert_id;  // Get the oID (auto-incremented ID of the new order)
            
            // Process each item in the order
            foreach ($orderData as $item) {
                $dishName = htmlspecialchars($item['name']);
                $count = intval($item['count']);

                // Prepare a query to grab dishID of dishName
                $query = "SELECT dishID FROM menu WHERE NAME = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $dishName);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($dishID);
                $stmt->fetch();

                // Check if dishID is found
                if ($dishID) {
                    // Insert into pending_orders with the generated oID
                    $query = "INSERT INTO pending_orders (oID, dishID, quantity) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("iii", $oID, $dishID, $count); // 'iii' for oID, dishID, and quantity
                    $stmt->execute();
                } else {
                    echo "<p>Dish '$dishName' not found in the menu.</p>";
                }
            }
            
            // Commit the transaction
            $conn->commit();
            echo "<p>Order placed successfully!</p>";
        } else {
            echo "<p>Error creating order.</p>";
            $conn->rollback();  // Rollback in case of error
        }
    } else {
        echo "No order data received.";
    }
    
    mysqli_close($conn);
?>

</body>
</html>