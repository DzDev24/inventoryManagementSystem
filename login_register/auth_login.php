<?php
session_start();
ini_set('session.gc_maxlifetime', 3600 * 24); // 24 hours on server
session_set_cookie_params(3600 * 24);         // 24 hours in browser cookie

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

    // === 1. Check in USERS table ===
    $stmt = $conn->prepare("SELECT * FROM users WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $userResult = $stmt->get_result();

    if ($userResult->num_rows > 0) {
        $user = $userResult->fetch_assoc();

        if (password_verify($password, $user['Password'])) {
            // Check if user is accepted

            if ($user['Accepted'] === 0) {
                header("Location: login.php?error=not_accepted");
                exit;
            }

            // Update user status to Online
            $updateStatus = $conn->prepare("UPDATE users SET Status = 'Online' WHERE User_ID = ?");
            $updateStatus->bind_param("i", $user['User_ID']);
            $updateStatus->execute();

            $_SESSION['user_id'] = $user['User_ID'];
            $_SESSION['user_role'] = $user['Role_ID'];
            $_SESSION['user_name'] = $user['Username'];
            $_SESSION['user_type'] = 'user';

            // Role-based redirect
            switch ($user['Role_ID']) {
                case 1:
                    header("Location: ../admin_dashboard.php");
                    break;
                case 2:
                    header("Location: ../sales_dashboard.php");
                    break;
                case 3:
                    header("Location: ../product-manager-dashboard.php");
                    break;
                default:
                    header("Location: login.php?error=unauthorized");
                    break;
            }
            exit;
        }
    }

    //  Check in CUSTOMERS table
    $stmt = $conn->prepare("SELECT * FROM customers WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $customerResult = $stmt->get_result();

    if ($customerResult->num_rows > 0) {
        $customer = $customerResult->fetch_assoc();

        if (password_verify($password, $customer['Password'])) {
            $_SESSION['customer_id'] = $customer['Customer_ID'];
            $_SESSION['customer_name'] = $customer['Name'];
            $_SESSION['user_type'] = 'customer';

            header("Location: ../Dashboards/customer_dashboard.php");
            exit;
        }
    }

    //  3. Check in SUPPLIER table 
    $stmt = $conn->prepare("SELECT * FROM supplier WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $supplierResult = $stmt->get_result();

    if ($supplierResult->num_rows > 0) {
        $supplier = $supplierResult->fetch_assoc();

        if (password_verify($password, $supplier['Password'])) {
            $_SESSION['supplier_id'] = $supplier['Supplier_ID'];
            $_SESSION['supplier_name'] = $supplier['Supplier_Name'];
            $_SESSION['user_type'] = 'supplier';

            header("Location: ../Dashboards/supplier_dashboard.php");
            exit;
        }
    }

    
    header("Location: login.php?error=invalid");
    exit;
} else {
    header("Location: login.php");
    exit;
}
