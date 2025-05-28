<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

$months = isset($_GET['months']) ? (int)$_GET['months'] : 6;

$labels = [];
$data = [];

for ($i = $months - 1; $i >= 0; $i--) {
    $date = date('Y-m', strtotime("-$i months"));
    $labels[] = date('M Y', strtotime("-$i months"));
    
    $query = "SELECT SUM(Total_Amount) as revenue FROM sales WHERE DATE_FORMAT(Sale_Date, '%Y-%m') = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $date);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $data[] = $row['revenue'] ?? 0;
}

echo json_encode([
    'labels' => $labels,
    'data' => $data
]);
?>