<?php
require_once "../includes/db.php";



// Handle DELETE Purchase
if (isset($_GET['rejectedid'])) {
    $id = intval($_GET['rejectedid']);

    // Update the 'Accepted' status
    $stmt = $conn->prepare("UPDATE users SET Accepted = 2 WHERE User_ID = ?");
    $stmt->bind_param("i", $id);


    if ($stmt->execute()) {
        header("Location: ../users_pending.php?rejectedid=1");
    } else {
        echo "Error deleting proposals: " . $stmt->error;
    }

    $stmt->close();
}

if (isset($_GET['acceptid'])) {
    $id = intval($_GET['acceptid']);

    // Update the 'Accepted' status
    $stmt = $conn->prepare("UPDATE users SET Accepted = 1 WHERE User_ID = ?");
    $stmt->bind_param("i", $id);


    if ($stmt->execute()) {
        header("Location: ../users_pending.php?acceptid=1");
    } else {
        echo "Error deleting proposals: " . $stmt->error;
    }

    $stmt->close();
}
