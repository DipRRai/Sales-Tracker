<?php
    include('header.html');
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
        <h1>Login</h1>
    </div>
    <form action="login.php" id="entry-form" method="post">
        <label for="username">Username</label><br>
        <input type="text" name="username" id="" value="dip"><br>
        <label for="password">Password</label><br>
        <input type="password" name="password" id="" value="dip"><br>
        <input type="submit" name="submit" value="login">
    </form>
</body>
</html>

<?php
    include('database.php');
   
    session_start();

    if (isset($_POST['submit'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE username='$username'";
        $result = mysqli_query($conn, $sql);
        if ($result && mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);
            $hash = $row['password'];
            if (password_verify($password, $hash) == 1){
                $_SESSION['uid'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                header('Location: dashboard.php');
                exit();
            } else {
                echo "incorrect password or username";
            }
        } else {
            echo "incorrect password or username";
        }
    }

    mysqli_close($conn);


