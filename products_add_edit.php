<?php
require_once "includes/db.php";


$isEdit = isset($_GET['id']);
$product_id = $isEdit ? intval($_GET['id']) : null;
$product = null;

if ($isEdit) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE Product_ID = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    // ✅ Add this block to fetch multiple suppliers
    $selected_suppliers = [];
    $stmt = $conn->prepare("SELECT Supplier_ID FROM product_supplier WHERE Product_ID = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $selected_suppliers[] = $row['Supplier_ID'];
    }
    $stmt->close();
}



// Fetch categories and suppliers
$categories = $conn->query("SELECT Category_ID, Category_Name FROM category");
$suppliers = $conn->query("SELECT Supplier_ID, Supplier_Name FROM supplier WHERE Status = 'Available'");
$units = $conn->query("SELECT Unit_ID, Unit_name, Unit_abrev FROM units");


?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title><?= $isEdit ? 'Edit Product' : 'Add Product' ?></title>
    <link href="css/vendor/bootstrap.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script data-search-pseudo-elements defer src="js/vendor/font-awesome.min.js" crossorigin="anonymous"></script>
    <script src="js/vendor/feather.min.js" crossorigin="anonymous"></script>
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
                                            <i data-feather="<?= $isEdit ? 'edit' : 'plus-circle' ?>"></i>
                                        </div>
                                        <?= $isEdit ? 'Edit Product' : 'Add Product' ?>
                                    </h1>
                                </div>
                                <div class="col-12 col-xl-auto mb-3">
                                    <a class="btn btn-sm btn-light text-primary" href="products_list.php">
                                        <i class="me-1" data-feather="arrow-left"></i>
                                        Back to Products List
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Main page content -->
                <!-- Main page content -->
                <div class="container-xl px-4 mt-4">

                    <form method="POST" enctype="multipart/form-data" action="backend/product_handler.php">
                        <div class="card mb-4">
                            <div class="card-header">
                                <?= $isEdit ? 'Edit Product Details' : 'Add New Product' ?>
                            </div>
                            <div class="card-body">

                                <?php if ($isEdit): ?>
                                    <input type="hidden" name="product_id" value="<?= $product_id ?>">
                                <?php endif; ?>

                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="product_name" value="<?= htmlspecialchars($product['Product_Name'] ?? '') ?>" required placeholder="Enter product name">
                                    </div>


                                    <div class="col-md-6">
                                        <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                        <input class="form-control" type="number" name="quantity" min="0" value="<?= htmlspecialchars($product['Quantity'] ?? '') ?>" required placeholder="Enter quantity">
                                    </div>
                                </div>

                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Buy Price (in DA)<span class="text-danger">*</span></label>
                                        <input class="form-control" type="number" name="buy_price" min="0" value="<?= htmlspecialchars($product['Buy_Price'] ?? '') ?>" required placeholder="Enter buy price in DA">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Sale Price (in DA)<span class="text-danger">*</span></label>
                                        <input class="form-control" type="number" name="sale_price" min="0" value="<?= htmlspecialchars($product['Sale_Price'] ?? '') ?>" required placeholder="Enter sale price in DA">
                                    </div>
                                </div>

                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Unit <span class="text-danger">*</span></label>
                                        <select class="form-select" name="unit_id" required>
                                            <option value="">-- Select Unit --</option>
                                            <?php while ($unit = $units->fetch_assoc()): ?>
                                                <option value="<?= $unit['Unit_ID'] ?>" <?= (isset($product['Unit_ID']) && $product['Unit_ID'] == $unit['Unit_ID']) ? 'selected' : '' ?>>
                                                    <?= $unit['Unit_name'] ?> (<?= $unit['Unit_abrev'] ?>)
                                                </option>

                                            <?php endwhile; ?>
                                        </select>
                                    </div>


                                    <div class="col-md-6">
                                        <label class="form-label">Minimum Stock <span class="text-danger">*</span></label>
                                        <input class="form-control" type="number" name="minimum_stock" min="0" value="<?= htmlspecialchars($product['Minimum_Stock'] ?? '') ?>" required placeholder="Minimum quantity for alerts">
                                    </div>
                                </div>

                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Category <span class="text-danger">*</span></label>
                                        <select class="form-select" name="category_id" required>
                                            <option value="">-- Select Category --</option>
                                            <?php mysqli_data_seek($categories, 0);
                                            while ($cat = $categories->fetch_assoc()): ?>
                                                <option value="<?= $cat['Category_ID'] ?>" <?= (isset($product['Category_ID']) && $product['Category_ID'] == $cat['Category_ID']) ? 'selected' : '' ?>>
                                                    <?= $cat['Category_Name'] ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                    <?php
