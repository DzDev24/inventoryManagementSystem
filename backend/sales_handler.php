<?php
require_once "../includes/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $saleId = isset($_POST['sale_id']) ? intval($_POST['sale_id']) : null;
    $saleDate = $_POST['sale_date'];
    $paymentMethod = $_POST['payment_method'];
    $deliveryStatus = $_POST['delivery_status'];
    $paymentStatus = $_POST['payment_status'];
    $customerId = intval($_POST['customer_id']);
    $notes = trim($_POST['notes']);
    $products = $_POST['products'] ?? [];

    // 1️⃣ Calculate total amount
    $totalAmount = 0;
    foreach ($products as $product) {
        $qty = intval($product['quantity']);
        $price = floatval($product['sale_price']);
        $totalAmount += $qty * $price;
    }

    // 2️⃣ Rollback stock and customer values if editing
    if ($saleId) {
        // Rollback product stock
        $stmtOldProducts = $conn->prepare("SELECT Product_ID, QTY FROM sales_order_details WHERE Sale_ID = ?");
        $stmtOldProducts->bind_param("i", $saleId);
        $stmtOldProducts->execute();
        $oldProductsResult = $stmtOldProducts->get_result();
        while ($oldProd = $oldProductsResult->fetch_assoc()) {
            $updateStock = $conn->prepare("UPDATE products SET Quantity = Quantity + ? WHERE Product_ID = ?");
            $updateStock->bind_param("ii", $oldProd['QTY'], $oldProd['Product_ID']);
            $updateStock->execute();
            $updateStock->close();
        }
        $stmtOldProducts->close();

        // Rollback old customer orders and spend
        $stmtOldSale = $conn->prepare("SELECT Customer_ID, Total_Amount FROM sales WHERE Sale_ID = ?");
        $stmtOldSale->bind_param("i", $saleId);
        $stmtOldSale->execute();
        $oldSaleData = $stmtOldSale->get_result()->fetch_assoc();
        $stmtOldSale->close();

        if ($oldSaleData) {
            $rollbackCustomer = $conn->prepare("UPDATE customers SET Orders = Orders - 1, Total_Spend = Total_Spend - ? WHERE Customer_ID = ?");
            $rollbackCustomer->bind_param("di", $oldSaleData['Total_Amount'], $oldSaleData['Customer_ID']);
            $rollbackCustomer->execute();
            $rollbackCustomer->close();
        }
    }

    // 3️⃣ Check stock availability
    foreach ($products as $product) {
        $productId = intval($product['product_id']);
        $quantity = intval($product['quantity']);

        $stockResult = $conn->query("SELECT Quantity, Product_Name FROM products WHERE Product_ID = $productId");
        $stockData = $stockResult->fetch_assoc();
        $availableStock = intval($stockData['Quantity']);
        $productName = $stockData['Product_Name'];

        if ($quantity > $availableStock) {
            header("Location: ../sales_add_edit.php?error_stock=1&product=" . urlencode($productName) . "&available=$availableStock&requested=$quantity" . ($saleId ? "&id=$saleId" : ""));
            exit;
        }
    }

    // 4️⃣ Insert or Update sale
    if ($saleId) {
        $conn->query("DELETE FROM sales_order_details WHERE Sale_ID = $saleId");

        $stmtUpdate = $conn->prepare("UPDATE sales SET Sale_Date=?, Delivery_Status=?, Payment_Method=?, Payment_Status=?, Notes=?, Total_Amount=?, Customer_ID=?, Updated_At=NOW() WHERE Sale_ID=?");
        $stmtUpdate->bind_param("ssssssii", $saleDate, $deliveryStatus, $paymentMethod, $paymentStatus, $notes, $totalAmount, $customerId, $saleId);
        $stmtUpdate->execute();
        $stmtUpdate->close();

        // ✅ Update customer orders & spend
        $updateCustomer = $conn->prepare("UPDATE customers SET Orders = Orders + 1, Total_Spend = Total_Spend + ? WHERE Customer_ID = ?");
        $updateCustomer->bind_param("di", $totalAmount, $customerId);
        $updateCustomer->execute();
        $updateCustomer->close();
    } else {
        $stmt = $conn->prepare("INSERT INTO sales (Sale_Date, Delivery_Status, Payment_Method, Payment_Status, Notes, Total_Amount, Customer_ID) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssi", $saleDate, $deliveryStatus, $paymentMethod, $paymentStatus, $notes, $totalAmount, $customerId);
        $stmt->execute();
        $saleId = $stmt->insert_id;
        $stmt->close();

        // ✅ Update customer orders & spend for new sale
        $updateCustomer = $conn->prepare("UPDATE customers SET Orders = Orders + 1, Total_Spend = Total_Spend + ? WHERE Customer_ID = ?");
        $updateCustomer->bind_param("di", $totalAmount, $customerId);
        $updateCustomer->execute();
        $updateCustomer->close();
    }

    // 5️⃣ Insert sale details & update stock
    foreach ($products as $product) {
        $productId = intval($product['product_id']);
        $quantity = intval($product['quantity']);
        $sellPrice = floatval($product['sale_price']);
        $totalCost = $quantity * $sellPrice;

        $stmtDetails = $conn->prepare("INSERT INTO sales_order_details (Sale_ID, Product_ID, Sale_Price, QTY, Total_Cost) VALUES (?, ?, ?, ?, ?)");
        $stmtDetails->bind_param("iidii", $saleId, $productId, $sellPrice, $quantity, $totalCost);
        $stmtDetails->execute();
        $stmtDetails->close();

        $updateStock = $conn->prepare("UPDATE products SET Quantity = Quantity - ? WHERE Product_ID = ?");
        $updateStock->bind_param("ii", $quantity, $productId);
        $updateStock->execute();
        $updateStock->close();
    }

    header("Location: ../sales_list.php?updated=1");
    exit;
}

// Handle delete
if (isset($_GET['deleteid'])) {
    $id = intval($_GET['deleteid']);

    // Optional: rollback stock or customer data on delete

    $conn->query("DELETE FROM sales_order_details WHERE Sale_ID = $id");

    $stmt = $conn->prepare("DELETE FROM sales WHERE Sale_ID = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: ../sales_list.php?deleted=1");
    }
    $stmt->close();
}
