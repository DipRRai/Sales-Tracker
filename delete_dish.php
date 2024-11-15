<?php
session_start();
include('database.php');

if (isset($_POST['dishName'])) {
    $query = "DELETE FROM menu WHERE uID = ? AND name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $_SESSION['uid'], $_POST['dishName']);
    
    if ($stmt->execute()) {
        echo $_POST['dishName']; // Send back the deleted dish name
    } else {
        echo "Error deleting dish";
    }
}
?>