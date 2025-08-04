<?php
session_start(); // Always at the top

// ✅ Allow guest users only on safe paths
if (isset($_SESSION['user_name']) && $_SESSION['user_name'] === 'Guest') {
    $restricted_guest_paths = ['/admin/', '/supplier/', '/manager/', '/product-manager/', '/reports/'];
    foreach ($restricted_guest_paths as $path) {
        if (strpos($_SERVER['REQUEST_URI'], $path) !== false) {
            header("Location: ../Dashboards/customer_dashboard.php");
            exit;
        }
    }
    // ✅ Guest access is allowed — stop the script here
    return;
}

// ✅ Registered users must have one of these
if (
    !isset($_SESSION['user_id']) &&
    !isset($_SESSION['customer_id']) &&
    !isset($_SESSION['supplier_id'])
) {
    $current_dir_fs = str_replace('\\', '/', __DIR__);
    $app_root_fs = dirname($current_dir_fs);
    $document_root_fs = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '/');

    $app_base_web_path = '';
    if (stripos($app_root_fs, $document_root_fs) === 0) {
        $app_base_web_path = substr($app_root_fs, strlen($document_root_fs));
    }

    $app_base_web_path = '/' . trim($app_base_web_path, '/');
    if ($app_base_web_path === '/') {
        $app_base_web_path = '';
    }

    $login_url = $app_base_web_path . "/login_register/login.php";

    header("Location: " . $login_url);
    exit;
}
