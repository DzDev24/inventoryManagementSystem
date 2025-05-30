<?php
session_start(); // Ensure this is at the very top

if (
    !isset($_SESSION['user_id']) &&
    !isset($_SESSION['customer_id']) &&
    !isset($_SESSION['supplier_id'])
) {
    // --- Dynamically determine the correct base path ---

    // 1. Get the absolute file system path to the directory of this file (auth_session.php)
    // e.g., /var/www/html/inventoryManagementSystem/login_register
    // or /home/user/public_html/login_register
    $current_dir_fs = str_replace('\\', '/', __DIR__);

    // 2. Determine the application's root file system path.
    // Since auth_session.php is in 'login_register', the app root is one level up.
    // e.g., /var/www/html/inventoryManagementSystem
    // or /home/user/public_html
    $app_root_fs = dirname($current_dir_fs);

    // 3. Get the server's document root (where web accessible files start).
    // e.g., /var/www/html
    // or /home/user/public_html
    // Remove trailing slash for consistency if present.
    $document_root_fs = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '/');

    // 4. Calculate the application's base web path (the subdirectory part, if any).
    $app_base_web_path = '';
    // Check if the app root path starts with the document root path
    if (stripos($app_root_fs, $document_root_fs) === 0) {
        // If yes, the base web path is the part of app_root_fs after document_root_fs
        $app_base_web_path = substr($app_root_fs, strlen($document_root_fs));
    }
    // $app_base_web_path might be like "/inventoryManagementSystem" or "" (if app is at domain root)

    // Sanitize the base web path:
    // Ensure it starts with a '/' if it's not empty, and remove any leading/trailing slashes first to normalize.
    $app_base_web_path = '/' . trim($app_base_web_path, '/');
    if ($app_base_web_path === '/') { // If it ended up as just a single slash (app is at root)
        $app_base_web_path = ''; // Make it an empty string for root deployments
    }

    // 5. Construct the full, absolute web path to the login page.
    // $app_base_web_path will be "" for root deployment, or "/inventoryManagementSystem" for local.
    $login_url = $app_base_web_path . "/login_register/login.php";

    // --- Perform the redirect ---
    header("Location: " . $login_url);
    exit; // Always call exit() after a header redirect
}
