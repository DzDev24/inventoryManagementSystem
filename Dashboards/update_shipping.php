<?php
session_start();
require_once "../includes/db.php";

// Ensure customer is logged in
if (!isset($_SESSION['customer_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$customerId = $_SESSION['customer_id'];

// Sanitize input
$name = trim($_POST['real_name'] ?? '');
$address = trim($_POST['address'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$state_id = intval($_POST['state_id'] ?? 0);

if ($name === '' || $address === '' || $phone === '' || $state_id === 0) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

// Update customer table
$stmt = $conn->prepare("UPDATE customers SET Name = ?, `Shipping Address` = ?, Phone = ?, State_ID = ? WHERE Customer_ID = ?");
$stmt->bind_param("sssii", $name, $address, $phone, $state_id, $customerId);

if ($stmt->execute()) {
    $_SESSION['customer_name'] = $name;
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update info']);
}
?>