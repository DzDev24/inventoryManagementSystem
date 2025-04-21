<?php
require_once "includes/db.php";

// Fetch users with media and role info
$sql = "SELECT u.*, m.File_Path, r.Role_Name 
        FROM users u
        LEFT JOIN media m ON u.Media_ID = m.Media_ID
        LEFT JOIN roles r ON u.Role_ID = r.Role_ID
        ORDER BY u.Created_At DESC";
$result = $conn->query($sql);

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Users List</title>
    <link href="css/vendor/datatables-style.min.css" rel="stylesheet" />
    <link href="css/vendor/bootstrap.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
    <script defer src="js/vendor/font-awesome.min.js"></script>
    <script src="js/vendor/feather.min.js"></script>
    <style>
    /* Center header columns */
    table#datatablesSimple thead th:nth-child(1),
    table#datatablesSimple thead th:nth-child(3),
    table#datatablesSimple thead th:nth-child(4),
    table#datatablesSimple thead th:nth-child(5),
    table#datatablesSimple thead th:nth-child(6),
    table#datatablesSimple thead th:nth-child(7),
    table#datatablesSimple thead th:nth-child(8),
    table#datatablesSimple thead th:nth-child(9),

    /* Center table body cells */
    table#datatablesSimple tbody td:nth-child(1),
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
                                    <div class="page-header-icon"><i data-feather="users"></i></div>
                                    Users List
                                </h1>
                            </div>
                            <div class="col-12 col-xl-auto mb-3">
                                <a class="btn btn-sm btn-light text-primary" href="users_add_edit.php">
                                    <i class="me-1" data-feather="plus-circle"></i>
                                    Add New User
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
                            <div class="alert alert-success alert-icon d-flex align-items-start">
                                <button class="btn-close" data-bs-dismiss="alert"></button>
                                <div class="alert-icon-aside"><i class="fas fa-trash-alt"></i></div>
                                <div class="alert-icon-content"><strong>User Deleted:</strong> Successfully removed.</div>
                            </div>
                        <?php elseif (isset($_GET['added']) && $_GET['added'] == '1'): ?>
                            <div class="alert alert-success alert-icon d-flex align-items-start">
                                <button class="btn-close" data-bs-dismiss="alert"></button>
                                <div class="alert-icon-aside"><i class="fas fa-plus-circle"></i></div>
                                <div class="alert-icon-content"><strong>User Added:</strong> New user added.</div>
                            </div>
                        <?php elseif (isset($_GET['updated']) && $_GET['updated'] == '1'): ?>
                            <div class="alert alert-info alert-icon d-flex align-items-start">
                                <button class="btn-close" data-bs-dismiss="alert"></button>
                                <div class="alert-icon-aside"><i class="fas fa-save"></i></div>
                                <div class="alert-icon-content"><strong>User Updated:</strong> Changes saved.</div>
                            </div>
                        <?php endif; ?>

                        <?php foreach ($users as $user): ?>
                            <?php include 'components/users_details_modal.php'; ?>
                        <?php endforeach; ?>

                        <table id="datatablesSimple" class="table-customers table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Email</th>
                                <th>Password</th>
                                <th>Status</th>
                                <th>Role</th>
                                <th>Last Login</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($users as $row): ?>
                                <tr>
                                    <td><?= $row['User_ID'] ?></td>
                                    <td>
                                    <div class="d-flex align-items-center">
                                                    <div class="avatar me-2"><img class="avatar-img img-fluid" src="<?= $row['File_Path'] ? $row['File_Path'] : 'assets/img/illustrations/profiles/profile-1.png"' ?>" alt="Supplier Image"></div>
                                                    <div>
                                                        <a href="#viewModalDetails<?= $row['User_ID'] ?>" class="fw-semibold text-reset text-decoration-none" data-bs-toggle="modal" data-bs-target="#viewModalDetails<?= $row['User_ID'] ?>">

                                                            <?= htmlspecialchars($row['Real_Name']) ?>
                                                        </a>
                                                        <div class="text-muted small"><?= htmlspecialchars($row['Username']) ?></div>
                                                    </div>
                                                </div>
                                    </td>
                                    <td><?= htmlspecialchars($row['Email']) ?></td>
                                    <td><?= htmlspecialchars($row['Password']) ?></td>
                                    <td>
                                        <?php
                                        $statusClass = match ($row['Status']) {
                                            'Online' => 'bg-success',
                                            'Offline' => 'bg-secondary',
                                            default => 'bg-light text-dark'
                                        };
                                        ?>
                                        <span class="badge <?= $statusClass ?> text-white"><?= $row['Status'] ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($row['Role_Name']) ?: '<span class="text-muted">N/A</span>' ?></td>
                                    <td><?= $row['Last_Login'] ? date('Y-m-d H:i', strtotime($row['Last_Login'])) : 'Never' ?></td>
                                    <td>
                                        <div class="mx-auto text-center">
                                            <div class="fw-bold text-dark"><?= date('Y-m-d', strtotime($row['Created_At'])) ?></div>
                                            <div class="text-muted small"><?= date('H:i:s', strtotime($row['Created_At'])) ?></div>
                                        </div>
                                    </td>
                                    <td style="width: 118px;">
                                        <a class="btn btn-datatable btn-icon btn-transparent-dark" title="View" data-bs-toggle="modal" data-bs-target="#viewModalDetails<?= $row['User_ID'] ?>">
                                            <i data-feather="eye"></i>
                                        </a>
                                        <a href="users_add_edit.php?id=<?= $row['User_ID'] ?>" class="btn btn-datatable btn-icon btn-transparent-dark" title="Edit">
                                            <i data-feather="edit"></i>
                                        </a>
                                        <a class="btn btn-datatable btn-icon btn-transparent-dark" title="Delete" onclick="return confirmDelete(<?= $row['User_ID'] ?>)">
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
    function confirmDelete(userId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This user will be permanently deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'backend/users_handler.php?deleteid=' + userId;
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


