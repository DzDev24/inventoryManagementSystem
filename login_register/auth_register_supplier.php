<?php
require_once "../includes/db.php";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // Retrieve and sanitize inputs
    $supplierName = trim($_POST['supplier_name']);
    $companyName = trim($_POST['company_name']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $stateID = intval($_POST['state_id']);
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';

    // Basic validation
    $errors = [];

    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (!preg_match('/^(\+213|0)[0-9]{9}$/', $phone)) {
        $errors[] = "Invalid phone format.";
    }
    
    if (empty($supplierName) || empty($companyName) || empty($password) || empty($email) || empty($phone) || empty($address) || empty($stateID)) {
        $errors[] = "Please fill in all required fields.";
    }

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color:red; text-align:center;'>$error</p>";
        }
        echo "<p style='text-align:center;'><a href='register_supplier.php'>Go back</a></p>";
        exit;
    }

    // Handle Image Upload if provided
    $mediaID = null;
    if (!empty($_FILES['image']['name'])) {

        $uploadsDir = "../uploads/profiles/";
$relativePath = "uploads/profiles/"; // this is what you save to DB

if (!is_dir($uploadsDir)) {
    mkdir($uploadsDir, 0777, true);
}

$filename = time() . "_" . basename($_FILES['image']['name']);
$targetPath = $uploadsDir . $filename;

if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
    $fileType = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $filePathToSave = $relativePath . $filename; // 👈 Clean relative path!

    $stmtMedia = $conn->prepare("INSERT INTO media (File_Name, File_Type, File_Path) VALUES (?, ?, ?)");
    $stmtMedia->bind_param("sss", $filename, $fileType, $filePathToSave); // 👈 save clean path
    $stmtMedia->execute();
    $mediaID = $stmtMedia->insert_id;
    $stmtMedia->close();
}

    }

    // Insert into supplier table
    $status = 'Available'; // Always Available at registration

    $stmt = $conn->prepare("INSERT INTO supplier (Supplier_Name, Company_Name, Password, Email, Phone, Address, State_ID, Description, Media_ID, Status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssisss", $supplierName, $companyName, $password, $email, $phone, $address, $stateID, $description, $mediaID, $status);

    if ($stmt->execute()) {
        // Registration successful, redirect to login
        header("Location: login.php?registered=success");
        exit;
    } else {
        echo "<p style='color:red; text-align:center;'>Error occurred during registration. Please try again.</p>";
        echo "<p style='text-align:center;'><a href='register_supplier.php'>Go back</a></p>";
        exit;
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: register_supplier.php");
    exit;
}
?>
