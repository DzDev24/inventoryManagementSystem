<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

$query = "SELECT 
            c.Customer_ID, 
            c.Name, 
            c.State_ID, 
            s.State_Name,
            COUNT(DISTINCT so.Sale_ID) as orders,
            SUM(sd.Total_Cost) as total_spend
          FROM customers c
          LEFT JOIN states s ON c.State_ID = s.State_ID
          LEFT JOIN sales so ON c.Customer_ID = so.Customer_ID
          LEFT JOIN sales_order_details sd ON so.Sale_ID = sd.Sale_ID
          GROUP BY c.Customer_ID
          HAVING total_spend > 0
          ORDER BY total_spend DESC
          LIMIT 5";

$result = $conn->query($query);
$customers = [];

while ($row = $result->fetch_assoc()) {
    $customers[] = [
        'name' => $row['Name'],
        'orders' => $row['orders'],
        'total_spend' => number_format($row['total_spend']) . ' DA',
        'location' => $row['State_Name'] ?? 'Unknown'
    ];
}

echo json_encode($customers);
?>