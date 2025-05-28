<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

$query = "SELECT r.Role_Name, COUNT(u.User_ID) as count 
          FROM roles r 
          LEFT JOIN users u ON r.Role_ID = u.Role_ID ";

if ($filter === 'active') {
    $query .= " WHERE u.Last_Login >= DATE_SUB(NOW(), INTERVAL 30 DAY) ";
}

$query .= " GROUP BY r.Role_ID";
$result = $conn->query($query);

$labels = [];
$data = [];
$total = 0;

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['Role_Name'];
    $data[] = (int)$row['count'];
    $total += (int)$row['count'];
}

// Calculate percentages
$percentages = [];
foreach ($data as $count) {
    $percentages[] = $total > 0 ? round(($count / $total) * 100, 1) : 0;
}

echo json_encode([
    'labels' => $labels,
    'data' => $data,
    'percentages' => $percentages
]);
?>