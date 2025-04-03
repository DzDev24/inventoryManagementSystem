<?php
require_once "../includes/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name            = trim($_POST['name']);
    $email           = trim($_POST['email']);
    $phone           = trim($_POST['phone']);
    $shippingAddress = trim($_POST['shipping_address']);
    $stateId         = intval($_POST['state_id']);
    $status          = $_POST['status'];
    $orders          = intval($_POST['orders']);
    $totalSpend      = intval($_POST['total_spend']);

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

    if (isset($_POST['customer_id'])) {
        // UPDATE existing customer
        $id = intval($_POST['customer_id']);

        if ($mediaId) {
            $stmt = $conn->prepare("UPDATE customers SET Name=?, Email=?, Phone=?, `Shipping Address`=?, State_ID=?, Status=?, Orders=?, Total_Spend=?, Media_ID=? WHERE Customer_ID=?");
            $stmt->bind_param("sssssssiii", $name, $email, $phone, $shippingAddress, $stateId, $status, $orders, $totalSpend, $mediaId, $id);
        } else {
            $stmt = $conn->prepare("UPDATE customers SET Name=?, Email=?, Phone=?, `Shipping Address`=?, State_ID=?, Status=?, Orders=?, Total_Spend=? WHERE Customer_ID=?");
            $stmt->bind_param("sssssssii", $name, $email, $phone, $shippingAddress, $stateId, $status, $orders, $totalSpend, $id);
        }

        if ($stmt->execute()) {
            header("Location: ../customers_list.php?updated=1");
        } else {
            echo "Error updating customer: " . $stmt->error;
        }
        $stmt->close();
    } else {
        // INSERT new customer
        $stmt = $conn->prepare("INSERT INTO customers (Name, Email, Phone, `Shipping Address`, State_ID, Status, Orders, Total_Spend, Media_ID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssii", $name, $email, $phone, $shippingAddress, $stateId, $status, $orders, $totalSpend, $mediaId);
        if ($stmt->execute()) {
            header("Location: ../customers_list.php?added=1");
        } else {
            echo "Error adding customer: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Handle DELETE
if (isset($_GET['deleteid'])) {
    $id = intval($_GET['deleteid']);
    $stmt = $conn->prepare("DELETE FROM customers WHERE Customer_ID = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: ../customers_list.php?deleted=1");
    } else {
        echo "Error deleting customer: " . $stmt->error;
    }

    $stmt->close();
}
