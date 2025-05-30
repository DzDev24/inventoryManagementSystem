<?php

require_once "./login_register/auth_session.php";

if ($_SESSION['user_role'] != 1 && $_SESSION['user_role'] != 3) {
    header("Location: ./unauthorized.php");
    exit;
}

?>

<?php
require_once "includes/db.php";

$isEdit = isset($_GET['id']);
$supplier = [
    'Supplier_Name' => '',
    'Email' => '',
    'Phone' => '',
    'Company_Name' => '',
    'Address' => '',
    'State_ID' => '',
    'Status' => 'Available',
    'Description' => '',
    'Media_ID' => null
];

if ($isEdit) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM supplier WHERE Supplier_ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $supplier = $result->fetch_assoc();
    $stmt->close();
}

$states = $conn->query("SELECT State_ID, State_Name FROM states");
$mediaPath = '';
if (!empty($supplier['Media_ID'])) {
    $mediaResult = $conn->query("SELECT File_Path FROM media WHERE Media_ID = " . intval($supplier['Media_ID']));
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
    <title><?= $isEdit ? 'Edit Supplier' : 'Add Supplier' ?></title>
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
                                        <?= $isEdit ? 'Edit Supplier' : 'Add Supplier' ?>
                                    </h1>
                                </div>
                                <div class="col-12 col-xl-auto mb-3">
                                    <a class="btn btn-sm btn-light text-primary" href="suppliers_list.php">
                                        <i class="me-1" data-feather="arrow-left"></i>
                                        Back to Supplier List
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <div class="container-xl px-4 mt-4">
                    <form method="POST" enctype="multipart/form-data" action="backend/suppliers_handler.php">
                        <?php if ($isEdit): ?>
                            <input type="hidden" name="supplier_id" value="<?= $id ?>">
                        <?php endif; ?>

                        <div class="card mb-4">
                            <div class="card-header">
                                <?= $isEdit ? 'Edit Supplier Details' : 'Add New Supplier' ?>
                            </div>
                            <div class="card-body">
                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Supplier Name <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="supplier_name" required placeholder="Enter supplier name" value="<?= htmlspecialchars($supplier['Supplier_Name']) ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Company Name <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="company_name" required placeholder="Enter company name" value="<?= htmlspecialchars($supplier['Company_Name']) ?>">
                                    </div>
                                </div>

                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Password <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="password" required placeholder="Enter password"
                                            value="<?= $isEdit && isset($supplier['Password']) ? htmlspecialchars($supplier['Password']) : '' ?>">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="confirm_password" required placeholder="Confirm password"
                                            value="<?= $isEdit && isset($supplier['Password']) ? htmlspecialchars($supplier['Password']) : '' ?>">
                                    </div>
                                </div>
                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input class="form-control" type="email" name="email" required placeholder="e.g. user@example.com" pattern="[^@\s]+@[^@\s]+\.[^@\s]+" value="<?= htmlspecialchars($supplier['Email']) ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="phone" required placeholder="e.g. +213123456758 or 0555512345" pattern="^(\+213|0)[0-9]{9}$" value="<?= htmlspecialchars($supplier['Phone']) ?>">
                                    </div>
                                </div>

                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Address <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="address" required placeholder="Enter address" value="<?= htmlspecialchars($supplier['Address']) ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">State <span class="text-danger">*</span></label>
                                        <select class="form-select" name="state_id" required>
                                            <option value="">-- Select State --</option>
                                            <?php while ($row = $states->fetch_assoc()): ?>
                                                <option value="<?= $row['State_ID'] ?>" <?= $row['State_ID'] == $supplier['State_ID'] ? 'selected' : '' ?>>
                                                    <?= $row['State_Name'] ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select" name="status" required>
                                        <option value="Available" <?= $supplier['Status'] === 'Available' ? 'selected' : '' ?>>Available</option>
                                        <option value="Unavailable" <?= $supplier['Status'] === 'Unavailable' ? 'selected' : '' ?>>Unavailable</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="3" placeholder="Optional description..."><?= htmlspecialchars($supplier['Description']) ?></textarea>
                                </div>

                                <div class="row gx-3 mb-3 align-items-center">
                                    <div class="col-md-9">
                                        <label class="form-label">Supplier Image</label>
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
                                        <?= $isEdit ? 'Update Supplier' : 'Add Supplier' ?>
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