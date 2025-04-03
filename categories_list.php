<?php
require_once "includes/db.php";

$sql = "SELECT c.Category_ID, c.Category_Name, c.Description, COUNT(p.Product_ID) as ProductCount, c.Created_At, c.Updated_At 
        FROM category c 
        LEFT JOIN products p ON c.Category_ID = p.Category_ID 
        GROUP BY c.Category_ID
        ORDER BY c.Updated_At DESC";


$result = $conn->query($sql);

$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}


// Preload all products by category
$category_products = [];

foreach ($categories as $cat) {
    $cat_id = $cat['Category_ID'];

    $product_query = "
        SELECT p.*, u.Unit_abrev, u.Unit_name
        FROM products p
        LEFT JOIN units u ON p.Unit_ID = u.Unit_ID
        WHERE p.Category_ID = $cat_id
    ";

    $result_products = $conn->query($product_query);
    $products = [];

    while ($prod = $result_products->fetch_assoc()) {
        $products[] = $prod;
    }

    $category_products[$cat_id] = $products;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Categories List</title>
    <link href="css/vendor/datatables-style.min.css" rel="stylesheet" />
    <link href="css/vendor/bootstrap.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
    <script data-search-pseudo-elements defer src="js/vendor/font-awesome.min.js" crossorigin="anonymous"></script>
    <script src="js/vendor/feather.min.js" crossorigin="anonymous"></script>
    <style>
        
    table#datatablesSimple thead th:nth-child(3),  /* Category */
    table#datatablesSimple thead th:nth-child(4),  /* Products */
    table#datatablesSimple thead th:nth-child(5),  /* Updated At */
    table#datatablesSimple thead th:nth-child(6)   /* Actions */ {
        text-align: center !important;
    }

    table#datatablesSimple tbody td:nth-child(4),  /* Products */
    table#datatablesSimple tbody td:nth-child(6)   /* Actions */ {
        text-align: center !important;
        vertical-align: middle !important;
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
                    <div class="container-fluid px-4">
                        <div class="page-header-content">
                            <div class="row align-items-center justify-content-between pt-3">
                                <div class="col-auto mb-3">
                                    <h1 class="page-header-title">
                                        <div class="page-header-icon"><i data-feather="tag"></i></div>
                                        Categories List
                                    </h1>
                                </div>
                                <div class="col-12 col-xl-auto mb-3">
                                    <a class="btn btn-sm btn-light text-primary" href="categories_add_edit.php">
                                        <i class="me-1" data-feather="plus-circle"></i>
                                        Add New Category
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>


                <div class="container-fluid px-4">
                    <div class="card">
                        <div class="card-body">
                            <?php if (isset($_GET['deleted']) && $_GET['deleted'] == '1'): ?>
                                <div class="alert alert-success alert-icon d-flex align-items-start" role="alert">
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <div class="alert-icon-aside">
                                        <i class="fas fa-trash-alt"></i>
                                    </div>
                                    <div class="alert-icon-content">
                                        <strong>Category Deleted:</strong> The category has been successfully removed.
                                    </div>
                                </div>
                            <?php elseif (isset($_GET['added']) && $_GET['added'] == '1'): ?>
                                <div class="alert alert-success alert-icon d-flex align-items-start" role="alert">
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <div class="alert-icon-aside">
                                        <i class="fas fa-plus-circle"></i>
                                    </div>
                                    <div class="alert-icon-content">
                                        <strong>Category Added:</strong> <?= htmlspecialchars($_GET['message'] ?? 'The category has been added.') ?>
                                    </div>
                                </div>
                            <?php elseif (isset($_GET['updated']) && $_GET['updated'] == '1'): ?>
                                <div class="alert alert-info alert-icon d-flex align-items-start" role="alert">
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <div class="alert-icon-aside">
                                        <i class="fas fa-save"></i>
                                    </div>
                                    <div class="alert-icon-content">
                                        <strong>Category Updated:</strong> <?= htmlspecialchars($_GET['message'] ?? 'Changes have been saved.') ?>
                                    </div>
                                </div>
                            <?php endif; ?>


                            <?php

                            foreach ($categories as $category):

                                $products = $category_products[$category['Category_ID']] ?? [];
                                include 'components/category_products_modal.php';

                            endforeach; ?>

                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Description</th>
                                        <th>Category</th>
                                        <th>Products</th>
                                        <th>Updated At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($categories as $category): ?>

                                        <tr>
                                            <td><?= $category['Category_ID'] ?></td>
                                            <td class="text-nowrap"><?= htmlspecialchars($category['Description']) ?></td>

                                            <td>
                                            <div class="d-flex justify-content-center">
                                             <span class="badge bg-primary-soft text-primary fs-7"><?= $category['Category_Name'] ?></span>
                                             </div>  
                                            </td>

                                            <td class="text-nowrap">



<a href="#" class="text-decoration-none d-inline-flex align-items-center gap-1"
    data-bs-toggle="modal"
    data-bs-target="#productsModal<?= $category['Category_ID'] ?>">
    <i class="fas fa-box-open text-info small"></i>
    <span class="badge bg-info-soft text-info fw-semibold"><?= $category['ProductCount'] ?></span>
    <small class="text-muted">products</small>
</a>


</td>









                                            <td>
                                                <div class="text-center">
                                                    <div class="fw-bold text-dark"><?= date('Y-m-d', strtotime($category['Updated_At'])) ?></div>
                                                    <div class="text-muted small"><?= date('H:i:s', strtotime($category['Updated_At'])) ?></div>
                                                </div>
                                            </td>

                                            <td>

                                                <a href="categories_add_edit.php?id=<?= $category['Category_ID'] ?>"
                                                    class="btn btn-datatable btn-icon btn-transparent-dark"
                                                    title="Edit"
                                                    style="pointer-events: auto;"
                                                    onclick="event.stopPropagation();">
                                                    <i data-feather="edit"></i>
                                                </a>
                                                <a class="btn btn-datatable btn-icon btn-transparent-dark"
                                                    title="Delete"
                                                    onclick="event.stopPropagation(); return confirmDelete(<?= $category['Category_ID'] ?>)"
                                                    style="pointer-events: auto;">
                                                    <i data-feather="trash-2"></i>
                                                </a>
                                            </td>
                                        </tr>

                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>



            <?php include 'includes/footer.php'; ?>
        </div>
    </div>

    <script src="js/vendor/sweetalert2@11.js"></script>
    <script src="js/vendor/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/vendor/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>

    <script>
        function confirmDelete(categoryId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'backend/category_handler.php?deleteid=' + categoryId;
                }
            });
        }

        window.addEventListener("DOMContentLoaded", (event) => {
            // Simple-DataTables
            // https://github.com/fiduswriter/Simple-DataTables/wiki

            const datatablesSimple = document.getElementById("datatablesSimple");
            if (datatablesSimple) {
                new simpleDatatables.DataTable(datatablesSimple);
            }
        });

    </script>
</body>

</html>