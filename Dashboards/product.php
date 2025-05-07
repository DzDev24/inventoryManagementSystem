<?php
require_once "../includes/db.php";
include 'dashboard_header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../index.php");
    exit;
}

$id = intval($_GET['id']);

$stmt = $conn->prepare(
    "SELECT p.*, c.Category_Name, u.Unit_abrev, m.File_Path
     FROM products p
     LEFT JOIN category c ON p.Category_ID = c.Category_ID
     LEFT JOIN units u ON p.Unit_ID = u.Unit_ID
     LEFT JOIN media m ON p.Media_ID = m.Media_ID
     WHERE Product_ID = ?"
);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "Product not found.";
    exit;
}

$similar_stmt = $conn->prepare(
    "SELECT p.Product_ID, p.Product_Name, p.Sale_Price, m.File_Path
     FROM products p
     LEFT JOIN media m ON p.Media_ID = m.Media_ID
     WHERE p.Category_ID = ? AND p.Product_ID != ?
     LIMIT 4"
);
$similar_stmt->bind_param("ii", $product['Category_ID'], $id);
$similar_stmt->execute();
$similar_result = $similar_stmt->get_result();
$similar_products = $similar_result->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['Product_Name']) ?> - IMS-24</title>
    <link href="../css/vendor/bootstrap.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4e73df;
            --primary-light: #f8f9fc;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --border-radius: 0.35rem;
            --box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        
        .product-container {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 2rem;
            margin-top: 2rem;
        }
        
        .product-image {
            border-radius: var(--border-radius);
            object-fit: contain;
            background: white;
            padding: 1rem;
            border: 1px solid #e3e6f0;
            transition: transform 0.3s ease;
            max-height: 450px;
            width: 100%;
        }
        
        .product-image:hover {
            transform: scale(1.02);
        }
        
        .price-tag {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--success-color);
        }
        
        .category-badge {
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            padding: 0.35rem 0.65rem;
        }
        
        .action-btn {
            padding: 0.5rem 1.25rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.2s;
        }
        
        .action-btn:hover {
            transform: translateY(-1px);
        }
        
        .detail-card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }
        
        .detail-card-header {
            background: var(--primary-light);
            border-bottom: 1px solid #e3e6f0;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        .availability-badge {
            font-size: 0.85rem;
            padding: 0.35rem 0.65rem;
        }
        
        .similar-products-card {
            height: 100%;
        }

        .similar-products-card .card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.similar-products-card .card:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
}

