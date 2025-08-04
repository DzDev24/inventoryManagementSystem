<?php

require_once "./login_register/auth_session.php";

if ($_SESSION['user_role'] != 1 && $_SESSION['user_role'] != 2) {
    header("Location: ./unauthorized.php");
    exit;
}

?>

<?php
require_once "includes/db.php";

// Fetch all products and media
$productsQuery = "SELECT p.Product_ID, p.Product_Name, p.Sale_Price, p.Quantity, u.Unit_abrev, m.File_Path 
                  FROM products p
                  LEFT JOIN media m ON p.Media_ID = m.Media_ID
                  LEFT JOIN units u ON p.Unit_ID = u.Unit_ID";

$productsResult = $conn->query($productsQuery);
$allProducts = [];
while ($row = $productsResult->fetch_assoc()) {
    $allProducts[] = $row;
}

// Fetch all customers
$customersQuery = "SELECT Customer_ID, Name FROM customers";
$customersResult = $conn->query($customersQuery);
$allCustomers = [];
while ($row = $customersResult->fetch_assoc()) {
    $allCustomers[] = $row;
}

$editingSale = false;
$editSaleData = null;
$editSaleProducts = [];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $saleId = intval($_GET['id']);
    $editingSale = true;

    // Fetch the sale main data
    $stmt = $conn->prepare("SELECT * FROM sales WHERE Sale_ID = ?");
    $stmt->bind_param("i", $saleId);
    $stmt->execute();
    $editSaleData = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Fetch the sold products
    $stmt = $conn->prepare("
    SELECT sod.*, p.Product_Name, p.Quantity AS Available_Quantity, u.Unit_abrev, m.File_Path
    FROM sales_order_details sod
    JOIN products p ON sod.Product_ID = p.Product_ID
    LEFT JOIN units u ON p.Unit_ID = u.Unit_ID
    LEFT JOIN media m ON p.Media_ID = m.Media_ID
    WHERE sod.Sale_ID = ?
");

    $stmt->bind_param("i", $saleId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $editSaleProducts[] = $row;
    }
    $stmt->close();
}
?>

<?php if (isset($_GET['error_stock'])): ?>
    <script src="js/vendor/sweetalert2@11.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: 'error',
                title: 'Stock Error',
                html: 'Not enough stock for <strong><?= htmlspecialchars($_GET['product']) ?></strong>.<br>Available: <strong><?= intval($_GET['available']) ?></strong>, Requested: <strong><?= intval($_GET['requested']) ?></strong>.<br><br>Please correct it before submitting.',
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK'
            });
        });
    </script>
<?php endif; ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title><?= $editingSale ? 'Edit Sale' : 'Add Sale' ?></title>
    <link href="css/vendor/bootstrap.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/icon.svg" />
    <script src="js/vendor/bootstrap.bundle.min.js"></script>
    <script src="js/vendor/font-awesome.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script src="js/vendor/feather.min.js"></script>
    <style>
        .product-entry {
            border: 1px solid #dee2e6;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 6px;
            background: #f8f9fa;
        }

        .product-entry img {
            height: 60px;
            width: auto;
        }
    </style>
    <?php include 'includes/common_head_elements.php'; ?>

</head>

