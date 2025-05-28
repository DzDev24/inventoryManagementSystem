<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

$activities = [];

// Get recent logins (last 5)
$query = "SELECT Username, Last_Login FROM users 
          WHERE Last_Login IS NOT NULL 
          ORDER BY Last_Login DESC LIMIT 5";
$result = $conn->query($query);

while ($row = $result->fetch_assoc()) {
    $activities[] = [
        'type' => 'login',
        'description' => $row['Username'] . ' logged in',
        'time' => formatTimeAgo($row['Last_Login'])
    ];
}

// Get recent sales (last 5)
$query = "SELECT s.Sale_ID, c.Name, s.Sale_Date, s.Total_Amount 
          FROM sales s
          LEFT JOIN customers c ON s.Customer_ID = c.Customer_ID
          ORDER BY s.Sale_Date DESC LIMIT 5";
$result = $conn->query($query);

while ($row = $result->fetch_assoc()) {
    $activities[] = [
        'type' => 'sale',
        'description' => 'Sale #' . $row['Sale_ID'] . ' to ' . $row['Name'] . ' for DA ' . number_format($row['Total_Amount']),
        'time' => formatTimeAgo($row['Sale_Date'])
    ];
}

// Get recent purchases (last 5)
$query = "SELECT p.Purchase_ID, s.Supplier_Name, p.Purchase_Date, p.Total_Amount 
          FROM purchases p
          LEFT JOIN supplier s ON p.Supplier_ID = s.Supplier_ID
          ORDER BY p.Purchase_Date DESC LIMIT 5";
$result = $conn->query($query);

while ($row = $result->fetch_assoc()) {
    $activities[] = [
        'type' => 'purchase',
        'description' => 'Purchase #' . $row['Purchase_ID'] . ' from ' . $row['Supplier_Name'] . ' for DA ' . number_format($row['Total_Amount']),
        'time' => formatTimeAgo($row['Purchase_Date'])
    ];
}

// Get recent product updates (last 5)
$query = "SELECT Product_ID, Product_Name, Updated_At 
          FROM products 
          WHERE Updated_At != Created_At
          ORDER BY Updated_At DESC LIMIT 5";
$result = $conn->query($query);

while ($row = $result->fetch_assoc()) {
    $activities[] = [
        'type' => 'product',
        'description' => 'Product updated: ' . $row['Product_Name'],
        'time' => formatTimeAgo($row['Updated_At'])
    ];
}

// Sort all activities by time (newest first)
usort($activities, function($a, $b) {
    return strtotime($b['time']) - strtotime($a['time']);
});

// Return only the 10 most recent activities
$recentActivities = array_slice($activities, 0, 10);

echo json_encode($recentActivities);

function formatTimeAgo($datetime) {
    $time = strtotime($datetime);
    $diff = time() - $time;
    
    if ($diff < 60) {
        return 'Just now';
    } elseif ($diff < 3600) {
        $mins = floor($diff / 60);
        return $mins . ' min' . ($mins > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    } else {
        return date('M j, Y', $time);
    }
}
?>