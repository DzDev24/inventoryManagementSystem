<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

$days = isset($_GET['days']) ? (int)$_GET['days'] : 30;

$query = "SELECT p.Product_Name as product_name, SUM(sod.Total_Cost) as total_sales
          FROM sales_order_details sod
          JOIN products p ON sod.Product_ID = p.Product_ID
          JOIN sales s ON sod.Sale_ID = s.Sale_ID
          WHERE s.Sale_Date >= DATE_SUB(NOW(), INTERVAL ? DAY)
          GROUP BY p.Product_ID
          ORDER BY total_sales DESC
          LIMIT 10";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $days);
$stmt->execute();
$result = $stmt->get_result();

$labels = [];
$data = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['product_name'];
    $data[] = (float)$row['total_sales'];
}

echo json_encode([
    'labels' => $labels,
    'data' => $data
]);

$conn->close();
?>