<body class="nav-fixed">
    <?php include 'includes/header.php'; ?>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav"><?php include 'includes/sidebar.php'; ?></div>
        <div id="layoutSidenav_content">
            <main>
                <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
                    <div class="container-xl px-4">
                        <div class="page-header-content">
                            <div class="row align-items-center justify-content-between pt-3">
                                <div class="col-auto mb-3">
                                    <h1 class="page-header-title">
                                        <div class="page-header-icon"><i data-feather="<?= $editingSale ? 'edit' : 'plus-circle' ?>"></i></div>
                                        <?= $editingSale ? 'Edit Sale' : 'Add New Sale' ?>
                                    </h1>
                                </div>
                                <div class="col-12 col-xl-auto mb-3">
                                    <a class="btn btn-sm btn-light text-primary" href="sales_list.php">
                                        <i class="me-1" data-feather="arrow-left"></i> Back to Sales List
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <div class="container-xl px-4 mt-4">
                    <form action="backend/sales_handler.php" method="POST">
                        <?php if ($editingSale): ?>
                            <input type="hidden" name="sale_id" value="<?= intval($_GET['id']) ?>">
                        <?php endif; ?>

                        <div class="card mb-4">
                            <div class="card-header"><?= $editingSale ? 'Edit Sale Details' : 'Sale Details' ?></div>
                            <div class="card-body">

                                
                                <div class="mb-3">
                                    <label class="form-label">Sale Date</label>
                                    <input type="date" name="sale_date" class="form-control"
                                        value="<?= $editingSale ? date('Y-m-d', strtotime($editSaleData['Sale_Date'])) : '' ?>"
                                        required>
                                </div>

                               
                                <div class="mb-3">
                                    <label class="form-label">Select Customer</label>
                                    <select name="customer_id" class="form-select" required>
                                        <option value="">-- Select Customer --</option>
                                        <?php foreach ($allCustomers as $customer): ?>
                                            <option value="<?= $customer['Customer_ID'] ?>"
                                                <?= $editingSale && $editSaleData['Customer_ID'] == $customer['Customer_ID'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($customer['Name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                
                                <div class="mb-3">
                                    <label class="form-label">Payment Method</label>
                                    <select name="payment_method" class="form-select" required>
                                        <option value="">-- Select Method --</option>
                                        <option value="Cash" <?= $editingSale && $editSaleData['Payment_Method'] == 'Cash' ? 'selected' : '' ?>>Cash</option>
                                        <option value="Bank Transfer" <?= $editingSale && $editSaleData['Payment_Method'] == 'Bank Transfer' ? 'selected' : '' ?>>Bank Transfer</option>
                                        <option value="Credit Card" <?= $editingSale && $editSaleData['Payment_Method'] == 'Credit Card' ? 'selected' : '' ?>>Credit Card</option>
                                        <option value="Cheque" <?= $editingSale && $editSaleData['Payment_Method'] == 'Cheque' ? 'selected' : '' ?>>Cheque</option>
                                    </select>
                                </div>

                                
                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Payment Status</label>
                                        <select name="payment_status" class="form-select" required>
                                            <option value="">-- Select Status --</option>
                                            <option value="Paid" <?= $editingSale && $editSaleData['Payment_Status'] == 'Paid' ? 'selected' : '' ?>>Paid</option>
                                            <option value="Partial" <?= $editingSale && $editSaleData['Payment_Status'] == 'Partial' ? 'selected' : '' ?>>Partial</option>
                                            <option value="Unpaid" <?= $editingSale && $editSaleData['Payment_Status'] == 'Unpaid' ? 'selected' : '' ?>>Unpaid</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Delivery Status</label>
                                        <select name="delivery_status" class="form-select" required>
                                            <option value="">-- Select Status --</option>
                                            <option value="Sold" <?= $editingSale && $editSaleData['Delivery_Status'] == 'Sold' ? 'selected' : '' ?>>Sold</option>
                                            <option value="Pending" <?= $editingSale && $editSaleData['Delivery_Status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="Canceled" <?= $editingSale && $editSaleData['Delivery_Status'] == 'Canceled' ? 'selected' : '' ?>>Canceled</option>
                                        </select>
                                    </div>
                                </div>

                                
                                <div class="mb-3">
                                    <label class="form-label">Notes</label>
                                    <textarea name="notes" class="form-control" rows="3"><?= $editingSale ? htmlspecialchars($editSaleData['Notes']) : '' ?></textarea>
                                </div>

                               
                                <div class="mb-3">
                                    <label class="form-label">Select Product</label>
                                    <select class="form-select" id="productDropdown">
                                        <option value="">-- Select Product --</option>
                                        <?php foreach ($allProducts as $product): ?>

                                            <option value="<?= $product['Product_ID'] ?>"
                                                data-name="<?= htmlspecialchars($product['Product_Name']) ?>"
                                                data-price="<?= $product['Sale_Price'] ?>"
                                                data-image="<?= $product['File_Path'] ?>"
                                                data-available="<?= $product['Quantity'] ?>"
                                                data-unit="<?= htmlspecialchars($product['Unit_abrev']) ?>">
                                                <?= htmlspecialchars($product['Product_Name']) ?>
                                            </option>


                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div id="productList"></div>

                                <script>
                                    document.addEventListener("DOMContentLoaded", function() {
                                        <?php foreach ($editSaleProducts as $product): ?>
                                                (function() {
                                                    const productList = document.getElementById('productList');
                                                    const index = <?= $product['Product_ID'] ?>;
                                                    const productName = <?= json_encode($product['Product_Name']) ?>;
                                                    const productImage = <?= json_encode($product['File_Path'] ?: 'https://placehold.co/60x60') ?>;
                                                    const availableQty = <?= intval($product['Available_Quantity'] + $product['QTY']) ?>;
                                                    const salePrice = <?= floatval($product['Sale_Price']) ?>;
                                                    const unit = <?= json_encode($product['Unit_abrev']) ?>;

                                                    const entryHTML = `
            <div class="product-entry">
                <div class="row align-items-center">
                    <div class="col-md-2">
                        <img src="${productImage}" alt="${productName}" style="height: 60px;">
                    </div>
                    <div class="col-md-4">
                        <input type="hidden" name="products[${index}][product_id]" value="${index}">
                        <input type="text" class="form-control" value="${productName}" readonly>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="products[${index}][quantity]" min="1" max="${availableQty}"
                               class="form-control" placeholder="Available: ${availableQty} ${unit}"
                               value="<?= $product['QTY'] ?>" required>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" value="${salePrice.toFixed(2)} DZD" readonly>
                        <input type="hidden" name="products[${index}][sale_price]" value="${salePrice}">
                    </div>
                    <div class="col-md-2 d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-entry-btn" title="Remove product">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
                                                    productList.insertAdjacentHTML('beforeend', entryHTML);
                                                })();
                                        <?php endforeach; ?>

                                        updateTotal(); 
                                    });
                                </script>








                                
                                <div class="d-flex justify-content-start mt-4 mb-3">
                                    <div class="border rounded px-4 py-2 bg-light shadow-sm text-start" style="min-width: 220px;">
                                        <h6 class="text-muted mb-1">Total Amount</h6>
                                        <h4 class="mb-0 text-success fw-bold d-flex align-items-center gap-2">
                                            <i class="fas fa-money-bill"></i>
                                            <span id="totalAmount">0.00</span>
                                        </h4>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary px-5">
                                        <i class="fas <?= $editingSale ? 'fa-save' : 'fa-plus-circle' ?> me-1"></i>
                                        <?= $editingSale ? 'Update Sale' : 'Add Sale' ?>
                                    </button>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </main>
            <?php include 'includes/footer.php'; ?>
        </div>
    </div>


    <script>
        const productDropdown = document.getElementById('productDropdown');
        const productList = document.getElementById('productList');
        let index = 0;

        productDropdown.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const productId = selectedOption.value;
            if (!productId) return;

            const productName = selectedOption.getAttribute('data-name');
            const productImage = selectedOption.getAttribute('data-image');
            const productPrice = selectedOption.getAttribute('data-price');
            const available = selectedOption.getAttribute('data-available');
            const unit = selectedOption.getAttribute('data-unit');


            const entryHTML = `
    <div class="product-entry">
        <div class="row align-items-center">
            <div class="col-md-2">
                <img src="${productImage}" alt="${productName}" style="height: 60px;">
            </div>
            <div class="col-md-4">
                <input type="hidden" name="products[${index}][product_id]" value="${productId}">
                <input type="text" class="form-control" value="${productName}" readonly>
            </div>
            <div class="col-md-2">
                <input type="number" name="products[${index}][quantity]" min="1" max="${available}" 
                       class="form-control" placeholder="Available: ${available} ${unit}" required>
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" value="${productPrice} DZD" readonly>
                <input type="hidden" name="products[${index}][sale_price]" value="${productPrice}">
            </div>
            <div class="col-md-2 d-flex align-items-center gap-2">
                <button type="button" class="btn btn-outline-danger btn-sm remove-entry-btn" title="Remove product">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>`;

            productList.insertAdjacentHTML('beforeend', entryHTML);
            this.selectedIndex = 0;
            index++;
            updateTotal();
        });

        document.addEventListener("DOMContentLoaded", function() {
            productList.addEventListener('input', function(event) {
                if (event.target.matches('input[name*="[quantity]"]')) {
                    updateTotal();
                }
            });

            productList.addEventListener('click', function(event) {
                if (event.target.closest('.remove-entry-btn')) {
                    event.target.closest('.product-entry').remove();
                    updateTotal();
                }
            });
        });

        function updateTotal() {
            let total = 0;
            const entries = document.querySelectorAll('.product-entry');
            entries.forEach(entry => {
                const qtyInput = entry.querySelector('input[name*="[quantity]"]');
                const priceInput = entry.querySelector('input[name*="[sale_price]"]');
                const qty = parseFloat(qtyInput?.value || 0);
                const price = parseFloat(priceInput?.value || 0);
                total += qty * price;
            });
            document.getElementById('totalAmount').textContent = total.toLocaleString(undefined, {
                minimumFractionDigits: 2
            }) + " DZD";
        }
    </script>

    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            feather.replace();
        });
    </script>

    <script src="js/vendor/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>