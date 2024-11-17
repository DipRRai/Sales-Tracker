<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="base.css">
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="dashboard.css">
    <title>Document</title>
</head>
<body>
    <?php
        include('header.html');
    ?>
    <div id='navbar'>
         <h1>Welcome <?php session_start(); echo "{$_SESSION['username']}"; ?><br>
    </div>
    <div id="options">
        <form action="menu_editor.php" class="option-form" method="post">
            <input type="submit" class="option-button" value="Edit Menu">
        </form>
        <form action="analytics.php" class="option-form" method="post">
            <input type="submit" class="option-button" value="View Analytics">
        </form>
        <form action="storefront.php" class="option-form" method="post">
            <input type="submit" class="option-button" value="Store Front">
        </form>
    </div>
</body>
</html>