.similar-products-card .card-title {
    font-size: 0.9rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
        
        @media (max-width: 768px) {
            .product-container {
                padding: 1rem;
            }
            
            .price-tag {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
<header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
                        <div class="container-xl px-4">
                            <div class="page-header-content pt-4">
                                <div class="row align-items-center justify-content-between">
                                    <div class="col-auto mt-4">
                                    <h1 class="page-header-title">
    <div class="page-header-icon"><i data-feather="shopping-bag"></i></div>
    <?= htmlspecialchars($product['Product_Name']) ?>
</h1>
<div class="page-header-subtitle">Product Details</div>
                                    </div>
                                </div>
                                <nav class="mt-4 rounded" aria-label="breadcrumb">
                                <ol class="breadcrumb px-3 py-2 rounded mb-0">
    <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
    <li class="breadcrumb-item"><a href="#">Products</a></li>
    <li class="breadcrumb-item active"><?= htmlspecialchars($product['Product_Name']) ?></li>
</ol>
                                </nav>
                            </div>
                        </div>
                    </header>
<main class="mt-n15">
<div class="container py-4">

    <!-- Main Product Container -->
    <div class="product-container position-relative">
        <div class="row">
            <!-- Image Column - Now with single larger image -->
            <div class="col-md-5 mb-4 mb-md-0">
                <div class="d-flex align-items-center justify-content-center bg-white p-3 rounded" style="height: 450px;">
                    <img src="../<?= $product['File_Path'] ?>" class="product-image img-fluid" alt="<?= htmlspecialchars($product['Product_Name']) ?>">
                </div>
            </div>

            <!-- Info Column -->
            <div class="col-md-7">
                <h2 class="mb-2 font-weight-bold"><?= htmlspecialchars($product['Product_Name']) ?></h2>
                <div class="d-flex align-items-center mb-3">
                    <span class="badge category-badge bg-primary-soft text-primary me-2"><?= $product['Category_Name'] ?></span>
                    <span class="text-muted small"><i class="fas fa-box-open me-1"></i> SKU: <?= $product['Product_ID'] ?></span>
                </div>
                
                <!-- Rating (placeholder) -->
                <div class="mb-3">
                    <span class="text-warning">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </span>
                    <span class="text-muted small ms-2">(24 reviews)</span>
                </div>
                
                <div class="mb-4">
                    <span class="price-tag"><?= number_format($product['Sale_Price'], 2) ?> DA</span>
                </div>
                
                <div class="stock-indicator position-absolute top-0 end-0 mt-3 me-3 text-end">
    <span class="badge availability-badge bg-<?= $product['Quantity'] > 0 ? 'success' : 'danger' ?>-soft text-<?= $product['Quantity'] > 0 ? 'success' : 'danger' ?> px-3 py-2 fs-6">
        <i class="fas fa-<?= $product['Quantity'] > 0 ? 'check' : 'times' ?>-circle me-1"></i>
        <?= $product['Quantity'] > 0 ? 'In Stock' : 'Out of Stock' ?>
    </span><br>
</div>

                
                <div class="mb-4">
                    <div class="input-group mb-3" style="width: 180px;">
                        <button class="btn btn-outline-secondary" type="button" id="decrement">-</button>
                        <input type="text" class="form-control text-center" value="1" id="quantity" style="min-width: 60px;">
                        <button class="btn btn-outline-secondary" type="button" id="increment">+</button>
                    </div>
                </div>
                
                <div class="d-grid gap-2 mb-4">
                <form action="buy_now.php" method="POST" class="m-0">
    <input type="hidden" name="product_id" value="<?= $product['Product_ID'] ?>">
    <input type="hidden" name="quantity" id="buy-now-quantity" value="1">
    <button type="submit" class="btn btn-sm btn-primary w-100">
        <i data-feather="credit-card" class="me-1"></i> Buy Now
    </button>
</form>

    <div class="d-grid">
    <form action="add_to_cart.php" method="POST" class="w-100 m-0">
    <input type="hidden" name="product_id" value="<?= $product['Product_ID'] ?>">
    <input type="hidden" name="quantity" id="add-to-cart-quantity" value="1">
    <button type="submit" class="btn btn-outline-primary action-btn w-100">
        <i class="fas fa-shopping-cart me-2"></i>Add to Cart
    </button>
</form>
</div>


</div>

                
                <div class="border-top pt-3">
                    <div class="d-flex align-items-center text-muted small mb-2">
                        <i class="fas fa-truck me-2"></i>
                        <span>Free shipping on orders over 5000 DA</span>
                    </div>
                    <div class="d-flex align-items-center text-muted small">
                        <i class="fas fa-shield-alt me-2"></i>
                        <span>30-day return policy</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Details Section -->
    <div class="row mt-4">
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="detail-card card">
                <div class="detail-card-header card-header py-3">
                    <ul class="nav nav-tabs card-header-tabs" id="productTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">Description</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs" type="button" role="tab">Specifications</button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="productTabsContent">
                        <div class="tab-pane fade show active" id="description" role="tabpanel">
                            <h5 class="mb-3">About this product</h5>
                            <p class="mb-4"><?= nl2br(htmlspecialchars($product['Description'] ?: 'This product has no detailed description yet.')) ?></p>
                            
                            <?php if (!empty($product['Features'])): ?>
                            <h5 class="mb-3">Key Features</h5>
                            <ul class="list-unstyled">
                                <?php 
                                $features = explode("\n", $product['Features']);
                                foreach ($features as $feature):
                                    if (trim($feature) != ''):
                                ?>
                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i><?= htmlspecialchars(trim($feature)) ?></li>
                                <?php 
                                    endif;
                                endforeach; 
                                ?>
                            </ul>
                            <?php endif; ?>
                        </div>
                        <div class="tab-pane fade" id="specs" role="tabpanel">
                            <h5 class="mb-3">Technical Specifications</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th width="30%" class="bg-light">Product Name</th>
                                            <td><?= htmlspecialchars($product['Product_Name']) ?></td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Category</th>
                                            <td><?= htmlspecialchars($product['Category_Name']) ?></td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Unit</th>
                                            <td><?= htmlspecialchars($product['Unit_abrev']) ?></td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Available Quantity</th>
                                            <td><?= $product['Quantity'] ?> <?= $product['Unit_abrev'] ?></td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Sale Price</th>
                                            <td><?= number_format($product['Sale_Price'], 2) ?> DA</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Similar Products -->
                <!-- Similar Products -->
                <div class="col-lg-4">
            <div class="detail-card similar-products-card card">
                <div class="detail-card-header card-header py-3">
                    <h6 class="m-0 font-weight-bold">You may also like</h6>
                </div>
                <div class="card-body">
                    <?php if (count($similar_products) > 0): ?>
                        <div class="row">
                            <?php foreach ($similar_products as $similar): ?>
                                <div class="col-6 mb-3">
                                    <a href="product.php?id=<?= $similar['Product_ID'] ?>" class="text-decoration-none">
                                        <div class="card h-100 border-0 shadow-sm">
                                            <img src="../<?= $similar['File_Path'] ?>" class="card-img-top p-2" alt="<?= htmlspecialchars($similar['Product_Name']) ?>" style="height: 120px; object-fit: contain;">
                                            <div class="card-body p-2">
                                                <h6 class="card-title text-dark mb-1"><?= htmlspecialchars($similar['Product_Name']) ?></h6>
                                                <p class="card-text text-primary mb-0"><?= number_format($similar['Sale_Price'], 2) ?> DA</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-box-open fa-2x text-muted mb-3"></i>
                            <p class="text-muted">No similar products found</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php include '../includes/footer.php'; ?>
    </div>
</div>
</main>

<script src="../js/vendor/bootstrap.bundle.min.js"></script>
<script src="../js/vendor/feather.min.js"></script>
<script>
    // Initialize feather icons
    feather.replace();
    
    // Quantity selector functionality
    document.getElementById('increment').addEventListener('click', function () {
    const quantityInput = document.getElementById('quantity');
    let current = parseInt(quantityInput.value);
    if (isNaN(current) || current < 1) current = 0;
    quantityInput.value = current + 1;
});

document.getElementById('decrement').addEventListener('click', function () {
    const quantityInput = document.getElementById('quantity');
    let current = parseInt(quantityInput.value);
    if (isNaN(current) || current <= 1) {
        quantityInput.value = 1;
    } else {
        quantityInput.value = current - 1;
    }
});

    // Tab functionality
    const tabElms = document.querySelectorAll('button[data-bs-toggle="tab"]');
    tabElms.forEach(tabElm => {
        tabElm.addEventListener('click', function (e) {
            e.preventDefault();
            const tab = new bootstrap.Tab(this);
            tab.show();
        });
    });
</script>


<script>
const visibleQty = document.getElementById('quantity');
const buyNowQty = document.getElementById('buy-now-quantity');
const addToCartQty = document.getElementById('add-to-cart-quantity');

function syncQuantities() {
    const value = visibleQty.value;
    if (buyNowQty) buyNowQty.value = value;
    if (addToCartQty) addToCartQty.value = value;
}

visibleQty.addEventListener('input', syncQuantities);
document.getElementById('increment').addEventListener('click', () => {
    visibleQty.value = parseInt(visibleQty.value || 1) + 1;
    syncQuantities();
});
document.getElementById('decrement').addEventListener('click', () => {
    visibleQty.value = Math.max(1, parseInt(visibleQty.value || 1) - 1);
    syncQuantities();
});
</script>



</body>
</html>