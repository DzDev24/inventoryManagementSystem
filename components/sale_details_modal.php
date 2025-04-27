<?php if (!isset($sale)) return; ?>

<div class="modal fade" id="viewModalDetails<?= $sale['Sale_ID'] ?>" tabindex="-1"
     aria-labelledby="saleModalLabel<?= $sale['Sale_ID'] ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-lg shadow border-0 overflow-hidden">

            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white font-weight-bold" id="saleModalLabel<?= $sale['Sale_ID'] ?>">
                    <i class="fas fa-shopping-bag me-2"></i> Sale Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">

                <!-- Centered Info -->
                <div class="row text-center mb-4">
                    <div class="col-md-6 mb-3">
                        <small class="text-muted"><i class="fas fa-user me-1"></i> Customer</small>
                        <div class="fw-bold"><?= htmlspecialchars($sale['Customer_Name']) ?></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted"><i class="fas fa-calendar-alt me-1"></i> Sale Date</small>
                        <div class="fw-bold"><?= date('Y-m-d', strtotime($sale['Sale_Date'])) ?></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted"><i class="fas fa-truck me-1"></i> Delivery Status</small><br>
                        <span class="badge <?= match($sale['Delivery_Status']) {
                            'Sold' => 'bg-success',
                            'Pending' => 'bg-warning text-dark',
                            'Canceled' => 'bg-danger',
                            default => 'bg-secondary'
                        } ?>"><?= $sale['Delivery_Status'] ?></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted"><i class="fas fa-credit-card me-1"></i> Payment Status</small><br>
                        <span class="badge <?= match($sale['Payment_Status']) {
                            'Paid' => 'bg-success',
                            'Partial' => 'bg-warning text-dark',
                            'Unpaid' => 'bg-danger',
                            default => 'bg-secondary'
                        } ?>"><?= $sale['Payment_Status'] ?></span>
                    </div>
                </div>

                <!-- Notes -->
                <div class="mb-4">
                    <h6 class="fw-bold"><i class="fas fa-sticky-note me-1"></i> Notes</h6>
                    <p class="text-muted"><?= nl2br(htmlspecialchars($sale['Notes'] ?: 'No additional notes')) ?></p>
                </div>

                <!-- Products List -->
                <h6 class="fw-bold mb-3"><i class="fas fa-boxes me-1"></i> Sold Products</h6>
                <?php
                $saleId = $sale['Sale_ID'];
                $stmt = $conn->prepare("
                    SELECT sod.Product_ID, sod.QTY, sod.Sale_Price, p.Product_Name, m.File_Path
                    FROM sales_order_details sod
                    INNER JOIN products p ON sod.Product_ID = p.Product_ID
                    LEFT JOIN media m ON p.Media_ID = m.Media_ID
                    WHERE sod.Sale_ID = ?
                    GROUP BY sod.Product_ID
                ");
                $stmt->bind_param("i", $saleId);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($prod = $result->fetch_assoc()):
                ?>
                    <div class="product-entry border rounded p-3 mb-3 bg-light">
                        <div class="row align-items-center">
                            <div class="col-md-2 text-center">
                                <img src="<?= $prod['File_Path'] ?: 'https://placehold.co/60x60' ?>" alt="<?= htmlspecialchars($prod['Product_Name']) ?>" class="img-fluid rounded" style="height: 60px;">
                            </div>
                            <div class="col-md-5">
                                <div class="fw-bold"><?= htmlspecialchars($prod['Product_Name']) ?></div>
                            </div>
                            <div class="col-md-2 text-center">
                                <small class="text-muted">Quantity</small>
                                <div class="fw-semibold"><?= $prod['QTY'] ?></div>
                            </div>
                            <div class="col-md-3 text-end">
                                <small class="text-muted">Sale Price</small>
                                <div class="fw-semibold text-success"><?= number_format($prod['Sale_Price'], 2) ?> DA</div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; $stmt->close(); ?>

                <!-- Total Cost -->
                <div class="text-start border-top pt-3">
                    <h5 class="text-muted mb-1">Total Amount</h5>
                    <h4 class="text-success fw-bold"><?= number_format($sale['Total_Amount'], 2) ?> DA</h4>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-4">
                    <small class="text-muted"><i class="fas fa-id-badge me-1"></i> ID: <?= $sale['Sale_ID'] ?></small>
                    <div>
                        <a href="sales_add_edit.php?id=<?= $sale['Sale_ID'] ?>" class="btn btn-warning btn-sm me-2">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                        <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $sale['Sale_ID'] ?>)">
                            <i class="fas fa-trash me-1"></i> Delete
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

