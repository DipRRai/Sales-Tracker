<?php
header('Content-Type: application/json');
include('database.php');

// Query the database
$sql = "SELECT dishID, SUM(quantity) AS total_quantity FROM fulfilled_orders GROUP BY dishID";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'dishID' => $row['dishID'],
            'quantity' => $row['total_quantity']
        ];
    }
}

echo json_encode($data);
$conn->close();
?>
