<?php
require_once "includes/db.php";

// Fetch Sales

        $sql = "SELECT s.*, 
               c.Name AS Customer_Name
        FROM sales s
        LEFT JOIN customers c ON s.Customer_ID = c.Customer_ID
        ORDER BY s.Sale_Date DESC";


$result = $conn->query($sql);

$sales = [];
while ($row = $result->fetch_assoc()) {
    $sales[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Sales List</title>
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
    table#datatablesSimple thead th:nth-child(9)
    {
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
    table#datatablesSimple tbody td:nth-child(9)
    {
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
                                    <div class="page-header-icon"><i data-feather="shopping-bag"></i></div>
                                    Sales List
                                </h1>
                            </div>
                            <div class="col-12 col-xl-auto mb-3">
                                <a class="btn btn-sm btn-light text-primary" href="sales_add_edit.php">
                                    <i class="me-1" data-feather="plus-circle"></i>
                                    Add New Sale
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
                                <strong>Sale Deleted:</strong> The sale has been successfully removed.
                            </div>
                        </div>
                    <?php elseif (isset($_GET['added']) && $_GET['added'] == '1'): ?>
                        <div class="alert alert-success alert-icon d-flex align-items-start" role="alert">
                            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                            <div class="alert-icon-aside">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                            <div class="alert-icon-content">
                                <strong>Sale Added:</strong> The sale has been added.
                            </div>
                        </div>
                    <?php elseif (isset($_GET['updated']) && $_GET['updated'] == '1'): ?>
                        <div class="alert alert-info alert-icon d-flex align-items-start" role="alert">
                            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                            <div class="alert-icon-aside">
                                <i class="fas fa-save"></i>
                            </div>
                            <div class="alert-icon-content">
                                <strong>Sale Updated:</strong> Changes have been saved.
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php foreach ($sales as $sale): ?>
    <?php include 'components/sale_details_modal.php'; ?>
<?php endforeach; ?>

                    <table id="datatablesSimple" class="table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Sale Date</th>
                            <th>Customer</th>
                            <th>Payment Method</th>
                            <th>Delivery Status</th>
                            <th>Payment Status</th>
                            <th>Total Amount</th>
                            <th>Updated At</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($sales as $row): ?>
                            <tr>
                                <td><?= $row['Sale_ID'] ?></td>
                                <td><?= date('Y-m-d', strtotime($row['Sale_Date'])) ?></td>
                                <td><?= htmlspecialchars($row['Customer_Name']) ?></td>
                                <td><?= htmlspecialchars($row['Payment_Method']) ?></td>

                                <td> <!-- Delivery Status -->
            <?php
            $deliveryBadge = match ($row['Delivery_Status']) {
                'Delivered' => 'bg-success',
                'Pending'   => 'bg-warning text-dark',
                'Canceled'  => 'bg-danger',
                default     => 'bg-secondary'
            };
            ?>
            <span class="badge <?= $deliveryBadge ?>"><?= $row['Delivery_Status'] ?></span>
        </td>

                                <td>
                                    <?php
                                    $paymentBadge = match ($row['Payment_Status']) {
                                        'Paid' => 'bg-success',
                                        'Partial' => 'bg-warning text-dark',
                                        'Unpaid' => 'bg-danger'
                                    };
                                    ?>
                                    <span class="badge <?= $paymentBadge ?>"><?= $row['Payment_Status'] ?></span>
                                </td>
                                <td><?= number_format($row['Total_Amount'], 2) ?> DA</td>
                                <td>
                                    <div class="text-center">
                                        <div class="fw-bold text-dark"><?= date('Y-m-d', strtotime($row['Updated_At'])) ?></div>
                                        <div class="text-muted small"><?= date('H:i:s', strtotime($row['Updated_At'])) ?></div>
                                    </div>
                                </td>
                                <td style="width: 120px;">
                                    <a class="btn btn-datatable btn-icon btn-transparent-dark" title="View"
                                       data-bs-toggle="modal" data-bs-target="#viewModalDetails<?= $row['Sale_ID'] ?>">
                                        <i data-feather="eye"></i>
                                    </a>
                                    <a href="sales_add_edit.php?id=<?= $row['Sale_ID'] ?>" class="btn btn-datatable btn-icon btn-transparent-dark" title="Edit">
                                        <i data-feather="edit"></i>
                                    </a>
                                    <a class="btn btn-datatable btn-icon btn-transparent-dark" title="Delete" onclick="return confirmDelete(<?= $row['Sale_ID'] ?>)">
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
<script src="js/vendor/bootstrap.bundle.min.js"></script>
<script src="js/vendor/simple-datatables.min.js"></script>
<script src="js/scripts.js"></script>

<script>
function confirmDelete(saleId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This sale will be permanently deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'backend/sales_handler.php?deleteid=' + saleId;
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

