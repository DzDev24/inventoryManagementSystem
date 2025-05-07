<?php
require_once "../login_register/auth_session.php";
require_once "../includes/db.php";

if ($_SESSION['user_type'] !== 'supplier') {
    header("Location: ../login_register/login.php");
    exit;
}

$supplier_id = $_SESSION['supplier_id'];

$name = trim($_POST['supplier_name']);
$company = trim($_POST['company_name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$address = trim($_POST['shipping_address']);
$state_id = intval($_POST['state_id']);
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
        $relativePath = 'uploads/' . uniqid('supplier_', true) . '.' . $ext;
        $fullPath = "../" . $relativePath;

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
    header("Location: supplier_profile.php");
    exit;
}

// Build SQL dynamically
$sql = "UPDATE supplier SET Supplier_Name = ?, Company_Name = ?, Email = ?, Phone = ?, Address = ?, State_ID = ?";
$params = [$name, $company, $email, $phone, $address, $state_id];
$types = "sssssi";

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

$sql .= " WHERE Supplier_ID = ?";
$params[] = $supplier_id;
$types .= "i";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    $_SESSION['success'] = "Profile updated successfully.";
    $_SESSION['supplier_name'] = $name;
} else {
    $_SESSION['error'] = "Failed to update profile.";
}

$_SESSION['profile_updated'] = true;
header("Location: supplier_dashboard.php");
exit;
