<?php
// api/custom/sales-performance.php
header('Content-Type: application/json');
require_once '../../includes/db.php';

try {
    // Summary stats
    $summaryQuery = "
        SELECT 
            COUNT(*) AS total_sales,
            SUM(Total_Amount) AS total_revenue,
            AVG(Total_Amount) AS avg_sale_value
        FROM sales
    ";
    $summaryResult = $conn->query($summaryQuery)->fetch_assoc();

    // Top customer
    $topCustomerQuery = "
        SELECT c.Name, SUM(s.Total_Amount) AS total_spent
        FROM sales s
        JOIN customers c ON s.Customer_ID = c.Customer_ID
        GROUP BY s.Customer_ID
        ORDER BY total_spent DESC
        LIMIT 1
    ";
    $topCustomer = $conn->query($topCustomerQuery)->fetch_assoc();

    // Top sale
    $topSaleQuery = "
        SELECT Sale_ID, Total_Amount
        FROM sales
        ORDER BY Total_Amount DESC
        LIMIT 1
    ";
    $topSale = $conn->query($topSaleQuery)->fetch_assoc();

    // Detailed sales list
    $salesQuery = "
        SELECT s.Sale_ID, c.Name AS customer, s.Sale_Date, s.Total_Amount
        FROM sales s
        LEFT JOIN customers c ON s.Customer_ID = c.Customer_ID
        ORDER BY s.Total_Amount DESC
    ";
    $salesResult = $conn->query($salesQuery);

    $sales = [];
    while ($row = $salesResult->fetch_assoc()) {
        $sales[] = [
            'id' => $row['Sale_ID'],
            'customer' => $row['customer'] ?: 'Guest',
            'date' => $row['Sale_Date'],
            'amount' => floatval($row['Total_Amount'])
        ];
    }

    echo json_encode([
        'summary' => [
            'total_sales' => $summaryResult['total_sales'],
            'total_revenue' => number_format($summaryResult['total_revenue'], 2),
            'avg_sale_value' => number_format($summaryResult['avg_sale_value'], 2),
            'top_customer' => $topCustomer['Name'],
            'top_sale_id' => $topSale['Sale_ID']
        ],
        'sales' => $sales
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
