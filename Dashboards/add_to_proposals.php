<?php
session_start();
require_once "../includes/db.php";

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php");
    exit;
}

$productId = intval($_POST['product_id'] ?? 0);
$quantity = max(1, intval($_POST['quantity'] ?? 1));

if ($productId <= 0 || $quantity <= 0) {
    $_SESSION['error'] = "Invalid product or quantity.";
    header("Location: ../index.php");
    exit;
}

// Fetch product details using Buy_Price
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

// Get image path if available
$imagePath = '';
if (!empty($product['Media_ID'])) {
    $imgRes = $conn->query("SELECT File_Path FROM media WHERE Media_ID = " . $product['Media_ID']);
    $img = $imgRes->fetch_assoc();
    $imagePath = $img['File_Path'] ?? '';
}

// Initialize the proposals list
if (!isset($_SESSION['supply_proposals'])) {
    $_SESSION['supply_proposals'] = [];
}

// Check if product already in the proposals list
$found = false;
foreach ($_SESSION['supply_proposals'] as &$item) {
    if ($item['id'] === $productId) {
        $item['quantity'] += $quantity;
        $found = true;
        break;
    }
}

if (!$found) {
    $_SESSION['supply_proposals'][] = [
        'id' => $product['Product_ID'],
        'name' => $product['Product_Name'],
        'buy_price' => $product['Buy_Price'],
        'quantity' => $quantity,
        'image' => $imagePath
    ];
}

// Redirect back to referring page
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
?>