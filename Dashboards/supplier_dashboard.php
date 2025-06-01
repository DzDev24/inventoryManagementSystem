<?php
require_once "../login_register/auth_session.php";

// Restrict access to suppliers only
if ($_SESSION['user_type'] !== 'supplier') {
    header("Location: ../login_register/login.php");
    exit;
}

require_once "../includes/db.php";
$supplier_id = $_SESSION['supplier_id'];
$conn->query("UPDATE supplier SET Status = 'available' WHERE Supplier_ID = $supplier_id");

require_once "../includes/db.php";
include 'supplier_dashboard_header.php';

// Fetch products
$products = $conn->query("SELECT p.*, m.File_Path, u.Unit_abrev, c.Category_Name
                          FROM products p 
                          LEFT JOIN category c ON p.Category_ID = c.Category_ID
                          LEFT JOIN media m ON p.Media_ID = m.Media_ID 
                          LEFT JOIN units u ON p.Unit_ID = u.Unit_ID");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Supplier Dashboard</title>

    <!-- Styles -->
    <link href="../css/vendor/bootstrap.css" rel="stylesheet" />
    <link href="../css/vendor/datatables-style.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="../assets/img/icon.svg" />

    <!-- Feather & Font Awesome Icons -->
    <script src="../js/vendor/feather.min.js" crossorigin="anonymous"></script>
    <script src="../js/vendor/font-awesome.min.js" crossorigin="anonymous"></script>
</head>

<body class="bg-light">

    <!-- Overlap Header -->
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="user"></i></div>
                            Welcome, <?= htmlspecialchars($_SESSION['supplier_name']) ?>!
                        </h1>
                        <div class="page-header-subtitle">Browse products available to supply below</div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content that overlaps the header -->
    <main class="mt-n10">
        <div class="container-xl px-4">
            <div class="row">
                <?php while ($product = $products->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow">
                            <?php if (!empty($product['File_Path'])): ?>
                                <a href="suppliers_product.php?id=<?= $product['Product_ID'] ?>">
                                    <img src="../<?= $product['File_Path'] ?>" class="card-img-top" style="max-height: 250px; object-fit: cover;" alt="Product Image">
                                </a>
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="suppliers_product.php?id=<?= $product['Product_ID'] ?>" class="text-decoration-none text-dark">
                                        <?= htmlspecialchars($product['Product_Name']) ?>
                                    </a>
                                </h5>
                                <small class="text-muted">Wanted Price per unit</small>
                                <div class="text-success fw-semibold fs-5"><?= number_format($product['Buy_Price'], 2) ?> DA</div>
                                <div class="mb-2"><strong><?= $product['Quantity'] . ' ' . $product['Unit_abrev'] ?></strong></div>
                                <span class="badge bg-primary-soft text-primary small"><?= $product['Category_Name'] ?></span>
                                <div class="d-grid gap-2 mt-3">

                                    <form action="propose_now.php" method="POST" class="m-0">
                                        <input type="hidden" name="product_id" value="<?= $product['Product_ID'] ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-sm btn-primary w-100">
                                            <i data-feather="send" class="me-1"></i> Propose to Supply
                                        </button>
                                    </form>

                                    <form action="add_to_proposals.php" method="POST" class="m-0">
                                        <input type="hidden" name="product_id" value="<?= $product['Product_ID'] ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-sm btn-outline-primary w-100">
                                            <i data-feather="plus-circle" class="me-1"></i> Add to Supply Proposals List
                                        </button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
                <?php include '../includes/footer.php'; ?>
            </div>
        </div>
    </main>

    <!-- JS -->
    <script src="../js/vendor/bootstrap.bundle.min.js"></script>
    <script>
        feather.replace();
    </script>

    <!-- SweetAlert2 -->
    <script src="../js/vendor/sweetalert2@11_.js"></script>

    <?php if (isset($_SESSION['success'])): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Proposal Sent',
                text: '<?= addslashes($_SESSION['success']) ?>',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        </script>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['profile_updated'])): ?>
        <script src="../js/vendor/sweetalert2@11_.js"></script>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Profile Updated Successfully',
                text: 'Your changes have been saved.',
                confirmButtonColor: '#3085d6'
            });
        </script>
        <?php unset($_SESSION['profile_updated']); ?>
    <?php endif; ?>

</body>

</html>