<?php
// api/custom/category-performance.php
header('Content-Type: application/json');
require_once '../../includes/db.php';

try {
    $query = "
        SELECT 
            c.Category_ID,
            c.Category_Name,
            COUNT(DISTINCT p.Product_ID) AS product_count,
            SUM(sod.QTY * sod.Sale_Price) AS revenue,
            SUM(sod.QTY * p.Buy_Price) AS cost
        FROM category c
        LEFT JOIN products p ON c.Category_ID = p.Category_ID
        LEFT JOIN sales_order_details sod ON p.Product_ID = sod.Product_ID
        LEFT JOIN sales s ON sod.Sale_ID = s.Sale_ID
        GROUP BY c.Category_ID
    ";

    $result = $conn->query($query);

    $categories = [];
    $total_categories = 0;
    $top_revenue = 0;
    $top_margin = -999;
    $top_revenue_cat = '';
    $top_margin_cat = '';
    $max_products = 0;
    $max_products_cat = '';

    while ($row = $result->fetch_assoc()) {
        $product_count = (int) $row['product_count'];
        $revenue = (float) $row['revenue'];
        $cost = (float) $row['cost'];

        $margin = ($revenue > 0) ? (($revenue - $cost) / $revenue) * 100 : 0;

        $categories[] = [
            'category' => $row['Category_Name'],
            'product_count' => $product_count,
            'revenue' => round($revenue, 2),
            'avg_margin' => round($margin, 2)
        ];

        $total_categories++;

        if ($revenue > $top_revenue) {
            $top_revenue = $revenue;
            $top_revenue_cat = $row['Category_Name'];
        }

        if ($margin > $top_margin) {
            $top_margin = $margin;
            $top_margin_cat = $row['Category_Name'];
        }

        if ($product_count > $max_products) {
            $max_products = $product_count;
            $max_products_cat = $row['Category_Name'];
        }
    }

    $summary = [
        'total_categories' => $total_categories,
        'top_revenue_category' => $top_revenue_cat,
        'top_margin_category' => $top_margin_cat,
        'most_products_category' => $max_products_cat
    ];

    echo json_encode([
        'summary' => $summary,
        'categories' => $categories
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Database error',
        'message' => $e->getMessage()
    ]);
}
