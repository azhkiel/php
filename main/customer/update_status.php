<?php
include "../../service/database.php";

if (isset($_GET['status'])) {
    $status = $_GET['status'];
    $new_status = "";

    if ($status == "pending") {
        $new_status = "processed";
    } elseif ($status == "processed") {
        $new_status = "completed";
    } else {
        echo "Invalid status!";
        exit();
    }

    $sql = "UPDATE `order` SET status = '$new_status' WHERE status = '$status'";
    
    if ($db->query($sql)) {
        echo "Status updated to $new_status!";
    } else {
        echo "Error: " . $db->error;
    }
}
?>
