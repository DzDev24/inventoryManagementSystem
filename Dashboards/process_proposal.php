<?php
session_start();
require_once "../includes/db.php";

// Ensure supplier is logged in and has a proposal
if (!isset($_SESSION['supplier_id']) || (empty($_SESSION['supply_proposals']) && empty($_SESSION['propose_now']))) {
    header("Location: ../index.php");
    exit;
}

$supplierId = $_SESSION['supplier_id'];
$paymentMethod = trim($_POST['payment_method'] ?? '');
$notes = trim($_POST['notes'] ?? '');

// Determine payment status
$paymentStatus = 'Unpaid';
if ($paymentMethod === 'Bank Transfer' || $paymentMethod === 'Credit Card') {
    $paymentStatus = 'Paid';
}

// Determine source of products
$items = isset($_SESSION['propose_now']) ? [$_SESSION['propose_now']] : $_SESSION['supply_proposals'];

// Calculate total cost
$totalAmount = 0;
foreach ($items as $item) {
    $totalAmount += $item['buy_price'] * $item['quantity'];
}

// Insert into purchases table
$stmt = $conn->prepare("INSERT INTO purchases (Supplier_ID, Payment_Method, Payment_Status, Notes, Total_Amount, Purchase_Date, Created_At)
                        VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
$stmt->bind_param("isssd", $supplierId, $paymentMethod, $paymentStatus, $notes, $totalAmount);

if (!$stmt->execute()) {
    $_SESSION['error'] = "Failed to save proposal.";
    header("Location: submit_proposal.php");
    exit;
}

$purchaseId = $conn->insert_id;

// Insert each product into purchases_details and increase stock
foreach ($items as $item) {
    $productId = $item['id'];
    $quantity = $item['quantity'];
    $buyPrice = $item['buy_price'];
    $totalCost = $buyPrice * $quantity;

    // Insert into purchases_details
    $detailStmt = $conn->prepare("INSERT INTO purchases_details 
        (Purchase_ID, Product_ID, Buy_Price, QTY, Total_Cost, Status, Supplier_ID)
        VALUES (?, ?, ?, ?, ?, 'Received', ?)");
    $detailStmt->bind_param("iiiiii", $purchaseId, $productId, $buyPrice, $quantity, $totalCost, $supplierId);
    $detailStmt->execute();

    // Increase product stock
    $stockUpdate = $conn->prepare("UPDATE products SET Quantity = Quantity + ? WHERE Product_ID = ?");
    $stockUpdate->bind_param("ii", $quantity, $productId);
    $stockUpdate->execute();


$checkSupplier = $conn->prepare("SELECT 1 FROM product_supplier WHERE Product_ID = ? AND Supplier_ID = ?");
$checkSupplier->bind_param("ii", $productId, $supplierId);
$checkSupplier->execute();
$exists = $checkSupplier->get_result()->fetch_assoc();

if (!$exists) {
    $linkSupplier = $conn->prepare("INSERT INTO product_supplier (Product_ID, Supplier_ID) VALUES (?, ?)");
    $linkSupplier->bind_param("ii", $productId, $supplierId);
    $linkSupplier->execute();
}
}

// Clear proposal session
unset($_SESSION['supply_proposals']);
unset($_SESSION['propose_now']);

$_SESSION['success'] = "Your proposal has been submitted successfully.";

// Store alert
if (!isset($_SESSION['alerts'])) {
    $_SESSION['alerts'] = [];
}

$_SESSION['alerts'][] = [
    'icon' => 'package',
    'color' => 'info',
    'title' => 'Supply Proposal Submitted',
    'message' => "Proposal recorded (ID: $purchaseId)",
    'time' => date('H:i')
];

header("Location: supplier_dashboard.php");
exit;
?>
