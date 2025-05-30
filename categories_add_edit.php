<?php

require_once "./login_register/auth_session.php";

if ($_SESSION['user_role'] != 1 && $_SESSION['user_role'] != 3) {
    header("Location: ./unauthorized.php");
    exit;
}


// categories_add_edit.php nazim
// This file handles both adding and editing categories.
require_once "includes/db.php";

$isEdit = isset($_GET['id']);
$category = ['Category_Name' => '', 'Description' => ''];

if ($isEdit) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM category WHERE Category_ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $category = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title><?= $isEdit ? 'Edit Category' : 'Add Category' ?></title>
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
                                        <?= $isEdit ? 'Edit Category' : 'Add New Category' ?>
                                    </h1>
                                </div>
                                <div class="col-12 col-xl-auto mb-3">
                                    <a class="btn btn-sm btn-light text-primary" href="categories_list.php">
                                        <i class="me-1" data-feather="arrow-left"></i>
                                        Back to Categories List
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <div class="container-xl px-4 mt-4">
                    <form method="POST" action="backend/category_handler.php">
                        <div class="card mb-4">
                            <div class="card-header">
                                <?= $isEdit ? 'Edit Category Details' : 'Add New Category' ?>
                            </div>
                            <div class="card-body">
                                <?php if ($isEdit): ?>
                                    <input type="hidden" name="category_id" value="<?= $id ?>">
                                <?php endif; ?>

                                <div class="mb-3">
                                    <label class="form-label">Category Name <span class="text-danger">*</span></label>
                                    <input type="text" name="category_name" class="form-control" required value="<?= htmlspecialchars($category['Category_Name']) ?>" placeholder="Enter category name">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" rows="4" placeholder="Enter category description"><?= htmlspecialchars($category['Description']) ?></textarea>
                                </div>

                                <div class="text-center">
                                    <button class="btn btn-primary px-5" type="submit">
                                        <i class="fas <?= $isEdit ? 'fa-save' : 'fa-plus-circle' ?> me-1"></i>
                                        <?= $isEdit ? 'Update Category' : 'Add Category' ?>
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
</body>

</html>