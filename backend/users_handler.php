<?php
require_once "../includes/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username   = trim($_POST['username']);
    $realName   = trim($_POST['real_name']);
    $email      = trim($_POST['email']);
    $status     = $_POST['status'];
    $roleId     = intval($_POST['role_id']);
    $password   = trim($_POST['password']);
    $confirm    = trim($_POST['confirm_password']);
    $mediaId    = null;

    if ($password !== $confirm) {
        die("Passwords do not match!");
    }

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/profiles/";
        $originalName = basename($_FILES["image"]["name"]);
        $uniqueName = time() . "_" . $originalName;
        $targetPath = $targetDir . $uniqueName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], '../' . $targetPath)) {
            $fileType = pathinfo($originalName, PATHINFO_EXTENSION);
            $stmtMedia = $conn->prepare("INSERT INTO media (File_Name, File_Type, File_Path) VALUES (?, ?, ?)");
            $stmtMedia->bind_param("sss", $originalName, $fileType, $targetPath);
            $stmtMedia->execute();
            $mediaId = $stmtMedia->insert_id;
            $stmtMedia->close();
        }
    }
        $password = password_hash($password, PASSWORD_DEFAULT); // Hash the password

    if (isset($_POST['user_id'])) {
        // UPDATE existing user
        $id = intval($_POST['user_id']);

        $fields = "Username=?, Real_Name=?, Email=?, Status=?, Role_ID=?";
        $types = "ssssi";
        $params = [$username, $realName, $email, $status, $roleId];

        if ($mediaId !== null) {
            $fields .= ", Media_ID=?";
            $types .= "i";
            $params[] = $mediaId;
        }

        if (!empty($password)) {
            $fields .= ", Password=?";
            $types .= "s";
            $params[] = $password;
        }

        $fields .= " WHERE User_ID=?";
        $types .= "i";
        $params[] = $id;

        $sql = "UPDATE users SET $fields";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            header("Location: ../users_list.php?updated=1");
        } else {
            echo "Error updating user: " . $stmt->error;
        }
        $stmt->close();

    } else {
        // INSERT new user
        $sql = "INSERT INTO users (Username, Real_Name, Email, Status, Role_ID, Media_ID, Password)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssis", $username, $realName, $email, $status, $roleId, $mediaId, $password);

        if ($stmt->execute()) {
            header("Location: ../users_list.php?added=1");
        } else {
            echo "Error adding user: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Handle DELETE
if (isset($_GET['deleteid'])) {
    $id = intval($_GET['deleteid']);
    $stmt = $conn->prepare("DELETE FROM users WHERE User_ID = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: ../users_list.php?deleted=1");
    } else {
        echo "Error deleting user: " . $stmt->error;
    }

    $stmt->close();
}
