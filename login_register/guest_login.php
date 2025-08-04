<?php
session_start();

$_SESSION['user_id'] = 0;
$_SESSION['user_role'] = 4;
$_SESSION['user_name'] = 'Guest';
$_SESSION['user_type'] = 'guest'; 

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

header("Location: ../Dashboards/customer_dashboard.php");
exit;
