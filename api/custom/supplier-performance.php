<?php
// api/custom/supplier-performance.php
header('Content-Type: application/json');
require_once '../../includes/db.php';

try {
    $query = "
        SELECT 
            s.Supplier_ID,
            s.Supplier_Name,
            s.Company_Name,
            s.Email,
            s.Phone,
            s.Status,
            COUNT(DISTINCT ps.Product_ID) AS product_count,
            MAX(p.Purchase_Date) AS last_delivery
        FROM supplier s
        LEFT JOIN product_supplier ps ON s.Supplier_ID = ps.Supplier_ID
        LEFT JOIN purchases p ON s.Supplier_ID = p.Supplier_ID
        GROUP BY s.Supplier_ID
    ";

    $result = $conn->query($query);

    $suppliers = [];
    $top_supplier = '';
    $max_products = 0;
    $total_products = 0;
    $active_suppliers = 0;

    while ($row = $result->fetch_assoc()) {
        $product_count = (int) $row['product_count'];
        $status = strtolower($row['Status']) === 'available' ? 'Available' : 'Unavailable';

        $suppliers[] = [
            'name' => $row['Supplier_Name'],
            'company' => $row['Company_Name'],
            'product_count' => $product_count,
            'last_delivery' => $row['last_delivery'] ? date('Y-m-d', strtotime($row['last_delivery'])) : '—',
            'email' => $row['Email'],
            'phone' => $row['Phone'],
            'status' => $status
        ];

        if ($product_count > $max_products) {
            $max_products = $product_count;
            $top_supplier = $row['Supplier_Name'];
        }

        $total_products += $product_count;
        if ($status === 'Available') $active_suppliers++;
    }

    $summary = [
        'top_supplier' => $top_supplier,
        'total_products' => $total_products,
        'active_suppliers' => $active_suppliers,
        'avg_products_per_supplier' => count($suppliers) > 0 ? $total_products / count($suppliers) : 0
    ];

    echo json_encode([
        'summary' => $summary,
        'suppliers' => $suppliers
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Database error',
        'message' => $e->getMessage()
    ]);
}
