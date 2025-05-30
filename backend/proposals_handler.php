<?php
require_once "../includes/db.php";



// Handle DELETE Purchase
if (isset($_GET['rejectedid'])) {
    $id = intval($_GET['rejectedid']);

    // Update the 'Accepted' status
    $stmt = $conn->prepare("UPDATE purchases SET Accepted = 2 WHERE Purchase_ID = ?");
    $stmt->bind_param("i", $id);


    if ($stmt->execute()) {
        header("Location: ../proposals_list.php?rejectedid=1");
    } else {
        echo "Error deleting proposals: " . $stmt->error;
    }

    $stmt->close();
}



if (isset($_GET['acceptid'])) {
    $id = intval($_GET['acceptid']);

    // Update the 'Accepted' status
    $stmt = $conn->prepare("UPDATE purchases SET Accepted = 1 WHERE Purchase_ID = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Retrieve all related purchase details
        $detailsStmt = $conn->prepare("SELECT Product_ID, QTY FROM purchases_details WHERE Purchase_ID = ?");
        $detailsStmt->bind_param("i", $id);
        $detailsStmt->execute();
        $detailsResult = $detailsStmt->get_result();

        // Update stock for each product
        while ($row = $detailsResult->fetch_assoc()) {
            $productId = $row['Product_ID'];
            $quantity = $row['QTY'];

            $updateStock = $conn->prepare("UPDATE products SET Quantity = Quantity + ? WHERE Product_ID = ?");
            $updateStock->bind_param("ii", $quantity, $productId);
            $updateStock->execute();
            $updateStock->close();
        }

        $detailsStmt->close();

        header("Location: ../proposals_list.php?acceptid=1"); // Redirect after success
        exit();
    } else {
        echo "Error updating proposal: " . $stmt->error;
    }

    $stmt->close();
}