$selectedSuppliers = [];
if ($isEdit) {
    $supplierResult = $conn->query("SELECT Supplier_ID FROM product_supplier WHERE Product_ID = $product_id");
    while ($row = $supplierResult->fetch_assoc()) {
        $selectedSuppliers[] = $row['Supplier_ID'];
    }
}
?>
<label class="form-label">Suppliers <span class="text-danger">*</span></label>
<select id="supplierSelect" name="supplier_ids[]" multiple required>
    <?php mysqli_data_seek($suppliers, 0); ?>
    <?php while ($sup = $suppliers->fetch_assoc()): ?>
        <option value="<?= $sup['Supplier_ID'] ?>" <?= in_array($sup['Supplier_ID'], $selected_suppliers ?? []) ? 'selected' : '' ?>>
    <?= $sup['Supplier_Name'] ?>
</option>

    <?php endwhile; ?>
</select>


                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="4" placeholder="Enter product description"><?= htmlspecialchars(trim($product['Description'] ?? '')) ?></textarea>
                                </div>

                                <div class="row gx-3 mb-3 align-items-center">


                                    <div class="row gx-3 mb-3 align-items-center">
                                        <!-- 9 cols: File input -->
                                        <div class="col-md-9">
                                            <label class="form-label">Product Image</label>
                                            <input class="form-control" type="file" name="image" id="imageInput" accept="image/*" onchange="previewImage(event)">
                                        </div>

                                        <!-- 3 cols: Image preview -->
                                        <div class="col-md-3 text-center">

                                            <img id="imagePreview"
                                                src="<?= isset($product['Media_ID']) ? ($conn->query("SELECT File_Path FROM media WHERE Media_ID = " . intval($product['Media_ID']))->fetch_assoc()['File_Path']) : '#' ?>"
                                                alt="Preview" style="max-height: 150px; <?= isset($product['Media_ID']) ? '' : 'display: none;' ?>"
                                                class="img-fluid rounded shadow-sm mt-3">
                                            <button type="button" id="removeImageBtn"
                                                class="btn btn-sm btn-outline-danger mt-2"
                                                style="display: none;"
                                                onclick="removeImage()">
                                                <i class="fas fa-times me-1"></i> Remove Image
                                            </button>
                                        </div>
                                    </div>


                                    <!-- ✅ Submit Button -->
                                    <div class="text-center">
                                        <button class="btn btn-primary px-5" type="submit">
                                            <i class="fas <?= $isEdit ? 'fa-save' : 'fa-plus-circle' ?> me-1"></i>
                                            <?= $isEdit ? 'Update Product' : 'Add Product' ?>
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
    <script src="js/vendor/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script>
        function previewImage(event) {
            const preview = document.getElementById('imagePreview');
            const removeBtn = document.getElementById('removeImageBtn');
            const file = event.target.files[0];

            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
                removeBtn.style.display = 'inline-block';
            }
        }

        function removeImage() {
            const input = document.getElementById('imageInput');
            const preview = document.getElementById('imagePreview');
            const removeBtn = document.getElementById('removeImageBtn');

            input.value = ''; // Clear file input
            preview.src = '#';
            preview.style.display = 'none';
            removeBtn.style.display = 'none';
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        new TomSelect('#supplierSelect', {
            placeholder: 'Select Supplier(s)',
            plugins: ['remove_button'],
            maxOptions: 1000
        });
    });
</script>


</body>

</html>