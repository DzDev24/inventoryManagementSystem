<?php
require_once "../includes/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $purchaseId = isset($_POST['purchase_id']) ? intval($_POST['purchase_id']) : null;
    $purchaseDate    = $_POST['purchase_date'];
    $paymentMethod   = $_POST['payment_method'];
    $deliveryStatus  = $_POST['delivery_status'];
    $paymentStatus   = $_POST['payment_status'];
    $notes           = trim($_POST['notes']);
    $products        = $_POST['products'] ?? [];

    // Calculate total amount
    $totalAmount = 0;
    foreach ($products as $product) {
        $qty = intval($product['quantity']);
        $price = floatval($product['buy_price']);
        $totalAmount += $qty * $price;
    }

    if ($purchaseId) {
        // --- 🛠 Edit Mode ---

        // 1. Rollback old stock
        $stmtOldProducts = $conn->prepare("SELECT Product_ID, QTY FROM purchases_details WHERE Purchase_ID = ?");
        $stmtOldProducts->bind_param("i", $purchaseId);
        $stmtOldProducts->execute();
        $oldProductsResult = $stmtOldProducts->get_result();
        while ($oldProd = $oldProductsResult->fetch_assoc()) {
            $updateStock = $conn->prepare("UPDATE products SET Quantity = Quantity - ? WHERE Product_ID = ?");
            $updateStock->bind_param("ii", $oldProd['QTY'], $oldProd['Product_ID']);
            $updateStock->execute();
            $updateStock->close();
        }
        $stmtOldProducts->close();

        // 2. Delete old purchase details
        $conn->query("DELETE FROM purchases_details WHERE Purchase_ID = $purchaseId");

        // 3. Update main purchase
        $stmtUpdate = $conn->prepare("UPDATE purchases SET Purchase_Date=?, Delivery_Status=?, Payment_Method=?, Payment_Status=?, Notes=?, Total_Amount=?, Updated_At=NOW() WHERE Purchase_ID=?");
        $stmtUpdate->bind_param("ssssssi", $purchaseDate, $deliveryStatus, $paymentMethod, $paymentStatus, $notes, $totalAmount, $purchaseId);
        $stmtUpdate->execute();
        $stmtUpdate->close();
    } else {
        // --- ➕ Add Mode ---

        $stmt = $conn->prepare("INSERT INTO purchases (Purchase_Date, Delivery_Status, Payment_Method, Payment_Status, Notes, Total_Amount, Report_ID, Accepted) 
                                VALUES (?, ?, ?, ?, ?, ?, NULL, ?)");
        $accepted = 1;
        $stmt->bind_param("sssssid", $purchaseDate, $deliveryStatus, $paymentMethod, $paymentStatus, $notes, $totalAmount, $accepted);

        $stmt->execute();
        $purchaseId = $stmt->insert_id;
        $stmt->close();
    }

    // 4. Insert new purchase details
    foreach ($products as $product) {
        $productId  = intval($product['product_id']);
        $supplierId = intval($product['supplier_id']);
        $quantity   = intval($product['quantity']);
        $buyPrice   = floatval($product['buy_price']);
        $totalCost  = $quantity * $buyPrice;

        $stmtDetails = $conn->prepare("INSERT INTO purchases_details (Purchase_ID, Product_ID, Supplier_ID, Buy_Price, QTY, Total_Cost, Status)
        VALUES (?, ?, ?, ?, ?, ?, 'Received')");
        $stmtDetails->bind_param("iiidii", $purchaseId, $productId, $supplierId, $buyPrice, $quantity, $totalCost);

        $stmtDetails->execute();
        $stmtDetails->close();

        // 5. Update stock
         $updateStock = $conn->prepare("UPDATE products SET Quantity = Quantity + ? WHERE Product_ID = ?");
         $updateStock->bind_param("ii", $quantity, $productId);
         $updateStock->execute();
         $updateStock->close();
    }

    header("Location: ../purchases_list.php?updated=1");
}

// Handle DELETE Purchase
if (isset($_GET['deleteid'])) {
    $id = intval($_GET['deleteid']);

    // First delete purchase details
    $conn->query("DELETE FROM purchases_details WHERE Purchase_ID = $id");

    // Then delete purchase
    $stmt = $conn->prepare("DELETE FROM purchases WHERE Purchase_ID = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: ../purchases_list.php?deleted=1");
    } else {
        echo "Error deleting purchase: " . $stmt->error;
    }

    $stmt->close();
}
