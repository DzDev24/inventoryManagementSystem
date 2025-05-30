<?php
session_start();
ini_set('session.gc_maxlifetime', 3600 * 24); // 1 hour on server
session_set_cookie_params(3600 * 24);         // 1 hour in browser cookie

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

    // 1. Try to find in users table
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
        $_SESSION['user_type'] = 'user';

        // ✅ Role-based redirect
        $roleId = $user['Role_ID'];
        if ($roleId == 1) {
            header("Location: ../admin_dashboard.php");
        } elseif ($roleId == 2) {
            header("Location: ../sales_dashboard.php");
        } elseif ($roleId == 3) {
            header("Location: ../product-manager-dashboard.php");
        } else {
            header("Location: ../login_register/login.php?error=unauthorized");
        }
        exit;
    }

    // 2. Try to find in customers table
    $stmt = $conn->prepare("SELECT * FROM customers WHERE Email = ? AND Password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $customerResult = $stmt->get_result();

    if ($customerResult->num_rows > 0) {
        $customer = $customerResult->fetch_assoc();

        $_SESSION['customer_id'] = $customer['Customer_ID'];
        $_SESSION['customer_name'] = $customer['Name'];
        $_SESSION['user_type'] = 'customer';

        header("Location: ../Dashboards/customer_dashboard.php");
        exit;
    }

    // 3. Try to find in supplier table
    $stmt = $conn->prepare("SELECT * FROM supplier WHERE Email = ? AND Password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $supplierResult = $stmt->get_result();

    if ($supplierResult->num_rows > 0) {
        $supplier = $supplierResult->fetch_assoc();

        $_SESSION['supplier_id'] = $supplier['Supplier_ID'];
        $_SESSION['supplier_name'] = $supplier['Supplier_Name'];
        $_SESSION['user_type'] = 'supplier';

        header("Location: ../Dashboards/supplier_dashboard.php");
        exit;
    }

    // ❌ No match found
    header("Location: login.php?error=invalid");
    exit;
} else {
    header("Location: login.php");
    exit;
}
