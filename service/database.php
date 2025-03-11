<?php
$hostname = "localhost";
$username = "root";
$password = "";
$database_name = "php";

$db = mysqli_connect($hostname, $username, $password, $database_name,3307);
if($db->connect_error){
    echo "Connection failed";
    die("Connection failed");
}
?>