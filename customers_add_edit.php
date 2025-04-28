<?php
require_once "includes/db.php";

$isEdit = isset($_GET['id']);
$customer = [
    'Name' => '',
    'Email' => '',
    'Phone' => '',
    'Shipping Address' => '',
    'State_ID' => '',
    'Status' => 'Pending',
    'Orders' => 0,
    'Total_Spend' => 0,
    'Media_ID' => null
];

if ($isEdit) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM customers WHERE Customer_ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $customer = $result->fetch_assoc();
    $stmt->close();
}

$states = $conn->query("SELECT State_ID, State_Name FROM states");
$mediaPath = '';
if (!empty($customer['Media_ID'])) {
    $mediaResult = $conn->query("SELECT File_Path FROM media WHERE Media_ID = " . intval($customer['Media_ID']));
    $mediaRow = $mediaResult->fetch_assoc();
    $mediaPath = $mediaRow['File_Path'] ?? '';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title><?= $isEdit ? 'Edit Customer' : 'Add Customer' ?></title>
    <link href="css/vendor/bootstrap.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
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
                                    <?= $isEdit ? 'Edit Customer' : 'Add Customer' ?>
                                </h1>
                            </div>
                            <div class="col-12 col-xl-auto mb-3">
                                <a class="btn btn-sm btn-light text-primary" href="customers_list.php">
                                    <i class="me-1" data-feather="arrow-left"></i>
                                    Back to Customer List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="container-xl px-4 mt-4">
                <form method="POST" enctype="multipart/form-data" action="backend/customers_handler.php">
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="customer_id" value="<?= $id ?>">
                    <?php endif; ?>

                    <div class="card mb-4">
                        <div class="card-header">
                            <?= $isEdit ? 'Edit Customer Details' : 'Add New Customer' ?>
                        </div>
                        <div class="card-body">
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="name" required placeholder="Enter full name" value="<?= htmlspecialchars($customer['Name']) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input class="form-control" type="email" name="email" required placeholder="e.g. user@example.com" pattern="[^@\s]+@[^@\s]+\.[^@\s]+" value="<?= htmlspecialchars($customer['Email']) ?>">
                                </div>
                            </div>

                            <div class="row gx-3 mb-3">
                            <div class="col-md-6">
    <label class="form-label">Password <span class="text-danger">*</span></label>
    <input class="form-control" type="text" name="password" required placeholder="Enter password"
           value="<?= $isEdit && isset($customer['Password']) ? htmlspecialchars($customer['Password']) : '' ?>">
</div>

<div class="col-md-6">
    <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
    <input class="form-control" type="text" name="confirm_password" required placeholder="Confirm password"
           value="<?= $isEdit && isset($customer['Password']) ? htmlspecialchars($customer['Password']) : '' ?>">
</div>

                            </div>

                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Phone <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="phone" required placeholder="e.g. +213123456789 or 0555512345" pattern="^(\+213|0)[0-9]{9}$" value="<?= htmlspecialchars($customer['Phone']) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Shipping Address <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="shipping_address" required placeholder="Enter shipping address" value="<?= htmlspecialchars($customer['Shipping Address']) ?>">
                                </div>
                            </div>

                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">State <span class="text-danger">*</span></label>
                                    <select class="form-select" name="state_id" required>
                                        <option value="">-- Select State --</option>
                                        <?php while ($row = $states->fetch_assoc()): ?>
                                            <option value="<?= $row['State_ID'] ?>" <?= $row['State_ID'] == $customer['State_ID'] ? 'selected' : '' ?>>
                                                <?= $row['State_Name'] ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select" name="status" required>
    <option value="Available" <?= $customer['Status'] === 'Available' ? 'selected' : '' ?>>Available</option>
    <option value="Unavailable" <?= $customer['Status'] === 'Unavailable' ? 'selected' : '' ?>>Unavailable</option>
</select>

                                </div>
                            </div>

                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Number of Orders</label>
                                    <input class="form-control" type="number" name="orders" value="<?= htmlspecialchars($customer['Orders']) ?>" min="0">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Total Spend (DZD)</label>
                                    <input class="form-control" type="number" name="total_spend" value="<?= htmlspecialchars($customer['Total_Spend']) ?>" min="0">
                                </div>
                            </div>

                            <div class="row gx-3 mb-3 align-items-center">
                                <div class="col-md-9">
                                    <label class="form-label">Customer Image</label>
                                    <input class="form-control" type="file" name="image" id="imageInput" accept="image/*" onchange="previewImage(event)">
                                </div>
                                <div class="col-md-3 text-center">
                                    <img id="imagePreview" src="<?= $mediaPath ?>" alt="Preview"
                                         style="max-height: 150px; <?= $mediaPath ? '' : 'display:none;' ?>"
                                         class="img-fluid rounded shadow-sm mt-3">
                                    <button type="button" id="removeImageBtn"
                                            class="btn btn-sm btn-outline-danger mt-2"
                                            style="display: none;"
                                            onclick="removeImage()">
                                        <i class="fas fa-times me-1"></i> Remove Image
                                    </button>
                                </div>
                            </div>

                            <div class="text-center">
                                <button class="btn btn-primary px-5" type="submit">
                                    <i class="fas <?= $isEdit ? 'fa-save' : 'fa-plus-circle' ?> me-1"></i>
                                    <?= $isEdit ? 'Update Customer' : 'Add Customer' ?>
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

        input.value = '';
        preview.src = '#';
        preview.style.display = 'none';
        removeBtn.style.display = 'none';
    }
</script>

</body>
</html>
