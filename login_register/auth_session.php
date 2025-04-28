<?php
session_start();

// If no user/customer/supplier is logged in, redirect to login
if (
    !isset($_SESSION['user_id']) && 
    !isset($_SESSION['customer_id']) && 
    !isset($_SESSION['supplier_id'])
) {
    header("Location: login_register/login.php");
    exit;
}
?>
