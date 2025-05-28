<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

// Get current month and year
$currentMonth = date('m');
$currentYear = date('Y');

// Monthly Revenue
$query = "SELECT COALESCE(SUM(Total_Amount), 0) as monthlyRevenue 
          FROM sales 
          WHERE MONTH(Sale_Date) = ? AND YEAR(Sale_Date) = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $currentMonth, $currentYear);
$stmt->execute();
$result = $stmt->get_result();
$monthlyRevenue = $result->fetch_assoc()['monthlyRevenue'];

// Orders Count
$query = "SELECT COUNT(*) as ordersCount 
          FROM sales 
          WHERE MONTH(Sale_Date) = ? AND YEAR(Sale_Date) = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $currentMonth, $currentYear);
$stmt->execute();
$result = $stmt->get_result();
$ordersCount = $result->fetch_assoc()['ordersCount'];

// Average Order Value
$avgOrderValue = $ordersCount > 0 ? $monthlyRevenue / $ordersCount : 0;

// Total Customers (replacing conversion rate)
$query = "SELECT COUNT(*) as totalCustomers FROM customers";
$result = $conn->query($query);
$totalCustomers = $result->fetch_assoc()['totalCustomers'];

echo json_encode([
    'monthlyRevenue' => (float)$monthlyRevenue,
    'ordersCount' => (int)$ordersCount,
    'avgOrderValue' => round($avgOrderValue, 2),
    'totalCustomers' => (int)$totalCustomers  // Changed from conversionRate to totalCustomers
]);

$conn->close();
?>