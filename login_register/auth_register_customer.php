<?php
require_once "../includes/db.php";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Retrieve and sanitize inputs
    $name = trim($_POST['customer_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);
    $phone = trim($_POST['phone']);
    $shippingAddress = trim($_POST['shipping_address']);
    $stateID = intval($_POST['state_id']);

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

    if (empty($name) || empty($email) || empty($password) || empty($phone) || empty($shippingAddress) || empty($stateID)) {
        $errors[] = "Please fill in all required fields.";
    }

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color:red; text-align:center;'>$error</p>";
        }
        echo "<p style='text-align:center;'><a href='register_customer.php'>Go back</a></p>";
        exit;
    }

    // Handle Image Upload if provided
$mediaID = null;
if (!empty($_FILES['image']['name'])) {
    $uploadsDir = "../uploads/profiles/";
    if (!is_dir($uploadsDir)) {
        mkdir($uploadsDir, 0777, true); // Create directory if not exists
    }
    
    $filename = time() . "_" . basename($_FILES['image']['name']);
    $targetPath = $uploadsDir . $filename;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
        // Save media into media table
        $fileType = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        // ❗ Save the correct *relative path* to database
        $relativePath = "uploads/profiles/" . $filename;

        $stmtMedia = $conn->prepare("INSERT INTO media (File_Name, File_Type, File_Path) VALUES (?, ?, ?)");
        $stmtMedia->bind_param("sss", $filename, $fileType, $relativePath);
        $stmtMedia->execute();
        $mediaID = $stmtMedia->insert_id;
        $stmtMedia->close();
    }
}


    // Insert into customers table
    $status = 'Available'; // Always Available at registration
    $orders = 0;
    $totalSpend = 0;

    $stmt = $conn->prepare("INSERT INTO customers (Name, Email, Password, Phone, `Shipping Address`, State_ID, Status, Orders, Total_Spend, Media_ID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssisiii", $name, $email, $password, $phone, $shippingAddress, $stateID, $status, $orders, $totalSpend, $mediaID);

    if ($stmt->execute()) {
        // Registration successful, redirect to login
        header("Location: login.php?registered=success");
        exit;
    } else {
        echo "<p style='color:red; text-align:center;'>Error occurred during registration. Please try again.</p>";
        echo "<p style='text-align:center;'><a href='register_customer.php'>Go back</a></p>";
        exit;
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: register_customer.php");
    exit;
}
?>
