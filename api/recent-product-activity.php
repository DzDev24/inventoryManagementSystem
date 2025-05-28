<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

try {
    // Get recent product additions
    $query = "SELECT 
                Product_ID as product_id,
                Product_Name as product_name,
                Created_At as created_at,
                Updated_At as updated_at,
                'added' as type
              FROM products
              ORDER BY Created_At DESC
              LIMIT 5";
    
    $result = $conn->query($query);
    $activities = [];
    while ($row = $result->fetch_assoc()) {
        $activities[] = $row;
    }
    
    // Format the response
    $response = [];
    foreach ($activities as $activity) {
        $date = new DateTime($activity['created_at']);
        $now = new DateTime();
        $interval = $now->diff($date);
        
        $timeText = '';
        if ($interval->y > 0) {
            $timeText = $interval->y . ' year' . ($interval->y > 1 ? 's' : '') . ' ago';
        } elseif ($interval->m > 0) {
            $timeText = $interval->m . ' month' . ($interval->m > 1 ? 's' : '') . ' ago';
        } elseif ($interval->d > 0) {
            $timeText = $interval->d . ' day' . ($interval->d > 1 ? 's' : '') . ' ago';
        } elseif ($interval->h > 0) {
            $timeText = $interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ' ago';
        } else {
            $timeText = 'Just now';
        }
        
        $response[] = [
            'product_id' => $activity['product_id'],
            'product_name' => $activity['product_name'],
            'time' => $timeText,
            'description' => 'Product was added to inventory',
            'type' => 'added',
            'created_at' => $activity['created_at'],
            'updated_at' => $activity['updated_at']
        ];
    }
    
    // Get recent product updates
    $query = "SELECT 
                Product_ID as product_id,
                Product_Name as product_name,
                Updated_At as updated_at,
                Created_At as created_at
              FROM products
              WHERE Updated_At != Created_At
              ORDER BY Updated_At DESC
              LIMIT 5";
    
    $result = $conn->query($query);
    $updates = [];
    while ($row = $result->fetch_assoc()) {
        $updates[] = $row;
    }
    
    foreach ($updates as $update) {
        $date = new DateTime($update['updated_at']);
        $now = new DateTime();
        $interval = $now->diff($date);
        
        $timeText = '';
        if ($interval->y > 0) {
            $timeText = $interval->y . ' year' . ($interval->y > 1 ? 's' : '') . ' ago';
        } elseif ($interval->m > 0) {
            $timeText = $interval->m . ' month' . ($interval->m > 1 ? 's' : '') . ' ago';
        } elseif ($interval->d > 0) {
            $timeText = $interval->d . ' day' . ($interval->d > 1 ? 's' : '') . ' ago';
        } elseif ($interval->h > 0) {
            $timeText = $interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ' ago';
        } else {
            $timeText = 'Just now';
        }
        
        $response[] = [
            'product_id' => $update['product_id'],
            'product_name' => $update['product_name'],
            'time' => $timeText,
            'description' => 'Product details were updated',
            'type' => 'updated',
            'created_at' => $update['created_at'],
            'updated_at' => $update['updated_at']
        ];
    }
    
    // Sort all activities by date (newest first)
    usort($response, function($a, $b) {
        $timeA = strtotime($a['type'] === 'added' ? $a['created_at'] : $a['updated_at']);
        $timeB = strtotime($b['type'] === 'added' ? $b['created_at'] : $b['updated_at']);
        return $timeB - $timeA;
    });
    
    // Return only the 10 most recent activities
    $response = array_slice($response, 0, 10);
    
    echo json_encode($response);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Database error',
        'message' => $e->getMessage()
    ]);
}