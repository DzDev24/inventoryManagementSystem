<?php

require_once "./login_register/auth_session.php";

if ($_SESSION['user_role'] != 1 && $_SESSION['user_role'] != 3) {
    header("Location: ./unauthorized.php");
    exit;
}

?>

<?php
require_once "includes/db.php";

// Query suppliers with media and state info
$sql = "SELECT s.*, m.File_Path, st.State_Name 
        FROM supplier s
        LEFT JOIN media m ON s.Media_ID = m.Media_ID
        LEFT JOIN states st ON s.State_ID = st.State_ID
        ORDER BY s.Updated_At DESC";
$result = $conn->query($sql);

$suppliers = [];
while ($row = $result->fetch_assoc()) {
    $suppliers[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Suppliers List</title>
    <link href="css/vendor/datatables-style.min.css" rel="stylesheet" />

    <link href="css/vendor/bootstrap.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/icon.svg" />
    <script data-search-pseudo-elements defer src="js/vendor/font-awesome.min.js" crossorigin="anonymous"></script>
    <script src="js/vendor/feather.min.js" crossorigin="anonymous"></script>
    <link href="css/styles.css" rel="stylesheet" />

    <style>
        /* Center header columns */
        table#datatablesSimple thead th:nth-child(5),
        table#datatablesSimple thead th:nth-child(6),
        table#datatablesSimple thead th:nth-child(7),
        table#datatablesSimple thead th:nth-child(8),
        table#datatablesSimple thead th:nth-child(9),
        table#datatablesSimple thead th:nth-child(10) {
            text-align: center !important;
        }



        /* Center table body cells */
        table#datatablesSimple tbody td:nth-child(5),
        table#datatablesSimple tbody td:nth-child(6),
        table#datatablesSimple tbody td:nth-child(7),
        table#datatablesSimple tbody td:nth-child(8),
        table#datatablesSimple tbody td:nth-child(9),
        table#datatablesSimple thead th:nth-child(10) {
            text-align: center !important;
            vertical-align: middle !important;
        }
    </style>
    <?php include 'includes/common_head_elements.php'; ?>

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
                                        <div class="page-header-icon"><i data-feather="truck"></i></div>
                                        Suppliers List
                                    </h1>
                                </div>
                                <div class="col-12 col-xl-auto mb-3">
                                    <a class="btn btn-sm btn-light text-primary" href="suppliers_add_edit.php">
                                        <i class="me-1" data-feather="plus-circle"></i>
                                        Add New Supplier
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
                                        <strong>Supplier Deleted:</strong> The supplier has been successfully removed.
                                    </div>
                                </div>
                            <?php elseif (isset($_GET['added']) && $_GET['added'] == '1'): ?>
                                <div class="alert alert-success alert-icon d-flex align-items-start" role="alert">
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <div class="alert-icon-aside">
                                        <i class="fas fa-plus-circle"></i>
                                    </div>
                                    <div class="alert-icon-content">
                                        <strong>Supplier Added:</strong> The supplier has been added.
                                    </div>
                                </div>
                            <?php elseif (isset($_GET['updated']) && $_GET['updated'] == '1'): ?>
                                <div class="alert alert-info alert-icon d-flex align-items-start" role="alert">
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <div class="alert-icon-aside">
                                        <i class="fas fa-save"></i>
                                    </div>
                                    <div class="alert-icon-content">
                                        <strong>Supplier Updated:</strong> Changes have been saved.
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php foreach ($suppliers as $supplier):
                                include 'components/supplier_details_modal.php';
                            endforeach; ?>

                            <table id="datatablesSimple" class="table-suppliers table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Supplier</th>
                                        <th>Address</th>
                                        <th>Email</th>
                                        <th>Password</th>
                                        <th>Phone</th>
                                        <th>State</th>
                                        <th>Status</th>
                                        <th>Updated At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php foreach ($suppliers as $row):
                                    ?>
                                        <tr>
                                            <td><?= $row['Supplier_ID'] ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar me-2"><img class="avatar-img img-fluid" src="<?= $row['File_Path'] ? $row['File_Path'] : 'assets/img/illustrations/profiles/profile-1.png"' ?>" alt="Supplier Image"></div>
                                                    <div>
                                                        <a href="#viewModalDetails<?= $row['Supplier_ID'] ?>" class="fw-semibold no-link-style" data-bs-toggle="modal" data-bs-target="#viewModalDetails<?= $row['Supplier_ID'] ?>">
                                                            <?= htmlspecialchars($row['Supplier_Name']) ?>
                                                        </a>
                                                        <div class="text-muted small"><?= htmlspecialchars($row['Company_Name']) ?></div>
                                                    </div>
                                                </div>
                                            </td>


                                            <td><?= htmlspecialchars($row['Address']) ?></td>
                                            <td class="text-nowrap"><?= htmlspecialchars($row['Email']) ?></td>
                                            <td><?= htmlspecialchars($row['Password']) ?></td>
                                            <td><?= htmlspecialchars($row['Phone']) ?></td>
                                            <td class="text-center"><?= htmlspecialchars($row['State_Name']) ?></td>
                                            <td class="text-center">
                                                <span class="badge bg-<?= $row['Status'] == 'Available' ? 'success' : 'secondary' ?> text-white"><?= $row['Status'] ?></span>
                                            </td>

                                            <td>
                                                <div class="text-center">
                                                    <div class="fw-bold text-dark"><?= date('Y-m-d', strtotime($row['Updated_At'])) ?></div>
                                                    <div class="text-muted small"><?= date('H:i:s', strtotime($row['Updated_At'])) ?></div>
                                                </div>
                                            </td>


                                            <td>

                                                <a class="btn btn-datatable btn-icon btn-transparent-dark" data-bs-toggle="modal" data-bs-target="#viewModalDetails<?= $row['Supplier_ID'] ?>" title="View"><i data-feather="eye"></i></a>
                                                <a href="suppliers_add_edit.php?id=<?= $row['Supplier_ID'] ?>" class="btn btn-datatable btn-icon btn-transparent-dark" title="Edit" onclick="event.stopPropagation();"><i data-feather="edit"></i></a>
                                                <a class="btn btn-datatable btn-icon btn-transparent-dark" title="Delete" onclick="event.stopPropagation(); return confirmDelete(<?= $row['Supplier_ID'] ?>)"><i data-feather="trash-2"></i></a>
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
        function confirmDelete(supplierId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This supplier will be permanently deleted.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'backend/suppliers_handler.php?deleteid=' + supplierId;
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