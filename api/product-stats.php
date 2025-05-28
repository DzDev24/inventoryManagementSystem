<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../includes/db.php';

$response = [
    'totalProducts' => 0,
    'lowStockCount' => 0,
    'categoryCount' => 0,
    'supplierCount' => 0,
    'error' => null
];

try {
    // Total Products
    $result = $conn->query("SELECT COUNT(*) as total FROM products");
    $response['totalProducts'] = $result->fetch_assoc()['total'];

    // Low Stock Products
    $result = $conn->query("SELECT COUNT(*) as low_stock FROM products WHERE Quantity <= Minimum_Stock");
    $response['lowStockCount'] = $result->fetch_assoc()['low_stock'];

    // Total Categories
    $result = $conn->query("SELECT COUNT(*) as categories FROM category");
    $response['categoryCount'] = $result->fetch_assoc()['categories'];

    // Active Suppliers
    $result = $conn->query("SELECT COUNT(*) as suppliers FROM supplier WHERE Status = 'Available'");
    $response['supplierCount'] = $result->fetch_assoc()['suppliers'];

    echo json_encode($response);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Database error',
        'message' => $e->getMessage()
    ]);
}

