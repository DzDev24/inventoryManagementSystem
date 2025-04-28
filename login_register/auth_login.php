<?php
session_start();
require_once "../includes/db.php";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // Get submitted credentials
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Basic validation
    if (empty($email) || empty($password)) {
        header("Location: login.php?error=invalid");
        exit;
    }

    // 1. Try to find in `users` table
    $stmt = $conn->prepare("SELECT * FROM users WHERE Email = ? AND Password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $userResult = $stmt->get_result();

    if ($userResult->num_rows > 0) {
        $user = $userResult->fetch_assoc();
        
        // Update user status to Online
        $updateStatus = $conn->prepare("UPDATE users SET Status = 'Online' WHERE User_ID = ?");
        $updateStatus->bind_param("i", $user['User_ID']);
        $updateStatus->execute();

        $_SESSION['user_id'] = $user['User_ID'];
        $_SESSION['user_role'] = $user['Role_ID']; // important for permissions
        $_SESSION['user_name'] = $user['Username'];
        $_SESSION['user_type'] = 'user'; // to differentiate in app
        
        header("Location: ../index.php");
        exit;
    }

    // 2. Try to find in `customers` table
    $stmt = $conn->prepare("SELECT * FROM customers WHERE Email = ? AND Password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $customerResult = $stmt->get_result();

    if ($customerResult->num_rows > 0) {
        $customer = $customerResult->fetch_assoc();

        $_SESSION['customer_id'] = $customer['Customer_ID'];
        $_SESSION['customer_name'] = $customer['Name'];
        $_SESSION['user_type'] = 'customer';
        
        header("Location: ../index.php");
        exit;
    }

    // 3. Try to find in `supplier` table
    $stmt = $conn->prepare("SELECT * FROM supplier WHERE Email = ? AND Password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $supplierResult = $stmt->get_result();

    if ($supplierResult->num_rows > 0) {
        $supplier = $supplierResult->fetch_assoc();

        $_SESSION['supplier_id'] = $supplier['Supplier_ID'];
        $_SESSION['supplier_name'] = $supplier['Supplier_Name'];
        $_SESSION['user_type'] = 'supplier';

        header("Location: ../index.php");
        exit;
    }

    // ❌ If no match found
    header("Location: login.php?error=invalid");
    exit;

} else {
    header("Location: login.php");
    exit;
}
?>
