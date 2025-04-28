<?php
require_once "../includes/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username']);
    $realName = trim($_POST['real_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);
    $roleID = intval($_POST['role_id']);

    $errors = [];

    if (empty($username) || empty($realName) || empty($email) || empty($password) || empty($confirmPassword) || empty($roleID)) {
        $errors[] = "Please fill in all required fields.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color:red; text-align:center;'>$error</p>";
        }
        echo "<p style='text-align:center;'><a href='register_user.php'>Go back</a></p>";
        exit;
    }

    // Handle Image Upload if provided
    $mediaID = null;
    if (!empty($_FILES['image']['name'])) {
        $uploadsDir = "../uploads/profiles/";
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0777, true);
        }

        $filename = time() . "_" . basename($_FILES['image']['name']);
        $targetPath = $uploadsDir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            // Save relative path
            $fileType = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $relativePath = "uploads/profiles/" . $filename; // <-- THIS is important

            $stmtMedia = $conn->prepare("INSERT INTO media (File_Name, File_Type, File_Path) VALUES (?, ?, ?)");
            $stmtMedia->bind_param("sss", $filename, $fileType, $relativePath);
            $stmtMedia->execute();
            $mediaID = $stmtMedia->insert_id;
            $stmtMedia->close();
        }
    }

    // Insert user into database
    $status = 'Offline'; // User is Offline at registration

    $stmt = $conn->prepare("INSERT INTO users (Username, Real_Name, Email, Password, Role_ID, Status, Media_ID) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssisi", $username, $realName, $email, $password, $roleID, $status, $mediaID);

    if ($stmt->execute()) {
        header("Location: login.php?registered=success");
        exit;
    } else {
        echo "<p style='color:red; text-align:center;'>Error occurred during registration. Please try again.</p>";
        echo "<p style='text-align:center;'><a href='register_user.php'>Go back</a></p>";
        exit;
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: register_user.php");
    exit;
}
?>

