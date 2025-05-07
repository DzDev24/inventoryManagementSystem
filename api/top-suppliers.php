<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

try {
    $query = "SELECT 
                s.Supplier_ID as id,
                s.Supplier_Name as name,
                s.Company_Name as company,
                s.Status as status,
                COUNT(ps.Product_ID) as product_count
              FROM supplier s
              LEFT JOIN product_supplier ps ON s.Supplier_ID = ps.Supplier_ID
              WHERE ps.Proposal_Status = 'Accepted'
              GROUP BY s.Supplier_ID
              ORDER BY product_count DESC
              LIMIT 5";
    
    $result = $conn->query($query);
    
    $suppliers = [];
    while ($row = $result->fetch_assoc()) {
        $suppliers[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'company' => $row['company'],
            'status' => $row['status'],
            'product_count' => (int)$row['product_count'] // Ensure count is integer
        ];
    }
    
    echo json_encode($suppliers);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Database error',
        'message' => $e->getMessage()
    ]);
}