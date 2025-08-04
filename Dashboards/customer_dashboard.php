
<?php
require_once "../login_register/auth_session.php";

$is_customer = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'customer';
$is_guest = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'guest';

if (!$is_customer && !$is_guest) {
    header("Location: ../login_register/login.php");
    exit;
}


require_once "../includes/db.php";
if (isset($_SESSION['customer_id'])) {
    $customer_id = intval($_SESSION['customer_id']);
    $conn->query("UPDATE customers SET Status = 'available' WHERE Customer_ID = $customer_id");
}


require_once "../includes/db.php";
include 'dashboard_header.php';

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
    <title>Customer Dashboard</title>

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
                            <?php
$display_name = 'Guest';
if (isset($_SESSION['customer_name'])) {
    $display_name = $_SESSION['customer_name'];
} elseif (isset($_SESSION['user_name'])) {
    $display_name = $_SESSION['user_name'];
}
?>
Welcome, <?= htmlspecialchars($display_name) ?>!

                        </h1>
                        <div class="page-header-subtitle">Browse available products below</div>
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
                                <a href="product.php?id=<?= $product['Product_ID'] ?>">
                                    <img src="../<?= $product['File_Path'] ?>" class="card-img-top" style="max-height: 250px; object-fit: cover;" alt="Product Image">
                                </a>
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="product.php?id=<?= $product['Product_ID'] ?>" class="text-decoration-none text-dark">
                                        <?= htmlspecialchars($product['Product_Name']) ?>
                                    </a>
                                </h5>
                                <div class="text-success fw-semibold fs-5"><?= number_format($product['Sale_Price'], 2) ?> DA</div>
                                <div class="mb-2"><strong><?= $product['Quantity'] . ' ' . $product['Unit_abrev'] ?></strong></div>
                                <span class="badge bg-primary-soft text-primary small"><?= $product['Category_Name'] ?></span>
                                <div class="d-grid gap-2 mt-3">

                                    <form action="buy_now.php" method="POST" class="m-0">
                                        <input type="hidden" name="product_id" value="<?= $product['Product_ID'] ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-sm btn-primary w-100">
                                            <i data-feather="credit-card" class="me-1"></i> Buy Now
                                        </button>
                                    </form>

                                    <form action="add_to_cart.php" method="POST" class="m-0">
                                        <input type="hidden" name="product_id" value="<?= $product['Product_ID'] ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-sm btn-outline-primary w-100">
                                            <i data-feather="shopping-cart" class="me-1"></i> Add to Cart
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
                title: 'Order Placed',
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