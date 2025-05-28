<?php
header('Content-Type: application/json');
require_once '../../includes/db.php';

try {
    // Get summary statistics
    $summaryQuery = "SELECT 
    COUNT(*) as total_orders,
    SUM(CASE WHEN Delivery_Status IN ('Completed', 'Sold') THEN 1 ELSE 0 END) as orders_completed,
    SUM(CASE WHEN Delivery_Status = 'Pending' THEN 1 ELSE 0 END) as orders_pending,
    SUM(CASE WHEN Delivery_Status = 'Canceled' THEN 1 ELSE 0 END) as orders_canceled
FROM sales";

    
    $summaryResult = $conn->query($summaryQuery);
    $summary = $summaryResult->fetch_assoc();

    // Get recent sales
    $salesQuery = "SELECT 
        s.Sale_ID, 
        s.Sale_Date, 
        s.Total_Amount, 
        s.Delivery_Status, 
        s.Payment_Status,
        c.Name as customer_name
    FROM sales s
    LEFT JOIN customers c ON s.Customer_ID = c.Customer_ID
    ORDER BY s.Total_Amount DESC
    LIMIT 15";
    
    $salesResult = $conn->query($salesQuery);
    $recent_sales = [];
    
    while ($row = $salesResult->fetch_assoc()) {
        $recent_sales[] = $row;
    }

    echo json_encode([
        'summary' => $summary,
        'recent_sales' => $recent_sales
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Database error',
        'message' => $e->getMessage()
    ]);
}