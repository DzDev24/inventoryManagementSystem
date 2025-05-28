<?php
header('Content-Type: application/json');
require_once '../../includes/db.php';

try {
    // Get summary statistics
    $summaryQuery = "SELECT 
        SUM(Total_Amount) as total_revenue,
        (SELECT SUM(Total_Amount) FROM sales WHERE MONTH(Sale_Date) = MONTH(CURRENT_DATE()) AND YEAR(Sale_Date) = YEAR(CURRENT_DATE())) as monthly_revenue,
        AVG(Total_Amount) as avg_order_value,
        MAX(Total_Amount) as highest_order
    FROM sales";
    
    $summaryResult = $conn->query($summaryQuery);
    $summary = $summaryResult->fetch_assoc();

    // Get monthly breakdown (last 12 months)
    $monthlyQuery = "SELECT 
        DATE_FORMAT(Sale_Date, '%Y-%m') as month,
        SUM(Total_Amount) as total_revenue,
        COUNT(*) as orders_count,
        AVG(Total_Amount) as avg_order_value
    FROM sales
    WHERE Sale_Date >= DATE_SUB(CURRENT_DATE(), INTERVAL 12 MONTH)
    GROUP BY DATE_FORMAT(Sale_Date, '%Y-%m')
    ORDER BY total_revenue DESC";
    
    $monthlyResult = $conn->query($monthlyQuery);
    $monthly_data = [];
    
    while ($row = $monthlyResult->fetch_assoc()) {
        $monthly_data[] = $row;
    }

    // Get payment methods breakdown
    $paymentQuery = "SELECT 
        Payment_Method as method,
        SUM(Total_Amount) as amount
    FROM sales
    GROUP BY Payment_Method";
    
    $paymentResult = $conn->query($paymentQuery);
    $payment_methods = [];
    
    while ($row = $paymentResult->fetch_assoc()) {
        $payment_methods[] = $row;
    }

    echo json_encode([
        'summary' => $summary,
        'monthly_data' => $monthly_data,
        'payment_methods' => $payment_methods
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Database error',
        'message' => $e->getMessage()
    ]);
}