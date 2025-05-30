<?php

require_once "./login_register/auth_session.php";

if ($_SESSION['user_role'] != 1 && $_SESSION['user_role'] != 3) {
    header("Location: ./unauthorized.php");
    exit;
}

?>

<?php
require_once "includes/db.php";

// Fetch Purchases
$sql = "SELECT p.*, 
               GROUP_CONCAT(DISTINCT s.Supplier_Name SEPARATOR ', ') AS Suppliers
        FROM purchases p
        LEFT JOIN purchases_details pd ON p.Purchase_ID = pd.Purchase_ID
        LEFT JOIN supplier s ON pd.Supplier_ID = s.Supplier_ID
        WHERE p.Accepted = 0
        GROUP BY p.Purchase_ID
        ORDER BY p.Purchase_Date DESC";



$result = $conn->query($sql);

$purchases = [];
while ($row = $result->fetch_assoc()) {
    $purchases[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Purchases List</title>
    <link href="css/vendor/datatables-style.min.css" rel="stylesheet" />
    <link href="css/vendor/bootstrap.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
    <script defer src="js/vendor/font-awesome.min.js"></script>
    <script src="js/vendor/feather.min.js"></script>

    <style>
        /* Center header columns */
        table#datatablesSimple thead th:nth-child(2),
        table#datatablesSimple thead th:nth-child(3),
        table#datatablesSimple thead th:nth-child(4),
        table#datatablesSimple thead th:nth-child(5),
        table#datatablesSimple thead th:nth-child(6),
        table#datatablesSimple thead th:nth-child(7),
        table#datatablesSimple thead th:nth-child(8),
        table#datatablesSimple thead th:nth-child(9) {
            text-align: center !important;

        }



        /* Center table body cells */
        table#datatablesSimple tbody td:nth-child(2),
        table#datatablesSimple tbody td:nth-child(3),
        table#datatablesSimple tbody td:nth-child(4),
        table#datatablesSimple tbody td:nth-child(5),
        table#datatablesSimple tbody td:nth-child(6),
        table#datatablesSimple tbody td:nth-child(7),
        table#datatablesSimple tbody td:nth-child(8),
        table#datatablesSimple tbody td:nth-child(9) {
            text-align: center !important;
            vertical-align: middle !important;
        }
    </style>

</head>

<body class="nav-fixed">
    <?php include 'includes/header.php'; ?>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav"><?php include 'includes/sidebar.php'; ?></div>
        <div id="layoutSidenav_content">
            <main>
                <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
                    <div class="container-fluid px-4">
                        <div class="page-header-content">
                            <div class="row align-items-center justify-content-between pt-3">
                                <div class="col-auto mb-3">
                                    <h1 class="page-header-title">
                                        <div class="page-header-icon"><i data-feather="shopping-cart"></i></div>
                                        Purchases List
                                    </h1>
                                </div>
                                <div class="col-12 col-xl-auto mb-3">
                                    <a class="btn btn-sm btn-light text-primary" href="purchases_add_edit.php">
                                        <i class="me-1" data-feather="plus-circle"></i>
                                        Add New Purchase
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
                                        <strong>Purchase Deleted:</strong> The purchase has been successfully removed.
                                    </div>
                                </div>
                            <?php elseif (isset($_GET['added']) && $_GET['added'] == '1'): ?>
                                <div class="alert alert-success alert-icon d-flex align-items-start" role="alert">
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <div class="alert-icon-aside">
                                        <i class="fas fa-plus-circle"></i>
                                    </div>
                                    <div class="alert-icon-content">
                                        <strong>Purchase Added:</strong> The purchase has been added.
                                    </div>
                                </div>
                            <?php elseif (isset($_GET['updated']) && $_GET['updated'] == '1'): ?>
                                <div class="alert alert-info alert-icon d-flex align-items-start" role="alert">
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <div class="alert-icon-aside">
                                        <i class="fas fa-save"></i>
                                    </div>
                                    <div class="alert-icon-content">
                                        <strong>Purchase Updated:</strong> Changes have been saved.
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php foreach ($purchases as $purchase): ?>
                                <?php include 'components/proposal_details_modal.php'; ?>
                            <?php endforeach; ?>

                            <table id="datatablesSimple" class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Purchase Date</th>
                                        <th>Suppliers</th>
                                        <th>Payment Method</th>
                                        <th>Total Amount</th>
                                        <th>View</th>

                                        <th>Accepted ?</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($purchases as $row): ?>
                                        <tr>
                                            <td><?= $row['Purchase_ID'] ?></td>
                                            <td><?= date('Y-m-d', strtotime($row['Purchase_Date'])) ?></td>

                                            <td>
                                                <div class="d-flex justify-content-center flex-column text-center">
                                                    <?php
                                                    $suppliers = explode(',', $row['Suppliers']);
                                                    foreach ($suppliers as $supplierName): ?>
                                                        <span class="badge bg-purple-soft text-purple mb-1"><?= trim($supplierName) ?></span>
                                                    <?php endforeach; ?>
                                                </div>
                                            </td>

                                            <td><?= htmlspecialchars($row['Payment_Method']) ?></td>

                                            <td><?= number_format($row['Total_Amount'], 2) ?> DA</td>

                                            <td style="width: 120px;">
                                                <a class="btn btn-datatable btn-icon btn-transparent-dark" title="View"
                                                    data-bs-toggle="modal" data-bs-target="#viewModalDetails<?= $row['Purchase_ID'] ?>">
                                                    <i data-feather="eye"></i>
                                                </a>

                                            </td>

                                            <td style="width: 120px;">

                                                <a class="btn btn-datatable btn-icon text-success" title="Accept" onclick="return confirmAccept(<?= $row['Purchase_ID'] ?>)">
                                                    <i data-feather="check-circle" style="width: 24px; height: 24px;"></i>
                                                </a>

                                                <a class="btn btn-datatable btn-icon text-danger" title="Reject" onclick="return confirmDelete(<?= $row['Purchase_ID'] ?>)">
                                                    <i data-feather="x-circle" style="width: 24px; height: 24px;"></i>
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
    <script src="js/vendor/bootstrap.bundle.min.js"></script>
    <script src="js/vendor/simple-datatables.min.js"></script>
    <script src="js/scripts.js"></script>

    <script>
        function confirmDelete(purchaseId) {
            Swal.fire({
                title: 'Are you sure to reject the proposal?',
                text: "This proposal will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Reject it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'backend/proposals_handler.php?rejectedid=' + purchaseId;
                }
            });
        }

        function confirmAccept(purchaseId) {
            Swal.fire({
                title: 'Are you sure you want to accept this proposal?',
                text: "This proposal will be marked as accepted.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6', // Blue for accept
                cancelButtonColor: '#d33', // Red for cancel
                confirmButtonText: 'Yes, Accept it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'backend/proposals_handler.php?acceptid=' + purchaseId;
                }
            });
        }

        window.addEventListener("DOMContentLoaded", () => {
            const datatablesSimple = document.getElementById("datatablesSimple");
            if (datatablesSimple) {
                new simpleDatatables.DataTable(datatablesSimple);
            }
        });
    </script>
</body>

</html>