<?php
require_once "../login_register/auth_session.php";

// Check if it's a user with Admin role
if ($_SESSION['user_type'] !== 'user' || $_SESSION['user_role'] != 2) {
    header("Location: ../login_register/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="../css/vendor/bootstrap.css" rel="stylesheet" />
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-primary">Welcome to the sales Dashboard</h1>
        <p>This is a placeholder for admin-specific tools and views.</p>
        <a href="../login_register/logout.php" class="btn btn-outline-secondary mt-3">Logout</a>
    </div>
</body>
</html>