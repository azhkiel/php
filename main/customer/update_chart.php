<?php
session_start();
include "../../service/database.php";

if (!isset($_SESSION["is_login"]) || $_SESSION["role"] !== "customer") {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $item_id = $_GET['id'];

    switch ($action) {
        case "increase":
            $db->query("UPDATE chart SET quantity = quantity + 1 WHERE id = '$item_id' AND user_id = '$user_id'");
            break;
        case "decrease":
            $result = $db->query("SELECT quantity FROM chart WHERE id = '$item_id' AND user_id = '$user_id'");
            $data = $result->fetch_assoc();
            if ($data['quantity'] > 1) {
                $db->query("UPDATE chart SET quantity = quantity - 1 WHERE id = '$item_id' AND user_id = '$user_id'");
            } else {
                $db->query("DELETE FROM chart WHERE id = '$item_id' AND user_id = '$user_id'");
            }
            break;
        case "remove":
            $db->query("DELETE FROM chart WHERE id = '$item_id' AND user_id = '$user_id'");
            break;
    }
}

header("Location: chart.php");
exit();
