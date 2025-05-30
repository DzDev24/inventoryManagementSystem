<?php
session_start();

// Redirect based on user_role (admin, sales, product manager)
if (isset($_SESSION['user_role'])) {
    switch ($_SESSION['user_role']) {
        case 1:
            header("Location: ./admin_dashboard.php");
            exit;
        case 2:
            header("Location: ./sales_dashboard.php");
            exit;
        case 3:
            header("Location: ./product-manager-dashboard.php");
            exit;
        default:
            header("Location: ./unauthorized.php");
            exit;
    }
}

// Redirect based on user_type (customer or supplier)
if (isset($_SESSION['user_type'])) {
    if ($_SESSION['user_type'] === 'customer') {
        header("Location: ./Dashboards/customer_dashboard.php");
        exit;
    } elseif ($_SESSION['user_type'] === 'supplier') {
        header("Location: ./Dashboards/supplier_dashboard.php");
        exit;
    }
}

// If not logged in at all, redirect to login
header("Location: ./login_register/login.php");
exit;
