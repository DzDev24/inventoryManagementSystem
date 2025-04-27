<?php
require_once "../includes/db.php";

header('Content-Type: application/json');

if (!isset($_GET['supplier_id']) || !is_numeric($_GET['supplier_id'])) {
    echo json_encode(['error' => 'Invalid supplier ID']);
    exit;
}

$supplierId = intval($_GET['supplier_id']);

$stmt = $conn->prepare("SELECT Product_ID, Product_Name, Buy_Price FROM products WHERE Supplier_ID = ?");
$stmt->bind_param("i", $supplierId);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode($products);
