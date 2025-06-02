<?php
require_once "../includes/db.php";

// Supplier info to update
$supplierId = 53;
$newName = "Mohamed Aymenm1";

// Prepare and execute the update
$stmt = $conn->prepare("UPDATE supplier SET Supplier_Name = ? WHERE Supplier_ID = ?");
$stmt->bind_param("si", $newName, $supplierId);

if ($stmt->execute()) {
    echo "Supplier name updated successfully.";
} else {
    echo "Error updating supplier name: " . $stmt->error;
}

$stmt->close();
$conn->close();
