<?php
require_once "../login_register/auth_session.php";
require_once "../includes/db.php";

if ($_SESSION['user_type'] !== 'supplier') {
    header("Location: ../login_register/login.php");
    exit;
}

include 'proposal_header.php';

$supplier_id = $_SESSION['supplier_id'];
$supplier = $conn->query("SELECT * FROM supplier WHERE Supplier_ID = $supplier_id")->fetch_assoc();
$states = $conn->query("SELECT * FROM states")->fetch_all(MYSQLI_ASSOC);

$mode = $_GET['mode'] ?? 'cart';
$proposal = ($mode === 'propose_now' && isset($_SESSION['propose_now']))
    ? [$_SESSION['propose_now']]
    : ($_SESSION['supply_proposals'] ?? []);

$total = 0;
foreach ($proposal as $item) {
    $total += $item['buy_price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Supply Proposal - IMS-24</title>

    <!-- Bootstrap Core CSS -->
    <link href="../css/vendor/bootstrap.css" rel="stylesheet" />

    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>

    <!-- Custom Styles -->
    <link href="../css/styles.css" rel="stylesheet" />

    <!-- Bootstrap Bundle -->
    <script src="../js/vendor/bootstrap.bundle.min.js"></script>
</head>
<body>
<main class="mt-4">
    <div class="container-xl px-4">
        <h2 class="mb-4">Submit Supply Proposal</h2>

        <!-- Supplier Info -->
        <div class="card mb-4">
            <div class="card-header"><strong>Supplier Information</strong></div>
            <div class="card-body">
                <p><strong>Name:</strong> <?= htmlspecialchars($supplier['Supplier_Name']) ?></p>
                <p><strong>Address:</strong> <?= htmlspecialchars($supplier['Address']) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($supplier['Phone']) ?></p>
                <p><strong>State:</strong> <?= $conn->query("SELECT State_Name FROM states WHERE State_ID = " . $supplier['State_ID'])->fetch_assoc()['State_Name'] ?? '' ?></p>
            </div>
        </div>

        <!-- Payment -->
        <div class="card mb-4">
            <div class="card-header"><strong>Payment</strong></div>
            <div class="card-body">
                <form method="post" action="process_proposal.php">
                    <input type="hidden" name="supplier_id" value="<?= $supplier_id ?>">
                    <div class="mb-3">
                        <label for="payment_method">Payment Method</label>
                        <select id="payment_method" name="payment_method" class="form-control" required>
                            <option value="">Choose a method</option>
                            <option value="Cash">Cash</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Cheque">Cheque</option>
                            <option value="Mobile Payment">Mobile Payment</option>
                        </select>
                    </div>

                    <!-- Proposal Items -->
                    <h5 class="mt-4">Proposal Summary</h5>
                    <ul class="list-group mb-3">
                        <?php foreach ($proposal as $item): ?>
                            <li class="list-group-item d-flex justify-content-between">
                                <span><?= htmlspecialchars($item['name']) ?> (×<?= $item['quantity'] ?>)</span>
                                <strong><?= number_format($item['buy_price'] * $item['quantity'], 2) ?> DA</strong>
                            </li>
                        <?php endforeach; ?>
                        <li class="list-group-item d-flex justify-content-between bg-light">
                            <strong>Total</strong>
                            <strong><?= number_format($total, 2) ?> DA</strong>
                        </li>
                    </ul>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg">Confirm Supply Proposal</button>
                    </div>
                </form>
            </div>
        </div>

        <?php include '../includes/footer.php'; ?>
    </div>
</main>

<!-- Feather Icons Init -->
<script>feather.replace();</script>

</body>
</html>


