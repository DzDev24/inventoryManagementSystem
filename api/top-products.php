<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

$days = intval($_GET['days'] ?? 30); // Default to 30 days

try {
    $query = "SELECT 
                p.Product_Name as label,
                SUM(sod.QTY) as total_sold
              FROM sales_order_details sod
              JOIN sales s ON sod.Sale_ID = s.Sale_ID
              JOIN products p ON sod.Product_ID = p.Product_ID
              WHERE s.Sale_Date >= DATE_SUB(NOW(), INTERVAL ? DAY)
              GROUP BY p.Product_ID
              ORDER BY total_sold DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $days);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $labels = [];
    $data = [];
    
    while ($row = $result->fetch_assoc()) {
        $labels[] = $row['label'];
        $data[] = $row['total_sold'];
    }
    
    echo json_encode([
        'labels' => $labels,
        'data' => $data
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Database error',
        'message' => $e->getMessage()
    ]);
}