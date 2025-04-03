<?php if (!isset($product)) return; ?>

<div class="modal fade" id="viewModalDetails<?= $product['Product_ID'] ?>" tabindex="-1"
    aria-labelledby="productModalLabel<?= $product['Product_ID'] ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-lg shadow border-0 overflow-hidden">


            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white font-weight-bold" id="productModalLabel<?= $product['Product_ID'] ?>">
                    <i class="fas fa-box-open me-2"></i> Product Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-0">
                <div class="row g-0 flex-nowrap">
                    <!-- Left Image Section -->
                    <div class="col-md-6 bg-light d-flex align-items-center justify-content-center p-4">
                        <?php if (!empty($product['File_Path'])): ?>
                            <img src="<?= $product['File_Path'] ?>" class="img-fluid rounded shadow-sm"
                                alt="<?= htmlspecialchars($product['Product_Name']) ?>"
                                style="max-height: 100%; object-fit: contain;">
                        <?php else: ?>
                            <div class="text-center text-muted">
                                <i class="fas fa-image fa-4x mb-3"></i>
                                <p>No Image Available</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Right Content -->
                    <div class="col-md-6 bg-white p-4 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h4 class="fw-bold mb-1"><?= htmlspecialchars($product['Product_Name']) ?></h4>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-primary-soft text-primary"><?= htmlspecialchars($product['Category_Name']) ?></span>
                                    <span class="badge bg-purple-soft text-purple"><?= htmlspecialchars($product['Supplier_Name']) ?></span>
                                </div>
                            </div>
                            <?php
                            $isLow = $product['Quantity'] < $product['Minimum_Stock'];
                            ?>
                            <span class="badge <?= $isLow ? 'bg-danger-soft text-danger' : 'bg-success-soft text-success' ?>">
                                <?= $isLow ? 'Low Stock' : 'In Stock' ?>
                            </span>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <h6 class="fw-bold"><i class="fas fa-file-alt me-1"></i> Description</h6>
                            <ul class="list-unstyled small">
                                <?php
                                $descPoints = array_filter(explode("\n", $product['Description']));
                                if (!empty($descPoints)) {
                                    foreach ($descPoints as $point) {
                                        echo "<li class='mb-1'>• " . htmlspecialchars(trim($point)) . "</li>";
                                    }
                                } else {
                                    echo "<li class='text-muted'>No description provided</li>";
                                }
                                ?>
                            </ul>
                        </div>

                        <!-- Product Details -->
                        <div class="row mb-3">
                            <div class="col-6">
                                <small class="text-muted"><i class="fas fa-cubes me-1"></i> Quantity</small>
                                <div><strong><?= $product['Quantity'] . ' ' . $product['Unit_abrev'] ?></strong></div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted"><i class="fas fa-warehouse me-1"></i> Min. Stock</small>
                                <div><strong><?= $product['Minimum_Stock'] ?></strong></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <small class="text-muted"><i class="fas fa-money-bill me-1"></i> Buy Price</small>
                                <div class="text-danger"><strong><?= number_format($product['Buy_Price'], 2) ?> DA</strong></div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted"><i class="fas fa-tags me-1"></i> Sale Price</small>
                                <div class="text-success"><strong><?= number_format($product['Sale_Price'], 2) ?> DA</strong></div>
                            </div>
                        </div>

                        <!-- Timestamps -->
                        <div class="row mb-3">
                            <div class="col-6">
                                <small class="text-muted"><i class="fas fa-calendar-plus me-1"></i> Created At</small>
                                <div><strong><?= $product['Created_At'] ?></strong></div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted"><i class="fas fa-calendar-check me-1"></i> Updated At</small>
                                <div><strong><?= $product['Updated_At'] ?></strong></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-auto">
                            <small class="text-muted"><i class="fas fa-id-badge me-1"></i> ID: <?= $product['Product_ID'] ?></small>
                            <div>
                                <a href="products_add_edit.php?id=<?= $product['Product_ID'] ?>" class="btn btn-warning btn-sm me-2">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $product['Product_ID'] ?>)">
                                    <i class="fas fa-trash me-1"></i> Delete
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>