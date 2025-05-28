<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

$response = [];

// Monthly Revenue
$currentMonth = date('Y-m');
$prevMonth = date('Y-m', strtotime('-1 month'));

$query = "SELECT SUM(Total_Amount) as revenue FROM sales WHERE DATE_FORMAT(Sale_Date, '%Y-%m') = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $currentMonth);
$stmt->execute();
$result = $stmt->get_result();
$currentRevenue = $result->fetch_assoc()['revenue'] ?? 0;

$stmt->bind_param('s', $prevMonth);
$stmt->execute();
$result = $stmt->get_result();
$prevRevenue = $result->fetch_assoc()['revenue'] ?? 0;

$revenueChange = $prevRevenue ? (($currentRevenue - $prevRevenue) / $prevRevenue) * 100 : 0;

$response['monthlyRevenue'] = $currentRevenue;
$response['revenueChange'] = round($revenueChange, 2);

// New Signups (last 30 days)
$query = "SELECT COUNT(*) as signups FROM customers WHERE Created_At >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
$result = $conn->query($query);
$currentSignups = $result->fetch_assoc()['signups'];

$query = "SELECT COUNT(*) as signups FROM customers WHERE Created_At BETWEEN DATE_SUB(NOW(), INTERVAL 60 DAY) AND DATE_SUB(NOW(), INTERVAL 30 DAY)";
$result = $conn->query($query);
$prevSignups = $result->fetch_assoc()['signups'];

$signupsChange = $prevSignups ? (($currentSignups - $prevSignups) / $prevSignups) * 100 : ($currentSignups ? 100 : 0);

$response['newSignups'] = $currentSignups;
$response['signupsChange'] = round($signupsChange, 2);

// Inventory Value
$query = "SELECT SUM(Quantity * Buy_Price) as value FROM products";
$result = $conn->query($query);
$currentInventoryValue = $result->fetch_assoc()['value'];

// Note: For inventory change, you might want to track this differently as it doesn't change monthly like revenue
$response['inventoryValue'] = $currentInventoryValue;
$response['inventoryChange'] = 0; // You can implement proper tracking for this

// Low Stock Count
$query = "SELECT COUNT(*) as low_stock FROM products WHERE Quantity <= Minimum_Stock";
$result = $conn->query($query);
$response['lowStockCount'] = $result->fetch_assoc()['low_stock'];

echo json_encode($response);
?>