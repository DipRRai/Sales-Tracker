<?php
    $db_server = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name = "storedb";

    // Attempt to establish a connection
    $conn = mysqli_connect($db_server, $db_user, $db_password, $db_name, 6969);

    // Check if the connection was successful
    // if (!$conn) {
    //     die("Connection failed: " . mysqli_connect_error());
    // } else {
    //     echo "Connected successfully!";
    // }
?>
