<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

$days = isset($_GET['days']) ? (int)$_GET['days'] : 7;

$query = "SELECT s.Sale_ID, s.Sale_Date, s.Total_Amount, s.Delivery_Status AS status, s.Payment_Status AS payment_status,
                 c.Name as customer_name, c.Email as customer_email
          FROM sales s
          LEFT JOIN customers c ON s.Customer_ID = c.Customer_ID
          WHERE s.Sale_Date >= DATE_SUB(NOW(), INTERVAL ? DAY)
          ORDER BY s.Sale_Date DESC
          LIMIT 10";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $days);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

echo json_encode($orders);

$conn->close();
?>