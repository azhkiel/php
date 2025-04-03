<?php
session_start();
include "../../service/database.php";

// Check if user is logged in and has customer role
if (!isset($_SESSION["is_login"])) {
    header("HTTP/1.1 401 Unauthorized");
    exit(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

// Get order ID and new status from request
$order_id = $_GET['order_id'] ?? null;
$new_status = $_GET['status'] ?? null;

// Validate inputs
if (!$order_id || !$new_status) {
    header("HTTP/1.1 400 Bad Request");
    exit(json_encode(['success' => false, 'message' => 'Missing parameters']));
}

// Validate status
$allowed_statuses = ['pending', 'processed', 'completed', 'cancelled'];
if (!in_array($new_status, $allowed_statuses)) {
    header("HTTP/1.1 400 Bad Request");
    exit(json_encode(['success' => false, 'message' => 'Invalid status']));
}

// Update status in database
try {
    $stmt = $db->prepare("UPDATE `order` SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No rows affected']);
    }
} catch (Exception $e) {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>