<?php
require_once "includes/db.php";

// Fetch all products and media
$productsQuery = "SELECT p.Product_ID, p.Product_Name, p.Buy_Price, m.File_Path 
                  FROM products p
                  LEFT JOIN media m ON p.Media_ID = m.Media_ID";
$productsResult = $conn->query($productsQuery);
$allProducts = [];
while ($row = $productsResult->fetch_assoc()) {
    $allProducts[] = $row;
}

// Map products to suppliers
$productSuppliersQuery = "SELECT ps.Product_ID, s.Supplier_ID, s.Supplier_Name 
                          FROM product_supplier ps
                          INNER JOIN supplier s ON ps.Supplier_ID = s.Supplier_ID";
$productSupplierMap = [];
$result = $conn->query($productSuppliersQuery);
while ($row = $result->fetch_assoc()) {
    $productSupplierMap[$row['Product_ID']][] = $row;
}

$editingPurchase = false;
$editPurchaseData = null;
$editPurchaseProducts = [];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $purchaseId = intval($_GET['id']);
    $editingPurchase = true;

    // Fetch the purchase main data
    $stmt = $conn->prepare("SELECT * FROM purchases WHERE Purchase_ID = ?");
    $stmt->bind_param("i", $purchaseId);
    $stmt->execute();
    $editPurchaseData = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Fetch the purchased products
    $stmt = $conn->prepare("
        SELECT pd.*, p.Product_Name, m.File_Path
        FROM purchases_details pd
        JOIN products p ON pd.Product_ID = p.Product_ID
        LEFT JOIN media m ON p.Media_ID = m.Media_ID
        WHERE pd.Purchase_ID = ?
    ");
    $stmt->bind_param("i", $purchaseId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $editPurchaseProducts[] = $row;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title><?= $editingPurchase ? 'Edit Purchase' : 'Add Purchase' ?></title>
    <link href="css/vendor/bootstrap.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
    <script src="js/vendor/bootstrap.bundle.min.js"></script>
    <script src="js/vendor/font-awesome.min.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="js/vendor/feather.min.js" crossorigin="anonymous"></script>
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
</head>
<body class="nav-fixed">
<?php include 'includes/header.php'; ?>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <?php include 'includes/sidebar.php'; ?>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
                <div class="container-xl px-4">
                    <div class="page-header-content">
                        <div class="row align-items-center justify-content-between pt-3">
                            <div class="col-auto mb-3">
                            <h1 class="page-header-title">
    <div class="page-header-icon">
        <i data-feather="<?= $editingPurchase ? 'edit' : 'plus-circle' ?>"></i>
    </div>
    <?= $editingPurchase ? 'Edit Purchase' : 'Add New Purchase' ?>
</h1>
                            </div>
                            <div class="col-12 col-xl-auto mb-3">
                                <a class="btn btn-sm btn-light text-primary" href="purchases_list.php">
                                    <i class="me-1" data-feather="arrow-left"></i>
                                    Back to Purchase List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="container-xl px-4 mt-4">
                <form action="backend/purchases_handler.php" method="POST">
                <?php if (isset($_GET['id'])): ?>
        <input type="hidden" name="purchase_id" value="<?= intval($_GET['id']) ?>">
    <?php endif; ?>
                    <div class="card mb-4">
                    <div class="card-header">
    <?= $editingPurchase ? 'Edit Purchase Details' : 'Purchase Details' ?>
</div>

                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Purchase Date</label>
                                <input type="date" name="purchase_date" class="form-control" 
       value="<?= $editingPurchase ? date('Y-m-d', strtotime($editPurchaseData['Purchase_Date'])) : '' ?>" 
       required>
                            </div>

                            <div class="mb-3">
    <label class="form-label">Payment Method</label>
    <select name="payment_method" class="form-select" required>
    <option value="">-- Select Method --</option>
    <option value="Cash" <?= $editingPurchase && $editPurchaseData['Payment_Method'] == 'Cash' ? 'selected' : '' ?>>Cash</option>
    <option value="Bank Transfer" <?= $editingPurchase && $editPurchaseData['Payment_Method'] == 'Bank Transfer' ? 'selected' : '' ?>>Bank Transfer</option>
    <option value="Credit Card" <?= $editingPurchase && $editPurchaseData['Payment_Method'] == 'Credit Card' ? 'selected' : '' ?>>Credit Card</option>
    <option value="Cheque" <?= $editingPurchase && $editPurchaseData['Payment_Method'] == 'Cheque' ? 'selected' : '' ?>>Cheque</option>
</select>

</div>

<div class="row gx-3 mb-3">



<div class="col-md-6">
    <label class="form-label">Payment Status</label>
    <select name="payment_status" class="form-select" required>
    <option value="">-- Select Status --</option>
    <option value="Paid" <?= $editingPurchase && $editPurchaseData['Payment_Status'] == 'Paid' ? 'selected' : '' ?>>Paid</option>
    <option value="Partial" <?= $editingPurchase && $editPurchaseData['Payment_Status'] == 'Partial' ? 'selected' : '' ?>>Partial</option>
    <option value="Unpaid" <?= $editingPurchase && $editPurchaseData['Payment_Status'] == 'Unpaid' ? 'selected' : '' ?>>Unpaid</option>
</select>

</div>


<div class="col-md-6">
    <label class="form-label">Delivery Status</label>
    <select name="delivery_status" class="form-select" required>
    <option value="">-- Select Status --</option>
    <option value="Recieved" <?= $editingPurchase && $editPurchaseData['Delivery_Status'] == 'Recieved' ? 'selected' : '' ?>>Recieved</option>
    <option value="Pending" <?= $editingPurchase && $editPurchaseData['Delivery_Status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
    <option value="Canceled" <?= $editingPurchase && $editPurchaseData['Delivery_Status'] == 'Canceled' ? 'selected' : '' ?>>Canceled</option>
</select>
</div>

</div>


<div class="mb-3">
    <label class="form-label">Notes</label>
    <textarea name="notes" class="form-control" rows="3" placeholder="Additional information..."><?= $editingPurchase ? htmlspecialchars($editPurchaseData['Notes']) : '' ?></textarea>
</div>

                            <div class="mb-3">
                                <label class="form-label">Select Product</label>
                                <select class="form-select" id="productDropdown">
                                    <option value="">-- Select Product --</option>
                                    <?php foreach ($allProducts as $product): ?>
                                        <option value="<?= $product['Product_ID'] ?>"
        data-name="<?= htmlspecialchars($product['Product_Name']) ?>"
        data-price="<?= $product['Buy_Price'] ?>"
        data-image="<?= $product['File_Path'] ?>"
        <?= isset($productSupplierMap[$product['Product_ID']]) 
            ? "data-suppliers='" . htmlspecialchars(json_encode($productSupplierMap[$product['Product_ID']]), ENT_QUOTES, 'UTF-8') . "'" 
            : "" ?>>
    <?= htmlspecialchars($product['Product_Name']) ?>
</option>

                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div id="productList"></div>
                            <?php if ($editingPurchase): ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    <?php foreach ($editPurchaseProducts as $product): ?>
        (function() {
            const productList = document.getElementById('productList');
            const index = <?= $product['Product_ID'] ?>;
            const entryHTML = `
                <div class="product-entry">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <img src="<?= $product['File_Path'] ?: 'https://placehold.co/60x60' ?>" alt="<?= htmlspecialchars($product['Product_Name']) ?>">
                        </div>
                        <div class="col-md-3">
                            <input type="hidden" name="products[${index}][product_id]" value="<?= $product['Product_ID'] ?>">
                            <input type="text" class="form-control" value="<?= htmlspecialchars($product['Product_Name']) ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="products[${index}][quantity]" min="1" class="form-control" value="<?= $product['QTY'] ?>" required>
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control" value="<?= $product['Buy_Price'] ?>" readonly>
                            <input type="hidden" name="products[${index}][buy_price]" value="<?= $product['Buy_Price'] ?>">
                        </div>
                        
                        <div class="col-md-3 d-flex align-items-center gap-2">
    <input type="hidden" name="products[${index}][supplier_id]" value="<?= $product['Supplier_ID'] ?>">
    <button type="button" class="btn btn-outline-danger btn-sm remove-entry-btn" title="Remove this product">
        <i class="fas fa-times"></i>
    </button>
</div>

                    </div>
                </div>
            `;
            productList.insertAdjacentHTML('beforeend', entryHTML);
        })();
    <?php endforeach; ?>
});
</script>
<?php endif; ?>

                            <div class="text-end mt-4 mb-3">
                            <div class="d-flex justify-content-start mt-4 mb-3">
    <div class="border rounded px-4 py-2 bg-light shadow-sm text-start" style="min-width: 220px;">
        <h6 class="text-muted mb-1">Total Amount</h6>
        <h4 class="mb-0 text-success fw-bold d-flex align-items-center gap-2">
        <i class="fas fa-money-bill"></i>
            <span id="totalAmount">0.00</span>
        </h4>
</div>

</div>


</div>
                            <div class="text-center">
                                 
                            <button type="submit" class="btn btn-primary px-5">
    <i class="fas <?= $editingPurchase ? 'fa-save' : 'fa-plus-circle' ?> me-1"></i>
    <?= $editingPurchase ? 'Update Purchase' : 'Add Purchase' ?>
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

    productDropdown.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const productId = selectedOption.value;
        if (!productId) return;

        const productName = selectedOption.getAttribute('data-name');
        const productImage = selectedOption.getAttribute('data-image');
        const productPrice = selectedOption.getAttribute('data-price');
        const suppliersJSON = selectedOption.getAttribute('data-suppliers');
        const suppliers = suppliersJSON ? JSON.parse(suppliersJSON) : [];

        const supplierHTML = suppliers.length === 1 ?
            `<input type="hidden" name="products[${index}][supplier_id]" value="${suppliers[0].Supplier_ID}">
             <input type="text" class="form-control" value="${suppliers[0].Supplier_Name}" readonly>` :
            `<select name="products[${index}][supplier_id]" class="form-select" required>
                <option value="">-- Select Supplier --</option>
                ${suppliers.map(s => `<option value="${s.Supplier_ID}">${s.Supplier_Name}</option>`).join('')}
            </select>`;

        const entryHTML = `
        <div class="product-entry">
            <div class="row align-items-center">
                <div class="col-md-2">
                    <img src="${productImage}" alt="${productName}">
                </div>
                <div class="col-md-3">
                    <input type="hidden" name="products[${index}][product_id]" value="${productId}">
                    <input type="text" class="form-control" value="${productName}" readonly>
                </div>
                <div class="col-md-2">
                    <input type="number" name="products[${index}][quantity]" min="1" class="form-control" placeholder="Quantity" required>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" value="${productPrice}" readonly>
                    <input type="hidden" name="products[${index}][buy_price]" value="${productPrice}">
                </div>
                <!-- Supplier Dropdown + Remove Button -->
<div class="col-md-3 d-flex align-items-center gap-2">
    ${supplierHTML}
    <button type="button" class="btn btn-outline-danger btn-sm remove-entry-btn" title="Remove this product">
        <i class="fas fa-times"></i>
    </button>
</div>


        </div>`;

        productList.insertAdjacentHTML('beforeend', entryHTML);
        this.selectedIndex = 0;
        index++;
    });
