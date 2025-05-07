<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

try {
    $query = "SELECT 
                c.Category_Name as name,
                COUNT(p.Product_ID) as product_count
              FROM category c
              LEFT JOIN products p ON c.Category_ID = p.Category_ID
              GROUP BY c.Category_ID
              ORDER BY product_count DESC";
    
    $result = $conn->query($query);
    
    $labels = [];
    $data = [];
    $total = 0;
    
    while ($category = $result->fetch_assoc()) {
        $labels[] = $category['name'];
        $data[] = $category['product_count'];
        $total += $category['product_count'];
    }
    
    // Calculate percentages
    $percentages = [];
    foreach ($data as $count) {
        $percentages[] = $total > 0 ? round(($count / $total) * 100, 1) : 0;
    }
    
    echo json_encode([
        'labels' => $labels,
        'data' => $data,
        'percentages' => $percentages
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Database error',
        'message' => $e->getMessage()
    ]);
}