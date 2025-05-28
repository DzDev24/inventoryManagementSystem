<?php
header('Content-Type: application/json');
require_once '../../includes/db.php';

try {
    // Get product sales data
    $productsQuery = "SELECT 
    p.Product_ID,
    p.Product_Name,
    SUM(sod.QTY) as units_sold,
    SUM(sod.Total_Cost) as revenue,
    ROUND((SUM(sod.Total_Cost) - SUM(sod.QTY * p.Buy_Price)) / SUM(sod.Total_Cost) * 100, 2) as profit_margin
FROM products p
JOIN sales_order_details sod ON p.Product_ID = sod.Product_ID
JOIN sales s ON sod.Sale_ID = s.Sale_ID
GROUP BY p.Product_ID
ORDER BY revenue DESC";

    
    $productsResult = $conn->query($productsQuery);
    $products = [];
    
    while ($row = $productsResult->fetch_assoc()) {
        $products[] = $row;
    }

    // Get summary statistics
    $summary = [
        'best_seller' => $products[0]['Product_Name'] ?? 'N/A',
        'least_seller' => count($products) > 0 ? $products[count($products)-1]['Product_Name'] : 'N/A',
        'total_units' => array_sum(array_column($products, 'units_sold')),
        'total_revenue' => array_sum(array_column($products, 'revenue'))
    ];

    echo json_encode([
        'summary' => $summary,
        'products' => $products
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Database error',
        'message' => $e->getMessage()
    ]);
}