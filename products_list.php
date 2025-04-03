<?php
require_once "includes/db.php";

$sql = "SELECT  p.Product_ID, p.Product_Name, p.Quantity, p.Buy_Price, p.Sale_Price,
                p.Minimum_Stock, p.Description, p.Updated_At, p.Created_At,
                c.Category_Name,
                s.Supplier_Name,
                m.File_Path,
                u.Unit_abrev, u.Unit_name
                FROM products p
                LEFT JOIN category c ON p.Category_ID = c.Category_ID
                LEFT JOIN supplier s ON p.Supplier_ID = s.Supplier_ID
                LEFT JOIN media m ON p.Media_ID = m.Media_ID
                LEFT JOIN units u ON p.Unit_ID = u.Unit_ID
                ORDER BY p.Updated_At DESC";

$result = $conn->query($sql);

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Products List</title>
    <link href="css/vendor/datatables-style.min.css" rel="stylesheet" />
    <link href="css/vendor/bootstrap.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
    <script data-search-pseudo-elements defer src="js/vendor/font-awesome.min.js" crossorigin="anonymous"></script>
    <script src="js/vendor/feather.min.js" crossorigin="anonymous"></script>

    <style>
    /* Center header columns */
    table#datatablesSimple thead th:nth-child(3),  /* Supplier */
    table#datatablesSimple thead th:nth-child(4),  /* Quantity */
    table#datatablesSimple thead th:nth-child(5),  /* Buy Price */
    table#datatablesSimple thead th:nth-child(6),  /* Sale Price */
    table#datatablesSimple thead th:nth-child(7),  /* Updated At */
    table#datatablesSimple thead th:nth-child(8)   /* Actions */ {
        text-align: center !important;
    }


    
    /* Center table body cells */
    table#datatablesSimple tbody td:nth-child(3),  /* Supplier */
    table#datatablesSimple tbody td:nth-child(4),  /* Quantity */
    table#datatablesSimple tbody td:nth-child(5),  /* Buy Price */
    table#datatablesSimple tbody td:nth-child(6),  /* Sale Price */
    table#datatablesSimple tbody td:nth-child(7),  /* Updated At */
    table#datatablesSimple tbody td:nth-child(8)   /* Actions */ {
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
                                        <div class="page-header-icon"><i data-feather="package"></i></div>
                                        Products List
                                    </h1>
                                </div>
                                <div class="col-12 col-xl-auto mb-3">

                                    <a class="btn btn-sm btn-light text-primary" href="products_add_edit.php">
                                        <i class="me-1" data-feather="user-plus"></i>
                                        Add New Product
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <!-- Main page content-->
                <div class="container-fluid px-4">
                    <div class="card">
                        <div class="card-body">

                            <!-- display alert messages here -->
                            <?php if (isset($_GET['deleted']) && $_GET['deleted'] == '1'): ?>
                                <div class="alert alert-success alert-icon d-flex align-items-start" role="alert">
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <div class="alert-icon-aside">
                                        <i class="fas fa-trash-alt"></i>
                                    </div>
                                    <div class="alert-icon-content">
                                        <strong>Product Deleted:</strong> The product has been successfully removed.
                                    </div>
                                </div>
                            <?php elseif (isset($_GET['added']) && $_GET['added'] == '1'): ?>
                                <div class="alert alert-success alert-icon d-flex align-items-start" role="alert">
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <div class="alert-icon-aside">
                                        <i class="fas fa-plus-circle"></i>
                                    </div>
                                    <div class="alert-icon-content">
                                        <strong>Product Added:</strong> The product has been added.
                                    </div>
                                </div>
                            <?php elseif (isset($_GET['updated']) && $_GET['updated'] == '1'): ?>
                                <div class="alert alert-info alert-icon d-flex align-items-start" role="alert">
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <div class="alert-icon-aside">
                                        <i class="fas fa-save"></i>
                                    </div>
                                    <div class="alert-icon-content">
                                        <strong>Product Updated:</strong> Changes have been saved.
                                    </div>
                                </div>
                            <?php endif; ?>
                            <!-- end of alert messages -->


                            <?php foreach ($products as $product):
                                include 'components/product_details_modal.php';
                            endforeach; ?>


                            <table id="datatablesSimple" class="table-products">
                                <thead>
                                    <tr>
                                        <th>ID</th>

                                        <th>Product</th>
                                        <!-- <th>Category</th> -->
                                        <th>Supplier</th>
                                        <th>Quantity</th>
                                        <th>Buy Price</th>
                                        <th>Sale Price</th>
                                        <th>Updated At</th>
                                        <!-- <th>Min. Stock</th> -->
                                        <th>Actions</th>

                                    </tr>
                                </thead>
                                <tbody>

                                    <?php foreach ($products as $row):
                                    ?>
                                        <tr>
                                            <td><?= $row['Product_ID'] ?></td>


                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="avatar avatar-xxl me-2 pt-0 pb-0">
                                                        <img src="<?= $row['File_Path'] ? '' . $row['File_Path'] : 'https://placehold.co/50'  ?>" alt="Product Image">
                                                    </div>
                                                    <div>
                                                        <!-- <div class="fw-semibold"><?= $row['Product_Name'] ?></div> -->
                                                        <a href="#viewModalDetails<?= $row['Product_ID'] ?>"
                                                            class="fw-semibold no-link-style"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#viewModalDetails<?= $row['Product_ID'] ?>">
                                                            <?= htmlspecialchars($row['Product_Name']) ?>
                                                        </a>

                                                        <span class="badge bg-primary-soft text-primary small"><?= $row['Category_Name'] ?></span>
                                                    </div>
                                                </div>
                                            </td>



                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <span class="badge bg-purple-soft text-purple"><?= $row['Supplier_Name'] ?></span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center align-items-baseline gap-1">
                                                    <strong><?= $row['Quantity'] ?></strong><small class="text-muted"><?= $row['Unit_abrev'] ?></small>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="d-flex justify-content-center align-items-baseline gap-1">
                                                    <strong><?= $row['Buy_Price'] ?></strong><small class="text-muted">DA</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center align-items-baseline gap-1">
                                                    <strong><?= $row['Sale_Price'] ?></strong><small class="text-muted">DA</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    <div class="fw-bold text-dark"><?= date('Y-m-d', strtotime($row['Updated_At'])) ?></div>
                                                    <div class="text-muted small"><?= date('H:i:s', strtotime($row['Updated_At'])) ?></div>
                                                </div>
                                            </td>

                                            <td>

                                                <a class="btn btn-datatable btn-icon btn-transparent-dark"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewModalDetails<?= $row['Product_ID'] ?>"
                                                    title="View"
                                                    style="pointer-events: auto;">
                                                    <i data-feather="eye"></i>
                                                </a>
                                                <a href="products_add_edit.php?id=<?= $row['Product_ID'] ?>"
                                                    class="btn btn-datatable btn-icon btn-transparent-dark"
                                                    title="Edit"
                                                    style="pointer-events: auto;"
                                                    onclick="event.stopPropagation();">
                                                    <i data-feather="edit"></i>
                                                </a>
                                                <a class="btn btn-datatable btn-icon btn-transparent-dark"
                                                    title="Delete"
                                                    onclick="event.stopPropagation(); return confirmDelete(<?= $row['Product_ID'] ?>)"
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
        function confirmDelete(productId) {
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
                    window.location.href = 'backend/product_handler.php?deleteid=' + productId;
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