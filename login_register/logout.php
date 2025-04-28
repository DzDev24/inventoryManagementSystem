<?php
session_start();
require_once "../includes/db.php"; // adjust path if needed

// If a user is logged in
if (isset($_SESSION['user_id'])) {
    $userId = intval($_SESSION['user_id']);
    // Set User Status to Offline
    $conn->query("UPDATE users SET Status = 'Offline' WHERE User_ID = $userId");
}

// If a supplier is logged in
if (isset($_SESSION['supplier_id'])) {
    $supplierId = intval($_SESSION['supplier_id']);
    // Set Supplier Status to Unavailable
    $conn->query("UPDATE supplier SET Status = 'Unavailable' WHERE Supplier_ID = $supplierId");
}

// If a customer is logged in
if (isset($_SESSION['customer_id'])) {
    $customerId = intval($_SESSION['customer_id']);
    // Set Customer Status to Unavailable
    $conn->query("UPDATE customers SET Status = 'Unavailable' WHERE Customer_ID = $customerId");
}

// Destroy session
session_unset();
session_destroy();

// Redirect to login page
header("Location: login_register/login.php");
exit;
?>
