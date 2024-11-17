<?php
    include('header.html');
    include('database.php');

    $sql = "INSERT INTO users (username, password) 
            VALUES ('dip', 'pass')";

    //mysqli_query($conn, $sql);

    mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="base.css">
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="loginCSS.css">
    <title>Document</title>
</head>
<body>
    <div id="navbar">
      <h1>Register</h1>
    </div>
    <form action="register.php" id="entry-form" method="post">
        <label for="username">Username</label><br>
        <input type="text" name="username" id="" value="dip"><br>
        <label for="password">Password</label><br>
        <input type="password" name="password" id="" value="dip"><br>
        <input type="submit" name="submit" value="register">
    </form>
</body>
</html>

<?php
    include('database.php');

    if (isset($_POST['submit'])) {
        $username = $_POST['username'];
        $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, password) 
        VALUES ('$username', '$hash')";
        try{
            mysqli_query($conn, $sql);
            echo "registration successful";
            //echo "username: {$_POST['username']} <br>password: {$_POST['password']} <br> hash: {$hash}<br>";

        } catch (mysqli_sql_exception){
            echo "error username is already taken";
        }

    }

    mysqli_close($conn);
?>