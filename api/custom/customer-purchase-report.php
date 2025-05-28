<?php
header('Content-Type: application/json');
require_once '../../includes/db.php';

try {
    // Get summary statistics
    $summaryQuery = "SELECT 
        COUNT(*) as total_customers,
        (SELECT COUNT(*) FROM customers WHERE DATE(Created_At) >= DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)) as new_customers
    FROM customers";
    
    $summaryResult = $conn->query($summaryQuery);
    $summary = $summaryResult->fetch_assoc();

    // Get top customer
    $topCustomerQuery = "SELECT 
        c.Name as name,
        SUM(s.Total_Amount) as total_spend
    FROM customers c
    JOIN sales s ON c.Customer_ID = s.Customer_ID
    GROUP BY c.Customer_ID
    ORDER BY total_spend DESC
    LIMIT 1";
    
    $topCustomerResult = $conn->query($topCustomerQuery);
    $topCustomer = $topCustomerResult->fetch_assoc();
    $summary['top_customer'] = $topCustomer['name'] ?? 'N/A';
    
    // Calculate average spend per customer
    $avgSpendQuery = "SELECT AVG(customer_totals.total_spend) as avg_spend
                      FROM (
                          SELECT SUM(s.Total_Amount) as total_spend
                          FROM customers c
                          LEFT JOIN sales s ON c.Customer_ID = s.Customer_ID
                          GROUP BY c.Customer_ID
                      ) as customer_totals";
    $avgSpendResult = $conn->query($avgSpendQuery);
    $summary['avg_spend'] = round($avgSpendResult->fetch_assoc()['avg_spend'] ?? 0, 2);

    // Get customer breakdown
    $customersQuery = "SELECT 
        c.Customer_ID,
        c.Name as name,
        c.Email as email,
        COUNT(s.Sale_ID) as orders,
        COALESCE(SUM(s.Total_Amount), 0) as total_spend,
        MAX(s.Sale_Date) as last_purchase,
        m.File_Path as avatar
    FROM customers c
    LEFT JOIN sales s ON c.Customer_ID = s.Customer_ID
    LEFT JOIN media m ON c.Media_ID = m.Media_ID
    GROUP BY c.Customer_ID
    ORDER BY total_spend DESC";
    
    $customersResult = $conn->query($customersQuery);
    $customers = [];
    
    while ($row = $customersResult->fetch_assoc()) {
        $customers[] = $row;
    }

    echo json_encode([
        'summary' => $summary,
        'customers' => $customers
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Database error',
        'message' => $e->getMessage()
    ]);
}