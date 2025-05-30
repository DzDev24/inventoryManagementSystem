<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "../includes/db.php";

// Defaults
$userName = "Guest";
$userEmail = "";
$userImage = "../assets/img/illustrations/profiles/profile-1.png";

// Load supplier info
if (isset($_SESSION['supplier_id'])) {
    $id = $_SESSION['supplier_id'];
    $userName = $_SESSION['supplier_name'] ?? 'Supplier';
    $query = $conn->query("SELECT Email, Media_ID FROM supplier WHERE Supplier_ID = $id");
}

if (!empty($query) && $query->num_rows > 0) {
    $data = $query->fetch_assoc();
    $userEmail = $data['Email'];
    if (!empty($data['Media_ID'])) {
        $res = $conn->query("SELECT File_Path FROM media WHERE Media_ID = " . $data['Media_ID']);
        if ($media = $res->fetch_assoc()) {
            $userImage = "../" . $media['File_Path'];
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
    <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 me-2 ms-lg-2 me-lg-0 invisible" disabled>
        <i data-feather="menu"></i>
    </button>

    <a class="navbar-brand pe-3 ps-4 ps-lg-2 d-flex align-items-center gap-2 fw-semibold fs-4 text-dark" href="supplier_dashboard.php">
        <img src="../assets/img/icon.svg" alt="IMS Logo" class="me-1" style="height: 30px;" />
        <span><strong>IMS-25</strong></span>
    </a>

    <div class="d-none d-lg-block" style="flex: 1; max-width: 300px;">
        <div class="input-group input-group-joined input-group-solid">
            <input class="form-control pe-0" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-text"><i data-feather="search"></i></div>
        </div>
    </div>

    <ul class="navbar-nav align-items-center ms-auto">

        <li class="nav-item dropdown no-caret d-none d-sm-block me-3 dropdown-notifications">
            <?php
            // Calculate total alerts
            $totalAlerts = 0;

            if (isset($_SESSION['supplier_id'])) {
                $supplierId = $_SESSION['supplier_id'];

                // Count accepted proposals
                $stmt = $conn->prepare("SELECT COUNT(*) FROM purchases WHERE Accepted = 1 AND Supplier_ID = ?");
                $stmt->bind_param("i", $supplierId);
                $stmt->execute();
                $stmt->bind_result($acceptedCount);
                $stmt->fetch();
                $stmt->close();
                $totalAlerts += $acceptedCount;

                // Count rejected proposals
                $stmt = $conn->prepare("SELECT COUNT(*) FROM purchases WHERE Accepted = 2 AND Supplier_ID = ?");
                $stmt->bind_param("i", $supplierId);
                $stmt->execute();
                $stmt->bind_result($rejectedCount);
                $stmt->fetch();
                $stmt->close();
                $totalAlerts += $rejectedCount;
            }

            // Count session alerts
            if (!empty($_SESSION['alerts'])) {
                $totalAlerts += count($_SESSION['alerts']);
            }
            ?>

            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownAlerts" href="#" role="button" data-bs-toggle="dropdown">
                <i data-feather="bell" style="width: 24px; height: 24px;"></i>
                <?php if ($totalAlerts > 0): ?>
                    <span class="badge rounded-pill bg-danger" style="font-size: 0.5rem; padding: 3px 6px;">
                        <?= $totalAlerts ?>
                    </span>
                <?php endif; ?>
            </a>

            <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownAlerts">
                <h6 class="dropdown-header dropdown-notifications-header">
                    <i class="me-2" data-feather="bell"></i>Alerts Center
                </h6>

                <?php
                $hasAlerts = false;

                if (isset($_SESSION['supplier_id'])) {
                    $supplierId = $_SESSION['supplier_id'];

                    // Accepted proposals
                    $stmt = $conn->prepare("SELECT Purchase_ID, Purchase_Date FROM purchases WHERE Accepted = 1 AND Supplier_ID = ? ORDER BY Purchase_Date DESC LIMIT 5");
                    $stmt->bind_param("i", $supplierId);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        $hasAlerts = true;
                        while ($row = $result->fetch_assoc()) {
                ?>
                            <a class="dropdown-item dropdown-notifications-item" href="my_accepted_proposals.php">
                                <div class="dropdown-notifications-item-icon bg-success">
                                    <i data-feather="check-circle"></i>
                                </div>
                                <div class="dropdown-notifications-item-content">
                                    <div class="dropdown-notifications-item-content-details">
                                        <?= htmlspecialchars(date("Y-m-d", strtotime($row['Purchase_Date']))) ?>
                                    </div>
                                    <div class="dropdown-notifications-item-content-text">
                                        Your proposal (ID: <?= $row['Purchase_ID'] ?>) has been accepted.
                                    </div>
                                </div>
                            </a>
                        <?php
                        }
                    }
                    $stmt->close();

                    // Rejected proposals
                    $stmt = $conn->prepare("SELECT Purchase_ID, Purchase_Date FROM purchases WHERE Accepted = 2 AND Supplier_ID = ? ORDER BY Purchase_Date DESC LIMIT 5");
                    $stmt->bind_param("i", $supplierId);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        $hasAlerts = true;
                        while ($row = $result->fetch_assoc()) {
                        ?>
                            <a class="dropdown-item dropdown-notifications-item" href="my_rejected_proposals.php">
                                <div class="dropdown-notifications-item-icon bg-danger">
                                    <i data-feather="x-circle"></i>
                                </div>
                                <div class="dropdown-notifications-item-content">
                                    <div class="dropdown-notifications-item-content-details">
                                        <?= htmlspecialchars(date("Y-m-d", strtotime($row['Purchase_Date']))) ?>
                                    </div>
                                    <div class="dropdown-notifications-item-content-text">
                                        Your proposal (ID: <?= $row['Purchase_ID'] ?>) has been rejected.
                                    </div>
                                </div>
                            </a>
                        <?php
                        }
                    }
                    $stmt->close();
                }

                // Session alerts
                if (!empty($_SESSION['alerts'])) {
                    $hasAlerts = true;
                    foreach ($_SESSION['alerts'] as $alert) {
                        ?>
                        <a class="dropdown-item dropdown-notifications-item" href="#">
                            <div class="dropdown-notifications-item-icon bg-<?= htmlspecialchars($alert['color']) ?>">
                                <i data-feather="<?= htmlspecialchars($alert['icon']) ?>"></i>
                            </div>
                            <div class="dropdown-notifications-item-content">
                                <div class="dropdown-notifications-item-content-details"><?= htmlspecialchars($alert['time']) ?></div>
                                <div class="dropdown-notifications-item-content-text"><?= htmlspecialchars($alert['message']) ?></div>
                            </div>
                        </a>
                    <?php
                    }
                }

                if (!$hasAlerts) {
                    ?>
                    <div class="text-center p-3 text-muted">No alerts.</div>
                <?php } ?>
            </div>
        </li>





        <!-- Supply Proposal Cart -->
        <li class="nav-item dropdown no-caret d-none d-sm-block me-3 dropdown-notifications">
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownMessages" href="#" role="button" data-bs-toggle="dropdown"><i data-feather="package" style="width: 24px; height: 24px;"></i></a>
            <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up cart-dropdown" aria-labelledby="navbarDropdownMessages">
                <h6 class="dropdown-header dropdown-notifications-header">
                    <i class="me-2" data-feather="package"></i>Supply Proposals Cart
                </h6>

                <?php if (!empty($_SESSION['supply_proposals'])): ?>
                    <?php foreach ($_SESSION['supply_proposals'] as $item): ?>
                        <a class="dropdown-item dropdown-notifications-item" href="suppliers_product.php?id=<?= $item['id'] ?>">
                            <img class="dropdown-notifications-item-img" src="../<?= $item['image'] ?>" />
                            <div class="dropdown-notifications-item-content">
                                <div class="dropdown-notifications-item-content-text"><?= htmlspecialchars($item['name']) ?></div>
                                <div class="dropdown-notifications-item-content-details"><?= $item['quantity'] ?> × <?= number_format($item['buy_price'], 2) ?> DA</div>
                            </div>
                            <form method="POST" action="remove_from_proposals.php" onsubmit="return confirm('Remove this item from proposals?');">
                                <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-link text-danger ms-2 p-0" title="Remove">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </a>
                    <?php endforeach; ?>
                    <a class="dropdown-item dropdown-notifications-footer" href="submit_proposal.php">Submit Proposal</a>
                <?php else: ?>
                    <div class="text-center p-3 text-muted">Your proposals cart is empty.</div>
                <?php endif; ?>
            </div>
        </li>

        <!-- User Dropdown -->
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
                <a class="dropdown-item" href="supplier_profile.php">
                    <div class="dropdown-item-icon"><i data-feather="settings"></i></div>Account
                </a>
                <a class="dropdown-item" href="../login_register/logout.php">
                    <div class="dropdown-item-icon"><i data-feather="log-out"></i></div>Logout
                </a>
            </div>
        </li>
    </ul>
</nav>