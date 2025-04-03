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

    if (isset($_POST['supplier_id'])) {
        // UPDATE existing supplier
        $id = intval($_POST['supplier_id']);

        if ($mediaId) {
            $stmt = $conn->prepare("UPDATE supplier SET Supplier_Name=?, Email=?, Phone=?, Company_Name=?, Address=?, State_ID=?, Status=?, Description=?, Media_ID=? WHERE Supplier_ID=?");
            $stmt->bind_param("sssssiissi", $supplierName, $email, $phone, $companyName, $address, $stateId, $status, $description, $mediaId, $id);
        } else {
            $stmt = $conn->prepare("UPDATE supplier SET Supplier_Name=?, Email=?, Phone=?, Company_Name=?, Address=?, State_ID=?, Status=?, Description=? WHERE Supplier_ID=?");
            $stmt->bind_param("sssssissi", $supplierName, $email, $phone, $companyName, $address, $stateId, $status, $description, $id);
        }

        if ($stmt->execute()) {
            header("Location: ../suppliers_list.php?updated=1");
        } else {
            echo "Error update supplier: " . $stmt->error;
        }
        $stmt->close();
    } else {
        // INSERT new supplier
        $stmt = $conn->prepare("INSERT INTO supplier (Supplier_Name, Email, Phone, Company_Name, Address, State_ID, Media_ID, Status, Description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssiiss", $supplierName, $email, $phone, $companyName, $address, $stateId, $mediaId, $status, $description);

        if ($stmt->execute()) {
            header("Location: ../suppliers_list.php?added=1");
        } else {
            echo "Error add supplier: " . $stmt->error;
        }


        $stmt->close();
    }
}

// Handle DELETE
if (isset($_GET['deleteid'])) {
    $id = intval($_GET['deleteid']);
    $stmt = $conn->prepare("DELETE FROM supplier WHERE Supplier_ID = ?");
    $stmt->bind_param("i", $id);


    if ($stmt->execute()) {
        header("Location: ../suppliers_list.php?deleted=1");
    } else {
        echo "Error deketed supplier: " . $stmt->error;
    }

    $stmt->close();
}
