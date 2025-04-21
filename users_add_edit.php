<?php
require_once "includes/db.php";

$isEdit = isset($_GET['id']);
$user = [
    'Username' => '',
    'Real_Name' => '',
    'Password' => '',
    'Email' => '',
    'Status' => 'Offline',
    'Role_ID' => '',
    'Media_ID' => null
];

if ($isEdit) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM users WHERE User_ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
}

$roles = $conn->query("SELECT Role_ID, Role_Name FROM roles");
$mediaPath = '';
if (!empty($user['Media_ID'])) {
    $mediaResult = $conn->query("SELECT File_Path FROM media WHERE Media_ID = " . intval($user['Media_ID']));
    $mediaRow = $mediaResult->fetch_assoc();
    $mediaPath = $mediaRow['File_Path'] ?? '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title><?= $isEdit ? 'Edit User' : 'Add User' ?></title>
    <link href="css/vendor/bootstrap.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
    <script defer src="js/vendor/font-awesome.min.js"></script>
    <script src="js/vendor/feather.min.js"></script>
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
                                    <div class="page-header-icon">
                                        <i data-feather="<?= $isEdit ? 'edit' : 'plus-circle' ?>"></i>
                                    </div>
                                    <?= $isEdit ? 'Edit User' : 'Add User' ?>
                                </h1>
                            </div>
                            <div class="col-12 col-xl-auto mb-3">
                                <a class="btn btn-sm btn-light text-primary" href="users_list.php">
                                    <i class="me-1" data-feather="arrow-left"></i>
                                    Back to User List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="container-xl px-4 mt-4">
                <form method="POST" enctype="multipart/form-data" action="backend/users_handler.php">
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="user_id" value="<?= $id ?>">
                    <?php endif; ?>

                    <div class="card mb-4">
                        <div class="card-header"><?= $isEdit ? 'Edit User Details' : 'Add New User' ?></div>
                        <div class="card-body">
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Username <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="username" required value="<?= htmlspecialchars($user['Username']) ?>" placeholder="Enter Username">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Real Name <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="real_name" required value="<?= htmlspecialchars($user['Real_Name']) ?>" placeholder="Enter Real Name">
                                </div>
                            </div>

                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input class="form-control" type="email" name="email" required placeholder="e.g. user@example.com" pattern="[^@\s]+@[^@\s]+\.[^@\s]+" value="<?= htmlspecialchars($user['Email']) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select" name="status" required>
                                        <option value="Online" <?= $user['Status'] === 'Online' ? 'selected' : '' ?>>Online</option>
                                        <option value="Offline" <?= $user['Status'] === 'Offline' ? 'selected' : '' ?>>Offline</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row gx-3 mb-3">
                            <div class="col-md-6">
    <label class="form-label">Password <span class="text-danger">*</span></label>
    <input class="form-control" type="text" name="password" required placeholder="Enter password"
           value="<?= $isEdit && isset($user['Password']) ? htmlspecialchars($user['Password']) : '' ?>">
</div>

<div class="col-md-6">
    <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
    <input class="form-control" type="text" name="confirm_password" required placeholder="Confirm password"
           value="<?= $isEdit && isset($user['Password']) ? htmlspecialchars($user['Password']) : '' ?>">
</div>
                            </div>

                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Role <span class="text-danger">*</span></label>
                                    <select class="form-select" name="role_id" required>
                                        <option value="">-- Select Role --</option>
                                        <?php while ($row = $roles->fetch_assoc()): ?>
                                            <option value="<?= $row['Role_ID'] ?>" <?= $row['Role_ID'] == $user['Role_ID'] ? 'selected' : '' ?>><?= $row['Role_Name'] ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">User Image</label>
                                    <input class="form-control" type="file" name="image" id="imageInput" accept="image/*" onchange="previewImage(event)">
                                </div>
                            </div>

                            <div class="row gx-3 mb-3">
                                <div class="col-md-3 offset-md-9 text-center">
                                    <img id="imagePreview" src="<?= $mediaPath ?>" alt="Preview"
                                         style="max-height: 150px; <?= $mediaPath ? '' : 'display:none;' ?>"
                                         class="img-fluid rounded shadow-sm mt-3">
                                    <button type="button" id="removeImageBtn" class="btn btn-sm btn-outline-danger mt-2" style="display: none;" onclick="removeImage()">
                                        <i class="fas fa-times me-1"></i> Remove Image
                                    </button>
                                </div>
                            </div>

                            <div class="text-center">
                                <button class="btn btn-primary px-5" type="submit">
                                    <i class="fas <?= $isEdit ? 'fa-save' : 'fa-plus-circle' ?> me-1"></i>
                                    <?= $isEdit ? 'Update User' : 'Add User' ?>
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

<script src="js/vendor/bootstrap.bundle.min.js"></script>
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
