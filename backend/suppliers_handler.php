<?php
require_once "../includes/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $supplierName = trim($_POST['supplier_name']);
    $companyName  = trim($_POST['company_name']);
    $email        = trim($_POST['email']);
    $phone        = trim($_POST['phone']);
    $address      = trim($_POST['address']);
    $stateId      = intval($_POST['state_id']);
    $status       = $_POST['status'];
    $description  = trim($_POST['description']);
    $password     = trim($_POST['password']);
    $confirm      = trim($_POST['confirm_password']);

    if ($password !== $confirm) {
        die("Passwords do not match!");
    }

    $mediaId = null;

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

    if (isset($_POST['supplier_id'])) {
        // UPDATE existing supplier
        $id = intval($_POST['supplier_id']);
        $fields = [];
$types = '';
$params = [];

$fields[] = "Supplier_Name=?";
$types .= "s";
$params[] = $supplierName;

$fields[] = "Email=?";
$types .= "s";
$params[] = $email;

$fields[] = "Phone=?";
$types .= "s";
$params[] = $phone;

$fields[] = "Company_Name=?";
$types .= "s";
$params[] = $companyName;

$fields[] = "Address=?";
$types .= "s";
$params[] = $address;

$fields[] = "State_ID=?";
$types .= "i";
$params[] = $stateId;

$fields[] = "Status=?";
$types .= "s";
$params[] = $status;

$fields[] = "Description=?";
$types .= "s";
$params[] = $description;

if ($mediaId !== null) {
    $fields[] = "Media_ID=?";
    $types .= "i";
    $params[] = $mediaId;
}

if (!empty($password)) {
    $fields[] = "Password=?";
    $types .= "s";
    $params[] = $password;
}

$fieldsSQL = implode(", ", $fields) . " WHERE Supplier_ID=?";
$types .= "i";
$params[] = $id;

$sql = "UPDATE supplier SET $fieldsSQL";
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);

    } else {
        // INSERT new supplier (password is required here)
        $sql = "INSERT INTO supplier (Supplier_Name, Email, Phone, Company_Name, Address, State_ID, Media_ID, Status, Description, Password)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssiisss", $supplierName, $email, $phone, $companyName, $address, $stateId, $mediaId, $status, $description, $password);
    }

    if ($stmt->execute()) {
        $redirect = isset($_POST['supplier_id']) ? 'updated=1' : 'added=1';
        header("Location: ../suppliers_list.php?$redirect");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle DELETE
if (isset($_GET['deleteid'])) {
    $id = intval($_GET['deleteid']);
    $stmt = $conn->prepare("DELETE FROM supplier WHERE Supplier_ID = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: ../suppliers_list.php?deleted=1");
    } else {
        echo "Error deleting supplier: " . $stmt->error;
    }

    $stmt->close();
}


