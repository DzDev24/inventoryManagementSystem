<?php
require_once "login_register/auth_session.php";
require_once "includes/db.php";

if ($_SESSION['user_type'] !== 'user') {
    header("Location:login_register/login.php");
    exit;
}

$user_id = intval($_SESSION['user_id']);

$username = trim($_POST['username']);
$real_name = trim($_POST['real_name']);
$email = trim($_POST['email']);
$status = $_POST['status'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

$errors = [];

// Optional password update
if (!empty($password)) {
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    } else {
        $plainPassword = $password;
    }
}

// Handle profile picture upload
$media_id = null;
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
    $fileTmp = $_FILES['profile_picture']['tmp_name'];
    $fileName = basename($_FILES['profile_picture']['name']);
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png'];

    if (in_array($ext, $allowed)) {
        $relativePath = 'uploads/' . uniqid('profile_', true) . '.' . $ext;
        $fullPath = "" . $relativePath;

        if (move_uploaded_file($fileTmp, $fullPath)) {
            $stmtMedia = $conn->prepare("INSERT INTO media (File_Path) VALUES (?)");
            $stmtMedia->bind_param("s", $relativePath);
            if ($stmtMedia->execute()) {
                $media_id = $stmtMedia->insert_id;
            }
        }
    } else {
        $errors[] = "Invalid image format. Only JPG, JPEG, PNG allowed.";
    }
}

if (!empty($errors)) {
    $_SESSION['error'] = implode("<br>", $errors);
    header("Location: user_profile.php");
    exit;
}

// Build SQL dynamically
$sql = "UPDATE users SET Username = ?, Real_Name = ?, Email = ?, Status = ?";
$params = [$username, $real_name, $email, $status];
$types = "ssss";

if (isset($plainPassword)) {
    $sql .= ", Password = ?";
    $params[] = $plainPassword;
    $types .= "s";
}

if ($media_id !== null) {
    $sql .= ", Media_ID = ?";
    $params[] = $media_id;
    $types .= "i";
}

$sql .= " WHERE User_ID = ?";
$params[] = $user_id;
$types .= "i";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    $_SESSION['success'] = "Profile updated successfully.";
} else {
    $_SESSION['error'] = "Failed to update profile.";
}

$_SESSION['profile_updated'] = true;
switch ($_SESSION['user_role']) {
    case 1: // Admin
        header("Location: admin_dashboard.php");
        break;
    case 2: // Sales Manager
        header("Location:  sales_dashboard.php");
        break;
    case 3: // Product Manager
        header("Location: product-manager-dashboard.php");
        break;
    default:
        header("Location: login_register/login.php?error=unauthorized");
        break;
}
exit;
