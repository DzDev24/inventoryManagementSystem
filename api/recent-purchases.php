<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

$query = "SELECT 
            p.Purchase_ID,
            GROUP_CONCAT(DISTINCT COALESCE(s.Supplier_Name, 'No Supplier') SEPARATOR ', ') as suppliers,
            COUNT(pd.Purchase_Details_ID) as products,
            p.Total_Amount as amount,
            p.Delivery_Status as status,
            p.Purchase_Date
          FROM purchases p
          LEFT JOIN purchases_details pd ON p.Purchase_ID = pd.Purchase_ID
          LEFT JOIN supplier s ON pd.Supplier_ID = s.Supplier_ID
          WHERE p.Accepted = 1
          GROUP BY p.Purchase_ID
          ORDER BY p.Purchase_Date DESC
          LIMIT 5";

$result = $conn->query($query);
$purchases = [];

while ($row = $result->fetch_assoc()) {
    $purchases[] = [
        'supplier' => $row['suppliers'] ?: 'No Supplier Specified',
        'products' => $row['products'] . ' items',
        'amount' => number_format($row['amount']) . ' DA',
        'status' => $row['status'],
        'date' => date('M j, Y', strtotime($row['Purchase_Date']))
    ];
}

echo json_encode($purchases);
