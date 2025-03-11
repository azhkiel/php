<?php 
    include "service/database.php";
    session_start();
    $login_mesegge = "";
    if (isset($_SESSION["is_login"])){
        header("Location: dashboard.php");
    }

    if (isset($_POST['login'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $hash_password = hash("sha256",$password);
        $sql = "SELECT * FROM akunphp WHERE username = '$username' AND password = '$hash_password'";
        $result = $db->query($sql);
        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            $_SESSION["username"] = $data["username"];
            $_SESSION["is_login"] = true; 
            echo $data['username'];
            header("Location: dashboard.php");
        } else {
            $login_mesegge = "akun tidak ada/nama atau password salah!";
        }
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
    <?php include "layout/header.html" ?>
    <h1>Login Form</h1>
    <p><?= $login_mesegge;?></p>
    <form method="post" action="login.php">
        <label for="name">Name:</label>
        <input type="text" placeholder="username" name="username">
        <label for="password">Password:</label>
        <input type="password" placeholder="password" name="password">
        <button type="submit" name="login">Submit</button>
    </form>
    <?php include "layout/footer.html" ?>
</body>
</html>