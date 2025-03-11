<?php
session_start();
if (isset($_POST['logout'])){
    session_unset();
    session_destroy();
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php include "layout/header.html"?>
    <h1>SELAMAT DATANG <?= $_SESSION["username"] ?></h1>
    <form method="post" action="dashboard.php">
        <button type="submit" name="logout">LogOut</button>
    </form>
    <?php include "layout/footer.html"?>
</body>
</html>