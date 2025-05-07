<?php
session_start();
require_once "../includes/db.php";

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php");
    exit;
}

// Validate and sanitize inputs
$productId = intval($_POST['product_id'] ?? 0);
$quantity = max(1, intval($_POST['quantity'] ?? 1));

if ($productId <= 0 || $quantity <= 0) {
    $_SESSION['error'] = "Invalid product or quantity.";
    header("Location: ../index.php");
    exit;
}

// Fetch product info, including Buy_Price (what supplier earns)
$stmt = $conn->prepare("SELECT Product_ID, Product_Name, Buy_Price, Media_ID FROM products WHERE Product_ID = ?");
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = "Product not found.";
    header("Location: ../index.php");
    exit;
}

$product = $result->fetch_assoc();

// Optionally get image
$imagePath = '';
if (!empty($product['Media_ID'])) {
    $imgRes = $conn->query("SELECT File_Path FROM media WHERE Media_ID = " . $product['Media_ID']);
    $img = $imgRes->fetch_assoc();
    $imagePath = $img['File_Path'] ?? '';
}

// Store in `propose_now` session
$_SESSION['propose_now'] = [
    'id' => $product['Product_ID'],
    'name' => $product['Product_Name'],
    'buy_price' => $product['Buy_Price'],
    'quantity' => $quantity,
    'image' => $imagePath
];

// Redirect to proposal submission page
header("Location: submit_proposal.php?mode=propose_now");
exit;
?>