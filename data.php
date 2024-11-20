<?php
include('database.php');

$mode = isset($_GET['mode']) ? $_GET['mode'] : 'default';

// Query the database
if ($mode === 'hist_dish') {
    $sql = "SELECT menu.name AS dishName, SUM(fulfilled_orders.quantity) AS total_quantity 
            FROM fulfilled_orders 
            JOIN menu ON fulfilled_orders.dishID = menu.dishID 
            GROUP BY menu.dishID";
} elseif ($mode === 'test') {
    $sql = "SELECT DATE(order_date) AS order_date, SUM(quantity) AS total_quantity FROM fulfilled_orders GROUP BY DATE(order_date)";
}
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    if ($mode === 'hist_dish') {
        $data[] = [
            'dishName' => $row['dishName'],
            'quantity' => $row['total_quantity']
        ];
    } elseif ($mode === 'test') {
        $data[] = [
            'orderDate' => $row['order_date'],
            'quantity' => $row['total_quantity']
        ];
    }
}

echo json_encode($data);
$conn->close();
?>
