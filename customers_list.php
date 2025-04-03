<?php
require_once "includes/db.php";

// Fetch customers with media and state info
$sql = "SELECT c.*, m.File_Path, st.State_Name 
        FROM customers c
        LEFT JOIN media m ON c.Media_ID = m.Media_ID
        LEFT JOIN states st ON c.State_ID = st.State_ID
        ORDER BY c.Updated_At DESC";
$result = $conn->query($sql);

$customers = [];
while ($row = $result->fetch_assoc()) {
    $customers[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Customers List</title>
    <link href="css/vendor/datatables-style.min.css" rel="stylesheet" />
    <link href="css/vendor/bootstrap.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
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
    table#datatablesSimple thead th:nth-child(10),
    table#datatablesSimple thead th:nth-child(11) {
        text-align: center !important;
    }


    
    /* Center table body cells */
    table#datatablesSimple tbody td:nth-child(5),  
    table#datatablesSimple tbody td:nth-child(6),  
    table#datatablesSimple tbody td:nth-child(7),  
    table#datatablesSimple tbody td:nth-child(8),  
    table#datatablesSimple tbody td:nth-child(9),  
    table#datatablesSimple tbody td:nth-child(10),
    table#datatablesSimple tbody td:nth-child(11){
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
                                    <div class="page-header-icon"><i data-feather="users"></i></div>
                                    Customers List
                                </h1>
                            </div>
                            <div class="col-12 col-xl-auto mb-3">
                                <a class="btn btn-sm btn-light text-primary" href="customers_add_edit.php">
                                    <i class="me-1" data-feather="plus-circle"></i>
                                    Add New Customer
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
                                <div class="alert-icon-aside"><i class="fas fa-trash-alt"></i></div>
                                <div class="alert-icon-content"><strong>Customer Deleted:</strong> The customer has been successfully removed.</div>
                            </div>
                        <?php elseif (isset($_GET['added']) && $_GET['added'] == '1'): ?>
                            <div class="alert alert-success alert-icon d-flex align-items-start" role="alert">
                                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                <div class="alert-icon-aside"><i class="fas fa-plus-circle"></i></div>
                                <div class="alert-icon-content"><strong>Customer Added:</strong> The customer has been added.</div>
                            </div>
                        <?php elseif (isset($_GET['updated']) && $_GET['updated'] == '1'): ?>
                            <div class="alert alert-info alert-icon d-flex align-items-start" role="alert">
                                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                <div class="alert-icon-aside"><i class="fas fa-save"></i></div>
                                <div class="alert-icon-content"><strong>Customer Updated:</strong> Changes have been saved.</div>
                            </div>
                        <?php endif; ?>

                        <?php foreach ($customers as $customer): 
                            include 'components/customer_details_modal.php';
                        endforeach; ?>

                        <table id="datatablesSimple" class="table-customers table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Address</th>
                                <th>Email</th> 
                                <th>Phone</th>                                                            
                                <th>State</th>                               
                                <th>Orders</th>
                                <th>Spending</th>
                                <th>Status</th>
                                <th style="width: 120px;">Updated At</th>
                                <th style="width: 118px;">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($customers as $row): ?>
                                <tr>
                                    <td><?= $row['Customer_ID'] ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-2">
                                                <img class="avatar-img img-fluid" src="<?= $row['File_Path'] ?: 'assets/img/illustrations/profiles/profile-1.png' ?>" alt="Customer Image">
                                            </div>
                                            <div>
                                                <a href="#viewCustomerModal<?= $row['Customer_ID'] ?>" class="fw-semibold no-link-style text-datk" style="color: inherit; text-decoration: none;" data-bs-toggle="modal" data-bs-target="#viewCustomerModal<?= $row['Customer_ID'] ?>">
                                                    <?= htmlspecialchars($row['Name']) ?>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($row['Shipping Address']) ?></td>
                                    <td class="text-nowrap"><?= htmlspecialchars($row['Email']) ?></td>
                                    <td><?= htmlspecialchars($row['Phone']) ?></td>
                                    
                                    
                                    <td class="text-center"><?= htmlspecialchars($row['State_Name']) ?></td>

                                    <td class="text-center"><?= $row['Orders'] ?></td>
                                    <td class="text-center"><?= $row['Total_Spend'] ?> DZD</td>



                                    <td class="text-center">
    <?php
    $statusClass = match ($row['Status']) {
        'Shipped' => 'bg-success',
        'Pending' => 'bg-warning',
        'Returned' => 'bg-danger',
        default => 'bg-secondary'
    };
    ?>
    <span class="badge <?= $statusClass ?> text-white"><?= $row['Status'] ?></span>
</td>


                                    <td>
                                        <div class="text-center" style="width: 120px;">
                                            <div class="fw-bold text-dark"><?= date('Y-m-d', strtotime($row['Updated_At'])) ?></div>
                                            <div class="text-muted small"><?= date('H:i:s', strtotime($row['Updated_At'])) ?></div>
                                        </div>
                                    </td>
                                    <td style="width: 118px;">
                                        <a class="btn btn-datatable btn-icon btn-transparent-dark" data-bs-toggle="modal" data-bs-target="#viewCustomerModal<?= $row['Customer_ID'] ?>" title="View"><i data-feather="eye"></i></a>
                                        <a href="customers_add_edit.php?id=<?= $row['Customer_ID'] ?>" class="btn btn-datatable btn-icon btn-transparent-dark" title="Edit"><i data-feather="edit"></i></a>
                                        <a class="btn btn-datatable btn-icon btn-transparent-dark" title="Delete" onclick="return confirmDelete(<?= $row['Customer_ID'] ?>)"><i data-feather="trash-2"></i></a>
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
    function confirmDelete(customerId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This customer will be permanently deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'backend/customers_handler.php?deleteid=' + customerId;
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
