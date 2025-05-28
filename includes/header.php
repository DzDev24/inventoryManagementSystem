<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "db.php";


$dashboardLink = null; // default fallback

if (isset($_SESSION['user_role'])) {
    switch ($_SESSION['user_role']) {
        case 1:
            $dashboardLink = "/inventoryManagementSystem/admin_dashboard.php";
            break;
        case 2:
            $dashboardLink = "/inventoryManagementSystem/sales_dashboard.php";
            break;
        case 3:
            $dashboardLink = "/inventoryManagementSystem/product-manager-dashboard.php";
            break;
        default:
            $dashboardLink = "/inventoryManagementSystem/login_register/login.php";
            break;
    }
}

// Default values
$userName = "Guest";
$userEmail = "";
$userImage = "inventoryManagementSystem/assets/img/illustrations/profiles/profile-1.png";

// Check for logged-in user
if (isset($_SESSION['user_id'])) {
    $id = $_SESSION['user_id'];
    $query = $conn->query("SELECT Username, Email, Media_ID FROM users WHERE User_ID = $id");
    if ($query && $query->num_rows > 0) {
        $data = $query->fetch_assoc();
        $userName = $data['Username'];
        $userEmail = $data['Email'];
        if (!empty($data['Media_ID'])) {
            $res = $conn->query("SELECT File_Path FROM media WHERE Media_ID = " . $data['Media_ID']);
            if ($media = $res->fetch_assoc()) {
                $userImage = "" . $media['File_Path'];
            }
        }
    }
}
?>

<style>
.cart-dropdown {
    max-height: 400px;
    overflow-y: auto;
    scrollbar-width: none;
}
.cart-dropdown::-webkit-scrollbar {
    display: none;
}
.dropdown-notifications-item-content-text {
    white-space: normal !important;
    overflow: visible !important;
    text-overflow: unset !important;
    display: block !important;
    word-break: break-word !important;
    max-width: 100% !important;
}
</style>

<nav class="topnav navbar navbar-expand shadow justify-content-between justify-content-sm-start navbar-light bg-white" id="sidenavAccordion">
    <!-- Sidebar Toggle (Visible if needed) -->
    <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 me-2 ms-lg-2 me-lg-0" id="sidebarToggle">
        <i data-feather="menu"></i>
    </button>

    <!-- Navbar Brand -->
    <a class="navbar-brand pe-3 ps-4 ps-lg-2 d-flex align-items-center gap-2 fw-semibold fs-4 text-dark" href="<?= $dashboardLink ?>">
        <img src="/inventoryManagementSystem/assets/img/icon.svg" alt="IMS Logo" class="me-1" style="height: 30px;" />
        <span><strong>IMS-25</strong></span>
    </a>

    <!-- Search bar -->
    <form class="form-inline me-auto d-none d-lg-block me-3">
        <div class="input-group input-group-joined input-group-solid">
            <input class="form-control pe-0" type="search" placeholder="Search" aria-label="Search" />
            <div class="input-group-text"><i data-feather="search"></i></div>
        </div>
    </form>

    <!-- Navbar Right -->
    <ul class="navbar-nav align-items-center ms-auto">

        <!-- Alerts -->
        <li class="nav-item dropdown no-caret d-none d-sm-block me-3 dropdown-notifications">
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownAlerts" href="#" role="button" data-bs-toggle="dropdown">
                <i data-feather="bell"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownAlerts">
                <h6 class="dropdown-header dropdown-notifications-header"><i class="me-2" data-feather="bell"></i>Alerts Center</h6>

                <?php if (!empty($_SESSION['alerts'])): ?>
                    <?php foreach ($_SESSION['alerts'] as $alert): ?>
                        <a class="dropdown-item dropdown-notifications-item" href="#">
                            <div class="dropdown-notifications-item-icon bg-<?= htmlspecialchars($alert['color']) ?>">
                                <i data-feather="<?= htmlspecialchars($alert['icon']) ?>"></i>
                            </div>
                            <div class="dropdown-notifications-item-content">
                                <div class="dropdown-notifications-item-content-details"><?= htmlspecialchars($alert['time']) ?></div>
                                <div class="dropdown-notifications-item-content-text"><?= htmlspecialchars($alert['message']) ?></div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center p-3 text-muted">No alerts.</div>
                <?php endif; ?>
            </div>
        </li>

        <!-- User Menu -->
        <li class="nav-item dropdown no-caret dropdown-user me-3 me-lg-4">
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage" href="#" role="button" data-bs-toggle="dropdown">
                <img class="img-fluid" src="<?= htmlspecialchars($userImage) ?>" />
            </a>
            <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownUserImage">
                <h6 class="dropdown-header d-flex align-items-center">
                    <img class="dropdown-user-img me-2" src="<?= htmlspecialchars($userImage) ?>" />
                    <div class="dropdown-user-details">
                        <div class="dropdown-user-details-name"><?= htmlspecialchars($userName) ?></div>
                        <div class="dropdown-user-details-email"><?= htmlspecialchars($userEmail) ?></div>
                    </div>
                </h6>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="user_profile.php">
                    <div class="dropdown-item-icon"><i data-feather="settings"></i></div>Account
                </a>
                <a class="dropdown-item" href="/inventoryManagementSystem/login_register/logout.php">
                    <div class="dropdown-item-icon"><i data-feather="log-out"></i></div>Logout
                </a>
            </div>
        </li>
    </ul>
</nav>



