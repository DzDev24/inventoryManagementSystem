<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

try {
    $query = "SELECT 
                p.Product_ID as id,
                p.Product_Name as name,
                c.Category_Name as category,
                p.Quantity as quantity,
                p.Minimum_Stock as min_stock,
                u.Unit_name as unit
              FROM products p
              JOIN category c ON p.Category_ID = c.Category_ID
              LEFT JOIN units u ON p.Unit_ID = u.Unit_ID
              WHERE p.Quantity <= p.Minimum_Stock
              ORDER BY (p.Quantity / p.Minimum_Stock) ASC";
    
    $result = $conn->query($query);
    
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    
    echo json_encode($products);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Database error',
        'message' => $e->getMessage()
    ]);
}
