<?php
require_once "../login_register/auth_session.php";
require_once "../includes/db.php";
include 'checkout_header.php';

// Only allow customer users
if ($_SESSION['user_type'] !== 'customer') {
    header("Location: ../login_register/login.php");
    exit;
}

// Fetch customer info
$customer_id = $_SESSION['customer_id'];
$customer = $conn->query("SELECT * FROM customers WHERE Customer_ID = $customer_id")->fetch_assoc();

$states = $conn->query("SELECT * FROM states")->fetch_all(MYSQLI_ASSOC);

$mode = $_GET['mode'] ?? 'cart';

if ($mode === 'buy_now' && isset($_SESSION['buy_now'])) {
    $cart = [$_SESSION['buy_now']];
} else {
    $cart = $_SESSION['cart'] ?? [];
}

$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - IMS-25</title> 
    <link href="../css/vendor/bootstrap.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="../js/vendor/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container py-5">
    <h2 class="mb-4">Checkout</h2>

    <!-- Shipping Info -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Shipping Information</strong>
            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editShippingModal">Edit</button>
        </div>
        <div class="card-body">
            <p><strong>Name:</strong> <?= htmlspecialchars($customer['Name']) ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($customer['Shipping Address']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($customer['Phone']) ?></p>
            <p><strong>State:</strong> <?= $conn->query("SELECT State_Name FROM states WHERE State_ID = " . $customer['State_ID'])->fetch_assoc()['State_Name'] ?? '' ?></p>
        </div>
    </div>

    <!-- Modal for Editing Shipping Info -->
    <div class="modal fade" id="editShippingModal" tabindex="-1" aria-labelledby="editShippingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <form id="shippingForm" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Shipping Info</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="real_name" class="form-control mb-2" value="<?= htmlspecialchars($customer['Name']) ?>" required>
                    <input type="text" name="address" class="form-control mb-2" value="<?= htmlspecialchars($customer['Shipping Address']) ?>" required>
                    <input type="text" name="phone" class="form-control mb-2" value="<?= htmlspecialchars($customer['Phone']) ?>" required>
                    <select name="state_id" class="form-control" required>
                        <?php foreach ($states as $state): ?>
                            <option value="<?= $state['State_ID'] ?>" <?= $customer['State_ID'] == $state['State_ID'] ? 'selected' : '' ?>>
                                <?= $state['State_Name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Payment -->
    <div class="card mb-4">
        <div class="card-header"><strong>Payment</strong></div>
        <div class="card-body">
            <form id="checkoutForm" method="post" action="process_checkout.php">
                <input type="hidden" name="customer_id" value="<?= $customer_id ?>">
                <div class="mb-3">
                    <label>Payment Method</label>
                    <select name="payment_method" id="payment_method" class="form-control" required>
    <option value="">Choose a method</option>
    <option value="Cash">Cash</option>
    <option value="Credit Card">Credit Card</option>
    <option value="Bank Transfer">Bank Transfer</option>
    <option value="Cheque">Cheque</option>

                    </select>
                </div>
                <div id="creditCardFields" style="display: none;">
                <input type="text" name="cardholder_name" class="form-control mb-2 cc-field" placeholder="Cardholder Name">

<input type="text" name="card_number" class="form-control mb-2 cc-field" placeholder="Card Number"
       pattern="\d{13,19}" maxlength="19" inputmode="numeric" title="Enter a valid card number (13–19 digits)">

<input type="text" name="cvv" class="form-control mb-2 cc-field" placeholder="CVV"
       pattern="\d{3}" maxlength="3" inputmode="numeric" title="Enter a 3-digit CVV code">

       <div class="mb-2">
    <select name="expiry_month" class="form-control cc-field">
        <option value="">-- Expiry Month --</option>
        <?php
        $months = [
            '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
            '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
            '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
        ];
        foreach ($months as $value => $name):
        ?>
            <option value="<?= $value ?>">(<?= $value ?>) <?= $name ?></option>
        <?php endforeach; ?>
    </select>
</div>


<div class="mb-2">
    <select name="expiry_year" class="form-control mb-2 cc-field" required>
        <option value="">-- Expiry Year --</option>
        <?php for ($y = 2025; $y <= 2050; $y++): ?>
            <option value="<?= $y ?>"><?= $y ?></option>
        <?php endfor; ?>
    </select>
</div>


                </div>

                <!-- Cart Items -->
                <h5 class="mt-4">Order Summary</h5>
                <ul class="list-group mb-3">
                    <?php foreach ($cart as $item): ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><?= htmlspecialchars($item['name']) ?> (×<?= $item['quantity'] ?>)</span>
                            <strong><?= number_format($item['price'] * $item['quantity'], 2) ?> DA</strong>
                        </li>
                    <?php endforeach; ?>
                    <li class="list-group-item d-flex justify-content-between bg-light">
                        <strong>Total</strong>
                        <strong><?= number_format($total, 2) ?> DA</strong>
                    </li>
                </ul>

                <div class="d-grid">
                    <button type="submit" class="btn btn-success btn-lg">Confirm Purchase</button>
                </div>
            </form>
        </div>
        <?php include '../includes/footer.php'; ?>
    </div>
</div>

<script>
document.getElementById('payment_method').addEventListener('change', function () {
    const ccFields = document.getElementById('creditCardFields');
    ccFields.style.display = this.value === 'Credit Card' ? 'block' : 'none';
});
</script>

<script>
document.getElementById('payment_method').addEventListener('change', function () {
    const ccFields = document.getElementById('creditCardFields');
    const isCC = this.value === 'Credit Card';
    ccFields.style.display = isCC ? 'block' : 'none';

    // Toggle required attributes
    document.querySelectorAll('.cc-field').forEach(field => {
        field.required = isCC;
    });
});
</script>

<script>
document.getElementById('shippingForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('update_shipping.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Close modal
            const modalEl = document.getElementById('editShippingModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();

            // Reload page to reflect updated shipping info
            location.reload();
        } else {
            alert(data.message || "An error occurred");
        }
    })
    .catch(err => {
        console.error(err);
        alert("An error occurred while updating shipping info.");
    });
});
</script>

</body>
</html>

