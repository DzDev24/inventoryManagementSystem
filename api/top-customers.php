<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

$query = "SELECT c.Customer_ID, c.Name as name, c.Email as email, 
                 COUNT(s.Sale_ID) as orders, 
                 COALESCE(SUM(s.Total_Amount), 0) as total_spend,
                 m.File_Path as avatar
          FROM customers c
          LEFT JOIN sales s ON c.Customer_ID = s.Customer_ID
          LEFT JOIN media m ON c.Media_ID = m.Media_ID
          GROUP BY c.Customer_ID
          ORDER BY total_spend DESC
          LIMIT 5";

$result = $conn->query($query);

$customers = [];
while ($row = $result->fetch_assoc()) {
    $customers[] = $row;
}

echo json_encode($customers);

$conn->close();
?>