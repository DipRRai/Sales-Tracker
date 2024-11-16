<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="process_order.css">
    <title>Document</title>
</head>
<body>
    <h1>Your order is on its way!</h1>
    <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get the JSON order data
            $orderData = json_decode($_POST['order'], true);

            // Process each item in the order
            foreach ($orderData as $item) {
                $dishName = htmlspecialchars($item['name']);
                $count = intval($item['count']);

                // Example: Insert the order into a database or process it
                echo "<p>Dish: $dishName, Quantity: $count</p>";

                // Insert into database logic (if needed)
                // $query = "INSERT INTO orders (dish_name, quantity) VALUES (?, ?)";
                // $stmt = $conn->prepare($query);
                // $stmt->bind_param("si", $dishName, $count);
                // $stmt->execute();
            }
        } else {
            echo "No order data received.";
        }
    ?>
</body>
</html>