<?php
// Make sure $customer is defined
if (!isset($customer)) return;
$customerId = $customer['Customer_ID'];

// Fetch all sales for this customer
$customerId = $customer['Customer_ID'];
$stmt = $conn->prepare("SELECT * FROM sales WHERE Customer_ID = ? ORDER BY Sale_Date DESC");
$stmt->bind_param("i", $customerId);
$stmt->execute();
$salesResult = $stmt->get_result();
?>

<div class="modal fade" id="orderHistoryModal<?= $customerId ?>" tabindex="-1" aria-labelledby="orderHistoryLabel<?= $customerId ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-lg shadow border-0 overflow-hidden">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold text-white" id="orderHistoryLabel<?= $customerId ?>">
                    <i class="fas fa-history me-2"></i> Order History for <?= htmlspecialchars($customer['Name']) ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <?php if ($salesResult->num_rows > 0): ?>
                    <?php while ($sale = $salesResult->fetch_assoc()): ?>
                        <div class="border rounded p-3 mb-3 bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <div class="fw-bold">Date: <?= date('Y-m-d', strtotime($sale['Sale_Date'])) ?></div>
                                    <div class="small text-muted">Sale ID: <?= $sale['Sale_ID'] ?></div>
                                </div>
                                <div class="text-end">
                                    <span class="badge <?= match($sale['Payment_Status']) {
                                        'Paid' => 'bg-success',
                                        'Partial' => 'bg-warning text-dark',
                                        'Unpaid' => 'bg-danger',
                                        default => 'bg-secondary'
                                    } ?>"><?= $sale['Payment_Status'] ?></span>
                                    <br>
                                    <span class="badge <?= match($sale['Delivery_Status']) {
                                        'Sold' => 'bg-success',
                                        'Pending' => 'bg-warning text-dark',
                                        'Canceled' => 'bg-danger',
                                        default => 'bg-secondary'
                                    } ?>"><?= $sale['Delivery_Status'] ?></span>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="text-muted small">Payment: <?= htmlspecialchars($sale['Payment_Method']) ?></div>
                                <div class="fw-bold text-success"><?= number_format($sale['Total_Amount'], 2) ?> DA</div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="text-muted text-center">
                        <i class="fas fa-info-circle fa-2x mb-2"></i><br>
                        No orders found for this customer.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $stmt->close(); ?>