</script>

<script>
// 🛠️ Make updateTotal() global
function updateTotal() {
    let total = 0;
    const entries = document.querySelectorAll('.product-entry');
    entries.forEach(entry => {
        const qtyInput = entry.querySelector('input[name*="[quantity]"]');
        const priceInput = entry.querySelector('input[name*="[buy_price]"]');
        const qty = parseFloat(qtyInput?.value || 0);
        const price = parseFloat(priceInput?.value || 0);
        total += qty * price;
    });
    document.getElementById('totalAmount').textContent = total.toLocaleString(undefined, {minimumFractionDigits: 2}) + " DZD";
}

document.addEventListener("DOMContentLoaded", function () {
    // Update total if quantity input changes
    document.getElementById('productList').addEventListener('input', function (event) {
        if (event.target.matches('input[name*="[quantity]"]')) {
            updateTotal();
        }
    });

    // Update total if product is removed
    document.getElementById('productList').addEventListener('click', function (event) {
        if (event.target.closest('.remove-entry-btn')) {
            const productEntry = event.target.closest('.product-entry');
            if (productEntry) {
                productEntry.remove();
                updateTotal(); // ✅ recalculates properly
            }
        }
    });

    updateTotal(); // initial load
});
</script>


<script src="js/vendor/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="js/scripts.js"></script>

<!-- Feather Icons -->
<script src="js/vendor/feather.min.js" crossorigin="anonymous"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        feather.replace();
    });
</script>


</body>
</html>



