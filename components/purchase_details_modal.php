<?php if (!isset($purchase)) return; ?>

<div class="modal fade" id="viewModalDetails<?= $purchase['Purchase_ID'] ?>" tabindex="-1"
     aria-labelledby="purchaseModalLabel<?= $purchase['Purchase_ID'] ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-lg shadow border-0 overflow-hidden">

            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white font-weight-bold" id="purchaseModalLabel<?= $purchase['Purchase_ID'] ?>">
                    <i class="fas fa-shopping-cart me-2"></i> Purchase Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">

                <!-- Centered Info -->
                <div class="row text-center mb-4">
                    <div class="col-md-6 mb-3">
                    <small class="text-muted"><i class="fas fa-credit-card me-1"></i> Payment Method</small>
                    <div class="fw-bold"><?= $purchase['Payment_Method'] ?></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted"><i class="fas fa-calendar-alt me-1"></i> Purchase Date</small>
                        <div class="fw-bold"><?= date('Y-m-d', strtotime($purchase['Purchase_Date'])) ?></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted"><i class="fas fa-truck me-1"></i> Delivery Status</small><br>
                        <span class="badge <?= match($purchase['Delivery_Status']) {
                            'Recieved' => 'bg-success',
                            'Pending'  => 'bg-warning text-dark',
                            'Canceled' => 'bg-danger',
                            default => 'bg-secondary'
                        } ?>"><?= $purchase['Delivery_Status'] ?></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted"><i class="fas fa-credit-card me-1"></i> Payment Status</small><br>
                        <span class="badge <?= match($purchase['Payment_Status']) {
                            'Paid' => 'bg-success',
                            'Partial' => 'bg-warning text-dark',
                            'Unpaid' => 'bg-danger',
                            default => 'bg-secondary'
                        } ?>"><?= $purchase['Payment_Status'] ?></span>
                    </div>
                </div>

                <!-- Notes -->
                <div class="mb-4">
                    <h6 class="fw-bold"><i class="fas fa-sticky-note me-1"></i> Notes</h6>
                    <p class="text-muted"><?= nl2br(htmlspecialchars($purchase['Notes'] ?: 'No additional notes')) ?></p>
                </div>

                <!-- Products List -->
                <h6 class="fw-bold mb-3"><i class="fas fa-boxes me-1"></i> Purchased Products</h6>
                <?php
                $purchaseId = $purchase['Purchase_ID'];
                $stmt = $conn->prepare("
                    SELECT pd.Product_ID, pd.QTY, pd.Buy_Price, p.Product_Name, m.File_Path,
                           s.Supplier_Name
                    FROM purchases_details pd
                    INNER JOIN products p ON pd.Product_ID = p.Product_ID
                    LEFT JOIN media m ON p.Media_ID = m.Media_ID
                    LEFT JOIN product_supplier ps ON p.Product_ID = ps.Product_ID AND ps.Supplier_ID = (
                        SELECT supplier_id FROM product_supplier WHERE product_id = p.Product_ID LIMIT 1
                    )
                    LEFT JOIN supplier s ON ps.Supplier_ID = s.Supplier_ID
                    WHERE pd.Purchase_ID = ?
                    GROUP BY pd.Product_ID
                ");
                $stmt->bind_param("i", $purchaseId);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($prod = $result->fetch_assoc()):
                ?>
                    <div class="product-entry border rounded p-3 mb-3 bg-light">
                        <div class="row align-items-center">
                            <div class="col-md-2 text-center">
                                <img src="<?= $prod['File_Path'] ?: 'https://placehold.co/60x60' ?>" alt="<?= htmlspecialchars($prod['Product_Name']) ?>" class="img-fluid rounded" style="height: 60px;">
                            </div>
                            <div class="col-md-4">
                                <div class="fw-bold"><?= htmlspecialchars($prod['Product_Name']) ?></div>
                                <div class="text-muted small">Supplier: <?= htmlspecialchars($prod['Supplier_Name'] ?: 'N/A') ?></div>
                            </div>
                            <div class="col-md-3 text-center">
                                <small class="text-muted">Quantity</small>
                                <div class="fw-semibold"><?= $prod['QTY'] ?></div>
                            </div>
                            <div class="col-md-3 text-end">
                                <small class="text-muted">Buy Price</small>
                                <div class="fw-semibold text-danger"><?= number_format($prod['Buy_Price'], 2) ?> DA</div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; $stmt->close(); ?>

                <!-- Total Cost -->
                <div class="text-start border-top pt-3">
                    <h5 class="text-muted mb-1">Total Amount</h5>
                    <h4 class="text-success fw-bold"><?= number_format($purchase['Total_Amount'], 2) ?> DA</h4>
                </div>


                <!-- Action Buttons -->
                <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-4">
                    <small class="text-muted"><i class="fas fa-id-badge me-1"></i> ID: <?= $purchase['Purchase_ID'] ?></small>
                    <div>
                        <a href="purchases_add_edit.php?id=<?= $purchase['Purchase_ID'] ?>" class="btn btn-warning btn-sm me-2">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                        <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $purchase['Purchase_ID'] ?>)">
                            <i class="fas fa-trash me-1"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
