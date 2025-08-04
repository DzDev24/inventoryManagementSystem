<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

$months = intval($_GET['months'] ?? 12); 

try {
    $endDate = new DateTime();
    $startDate = clone $endDate;
    $startDate->modify("-$months months");
    
    // Initialize data structure
    $results = [];
    $current = clone $startDate;
    
    while ($current <= $endDate) {
        $monthYear = $current->format('M Y');
        $results[$monthYear] = 0;
        $current->modify('+1 month');
    }
    
    
    $query = "SELECT 
                DATE_FORMAT(Created_At, '%b %Y') as month_year,
                COUNT(*) as count
              FROM products
              WHERE Created_At BETWEEN ? AND ?
              GROUP BY month_year
              ORDER BY Created_At";
    
   
    $stmt = $conn->prepare($query);
    
    
    $startDateStr = $startDate->format('Y-m-d 00:00:00');
    $endDateStr = $endDate->format('Y-m-d 23:59:59');
    
    
    $stmt->bind_param('ss', $startDateStr, $endDateStr);
    
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    
    while ($row = $result->fetch_assoc()) {
        if (isset($results[$row['month_year']])) {
            $results[$row['month_year']] = $row['count'];
        }
    }
    
    // Prepare response
    $labels = array_keys($results);
    $data = array_values($results);
    
    echo json_encode([
        'labels' => $labels,
        'data' => $data
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Database error',
        'message' => $e->getMessage()
    ]);
}