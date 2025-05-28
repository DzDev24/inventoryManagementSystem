<?php
// api/custom/product-performance.php
header('Content-Type: application/json');
require_once '../../includes/db.php';

try {
    $query = "
        SELECT 
            p.Product_ID,
            p.Product_Name,
            SUM(sod.QTY) AS units_sold,
            SUM(sod.QTY * sod.Sale_Price) AS revenue,
            SUM(sod.QTY * p.Buy_Price) AS cost,
            ROUND(
                CASE 
                    WHEN SUM(sod.QTY * sod.Sale_Price) = 0 THEN 0
                    ELSE ((SUM(sod.QTY * sod.Sale_Price) - SUM(sod.QTY * p.Buy_Price)) / SUM(sod.QTY * sod.Sale_Price)) * 100
                END,
                2
            ) AS profit_margin
        FROM sales_order_details sod
        JOIN products p ON sod.Product_ID = p.Product_ID
        JOIN sales s ON sod.Sale_ID = s.Sale_ID
        GROUP BY p.Product_ID
        ORDER BY revenue DESC
        LIMIT 20
    ";

    $result = $conn->query($query);

    $performance = [];
    $total_units = 0;
    $total_revenue = 0;

    while ($row = $result->fetch_assoc()) {
        $performance[] = [
            'id' => $row['Product_ID'],
            'name' => $row['Product_Name'],
            'units_sold' => (int)$row['units_sold'],
            'revenue' => round((float)$row['revenue'], 2),
            'margin' => (float)$row['profit_margin']
        ];
        $total_units += (int)$row['units_sold'];
        $total_revenue += (float)$row['revenue'];
    }

    $summary = [
        'best_product' => $performance[0]['name'] ?? 'N/A',
        'worst_product' => end($performance)['name'] ?? 'N/A',
        'total_units' => $total_units,
        'total_revenue' => number_format($total_revenue, 2)
    ];

    echo json_encode([
        'summary' => $summary,
        'performance' => $performance
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Database error',
        'message' => $e->getMessage()
    ]);
}
