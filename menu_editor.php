<?php
    session_start();
    include('header.html');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Menu Editor</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js" integrity="sha512-L0Shl7nXXzIlBSUUPpxrokqq4ojqgZFQczTYlGjzONGTDAcLremjwaWv5A+EDLnxhQzY5xUZPWLOLqYRkY0Cbw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="qrcode.js"></script>
    <link rel="stylesheet" href="base.css">
    <link rel="stylesheet" href="menu_edit.css">
    <style>
        .dish-entry { margin-bottom: 10px; }
    </style>
</head>
<body>
    <div id="navbar">
        <h1>Create Your Menu</h1>
    </div>
    <form action="menu_editor.php" method="post" id="menuForm">
        <div id="main-container">
            <div id="dish-container">
                <?php
                    include('database.php');
                    $query = "SELECT * FROM menu WHERE uID = '" . $_SESSION['uid'] . "'";
                    $result = mysqli_query($conn, $query);
                    // Check if query was successful
                    if ($result) {
                        // Loop through each row and echo the data
                        while ($row = $result->fetch_assoc()) {
                            // You can echo specific fields from the row
                            echo '
                            <div class="dish-entry">
                                <label>Dish Name: <input type="text" name="dish_name[]" value="' . $row['name'] . '" required></label><br>
                                <label>Price: <input type="number" name="price[]" step="0.01" value="' . $row['price'] . '" required></label><br>
                                <label>Description: <input type="text" name="description[]" value="' . $row['description'] . '" required></label><br>
                                <button type="button" class="deleteDish" data-name="' . $row['name'] . '">Delete</button><br><br>
                            </div>
                        ';
                        }
                    } else {
                        echo "Error: " . $mysqli->error;
                    }
                ?>
            </div>
            <div id="qr-container">
                <a id="qrLink" href="#"><div id="qrcode" style="width:100px; height:100px; margin-top:15px;"></div></a>
            </div>
        </div>
        <button type="button" onclick="addDishEntry()">Add Dish</button>
        <button type="submit" name="generateQR">Save Changes</button>
    </form>
    <script>
        var qrcode = new QRCode(document.getElementById("qrcode"), {
            width: 100,
            height: 100
        });

        var currentUrl = window.location.href;
        var baseUrl = currentUrl.substring(0, currentUrl.lastIndexOf('/') + 1);
        var redirectUrl = baseUrl + "order.php?menu=" + <?php echo json_encode($_SESSION['uid']); ?>;

        qrcode.makeCode(redirectUrl);
        var qrLink = document.getElementById("qrLink");
        qrLink.setAttribute("href", redirectUrl);
    </script>

    <script>
        // Function to add a new dish entry
        function addDishEntry() {
            const dishEntry = `
                <div class="dish-entry">
                    <label>Dish Name: <input type="text" name="dish_name[]" value="Tonkotsu Ramen" required></label><br>
                    <label>Price: <input type="number" name="price[]" step="0.01" value="15.99" required></label><br>
                    <label>Description: <input type="text" name="description[]" value="Extra Spicy" required></label><br>
                    <button type="button" class="deleteDish">Delete</button><br>
                </div>
            `;
            $('#dish-container').append(dishEntry);
        }

        $(document).on('click', '.deleteDish', function() {
        const dishEntry = $(this).closest('.dish-entry');
        const dishName = dishEntry.find('input[name="dish_name[]"]').val();

        if (dishName) {
            $.ajax({
                url: 'delete_dish.php',
                type: 'POST',
                data: { dishName: dishName },
                success: function(response) {
                    //alert("Dish Deleted: " + response);
                }
            });
        }

        dishEntry.remove(); // Remove the dish entry from the DOM
        });
    </script>
</body>
</html>

<?php
if (isset($_POST['generateQR'])) {
    include('database.php');

    $dishNames = $_POST['dish_name'];
    $prices = $_POST['price'];
    $description = $_POST['description'];

    // Check for duplicates
    $uniqueNames = array_unique($dishNames);
    if (count($uniqueNames) < count($dishNames)) {
        // Find the duplicate names
        $duplicates = array_diff_assoc($dishNames, $uniqueNames);
        
        // Create error message
        $duplicateList = implode(', ', array_unique($duplicates));
        echo '<div class="error" style="color: red;">
                Error: You have duplicate dish names: ' . htmlspecialchars($duplicateList) . '
                <br>Please make all dish names are unique before saving.
              </div>';
    } else {
        // No duplicates found, proceed with save       
        // Clear existing menu items for this user
        $deleteQuery = "DELETE FROM menu WHERE uID = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param("i", $_SESSION['uid']);
        $deleteStmt->execute();
        
        // Insert all current menu items
        $query = "INSERT INTO menu (uID, dishID, name, price, description) VALUES (?, NULL, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        
        // Bind parameters once, then reuse in the loop
        $stmt->bind_param("isss", $_SESSION['uid'], $dishName, $price, $desc);
        
        for ($i = 0; $i < count($dishNames); $i++) {
            // Set values for this iteration
            $dishName = $dishNames[$i];
            $price = $prices[$i];
            $desc = $description[$i];
            
            $stmt->execute();
        }
        // header('Location: menu_editor.php');
        // echo '<div class="submitted-data"><h2>Dishes Saved</h2> </div>';
        // exit;
    }
}
?>