<?php
session_start();
require_once "../includes/db.php";

$productId = intval($_POST['product_id'] ?? 0);
$quantity = intval($_POST['quantity'] ?? 1);

if ($productId <= 0 || $quantity <= 0) {
    header("Location: ../index.php");
    exit;
}

// Get product details
$stmt = $conn->prepare("SELECT Product_ID, Product_Name, Sale_Price FROM products WHERE Product_ID = ?");
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = "Product not found.";
    header("Location: ../index.php");
    exit;
}

$product = $result->fetch_assoc();

// Store in buy_now session
$_SESSION['buy_now'] = [
    'id' => $product['Product_ID'],
    'name' => $product['Product_Name'],
    'price' => $product['Sale_Price'],
    'quantity' => $quantity
];

// Redirect to checkout in buy now mode
header("Location: checkout.php?mode=buy_now");
exit;
?>