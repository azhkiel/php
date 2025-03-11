<?php
    include "service/database.php";
    session_start();
    $register_messege = "";
    if (isset($_SESSION["is_login"])){
        header("Location: dashboard.php");
    }

    try{
        if(isset($_POST['register'])){
            $username = $_POST['username'];
            $password = $_POST['password'];
            $hash_password = hash("sha256",$password);

            $sql = "INSERT INTO `akunphp`(`username`, `password`) VALUES ('$username','$hash_password')";
            if($db->query($sql)){
                $register_messege = "Register success";
            }else{
                echo "Register failed";
        }
    }
    }catch(mysqli_sql_exception){
        $register_messege = "username already registered!";
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
    <h1>Register Form</h1>
    <p><?= $register_messege?></p>
    <form method="post" action="register.php">
        <label for="name">Name:</label>
        <input type="text" placeholder="username" name="username">
        <label for="password">Password:</label>
        <input type="password" placeholder="password" name="password">
        <button type="submit" name="register">Submit</button>
    </form>
    <?php include "layout/footer.html" ?>
</body>
</html>