<?php
header('Content-Type: application/json');
require_once '../../includes/db.php';

try {
    // Get summary statistics
    $summaryQuery = "SELECT 
        COUNT(*) as total_purchases,
        SUM(CASE WHEN Delivery_Status = 'Recieved' THEN 1 ELSE 0 END) as purchases_completed,
        SUM(CASE WHEN Delivery_Status = 'Pending' THEN 1 ELSE 0 END) as purchases_pending,
        SUM(CASE WHEN Delivery_Status = 'Canceled' THEN 1 ELSE 0 END) as purchases_canceled,
        SUM(Total_Amount) as total_spent
    FROM purchases WHERE Accepted = 1";

    $summaryResult = $conn->query($summaryQuery);
    $summary = $summaryResult->fetch_assoc();

    // Get recent purchases with proper supplier join
    $purchasesQuery = "SELECT 
    p.Purchase_ID, 
    p.Purchase_Date, 
    p.Total_Amount, 
    p.Delivery_Status,
    IFNULL(s.Supplier_Name, 'No Supplier') as Supplier_Name,
    COUNT(pd.Product_ID) as products_count
FROM purchases p
LEFT JOIN supplier s ON p.Supplier_ID = s.Supplier_ID
LEFT JOIN purchases_details pd ON p.Purchase_ID = pd.Purchase_ID
WHERE p.Accepted = 1
GROUP BY p.Purchase_ID
ORDER BY p.Total_Amount DESC";

    $purchasesResult = $conn->query($purchasesQuery);
    $recent_purchases = [];

    while ($row = $purchasesResult->fetch_assoc()) {
        $recent_purchases[] = $row;
    }

    echo json_encode([
        'summary' => $summary,
        'recent_purchases' => $recent_purchases
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Database error',
        'message' => $e->getMessage()
    ]);
}
