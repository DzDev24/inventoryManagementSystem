<?php
// includes/sidebar.php

// Check if user is logged in and is a system user
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'user') {
    header("Location: ./login_register/login.php");
    exit;
}

$roleId = $_SESSION['user_role'];
?>

<nav class="sidenav shadow-right sidenav-light">
    <div class="sidenav-menu">
        <div class="nav accordion" id="accordionSidenav">

            <!-- Sidenav Menu Heading -->
            <div class="sidenav-menu-heading">Management</div>


            <?php
            $dashboardLink = "./login_register/login.php"; // default

            if (isset($_SESSION['user_role'])) {
                switch ($_SESSION['user_role']) {
                    case 1:
                        $dashboardLink = "./admin_dashboard.php";
                        break;
                    case 2:
                        $dashboardLink = "./sales_dashboard.php";
                        break;
                    case 3:
                        $dashboardLink = "./product-manager-dashboard.php";
                        break;
                }
            }
            ?>

            <!-- رابط داشبورد ديناميكي -->
            <a class="nav-link" href="<?= $dashboardLink ?>">
                <div class="nav-link-icon"><i data-feather="activity"></i></div>
                Dashboard
            </a>


            <?php if ($roleId == 1 || $roleId == 3): ?>
                <!-- Products -->
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseProducts">
                    <div class="nav-link-icon"><i data-feather="package"></i></div>
                    Products
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseProducts" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link" href="products_list.php">List Products</a>
                        <a class="nav-link" href="products_add_edit.php">Add Product</a>
                    </nav>
                </div>
            <?php endif; ?>

            <?php if ($roleId == 1 || $roleId == 3): ?>
                <!-- Categories -->
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseCategories">
                    <div class="nav-link-icon"><i data-feather="tag"></i></div>
                    Categories
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseCategories" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link" href="categories_list.php">List Categories</a>
                        <a class="nav-link" href="categories_add_edit.php">Add Category</a>
                    </nav>
                </div>
            <?php endif; ?>

            <?php if ($roleId == 1 || $roleId == 3): ?>
                <!-- Suppliers -->
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseSuppliers">
                    <div class="nav-link-icon"><i data-feather="truck"></i></div>
                    Suppliers
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseSuppliers" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link" href="suppliers_list.php">List Suppliers</a>
                        <a class="nav-link" href="suppliers_add_edit.php">Add Supplier</a>
                    </nav>
                </div>
            <?php endif; ?>

            <?php if ($roleId == 1 || $roleId == 2): ?>
                <!-- Customers -->
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseCustomers">
                    <div class="nav-link-icon"><i data-feather="users"></i></div>
                    Customers
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseCustomers" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link" href="customers_list.php">List Customers</a>
                        <a class="nav-link" href="customers_add_edit.php">Add Customer</a>
                    </nav>
                </div>
            <?php endif; ?>

            <?php if ($roleId == 1): ?>
                <!-- Users -->
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseUsers">
                    <div class="nav-link-icon"><i data-feather="users"></i></div>
                    Users
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseUsers" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link" href="users_list.php">List Users</a>
                        <a class="nav-link" href="users_pending.php">Pending Users</a>

                        
                        <a class="nav-link" href="users_add_edit.php">Add User</a>
                    </nav>
                </div>
            <?php endif; ?>

            <?php if ($roleId == 1 || $roleId == 3): ?>
                <!-- Purchases -->
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapsePurchases">
                    <div class="nav-link-icon"><i data-feather="shopping-cart"></i></div>
                    Purchases
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapsePurchases" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link" href="purchases_list.php">Purchases List</a>
                        <a class="nav-link" href="purchases_add_edit.php">Add Purchase</a>
                        <a class="nav-link" href="proposals_list.php">Proposals List</a>

                    </nav>
                </div>
            <?php endif; ?>

            <?php if ($roleId == 1 || $roleId == 2): ?>
                <!-- Sales -->
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseSales">
                    <div class="nav-link-icon"><i data-feather="shopping-bag"></i></div>
                    Sales
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseSales" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link" href="sales_list.php">Sales List</a>
                        <a class="nav-link" href="sales_add_edit.php">Add Sale</a>
                    </nav>
                </div>
            <?php endif; ?>

        </div>
    </div>
</nav>