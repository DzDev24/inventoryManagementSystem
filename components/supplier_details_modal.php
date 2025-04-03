<?php if (!isset($supplier)) return; ?>

<div class="modal fade" id="viewModalDetails<?= $supplier['Supplier_ID'] ?>" tabindex="-1"
    aria-labelledby="supplierDetailsLabel<?= $supplier['Supplier_ID'] ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-lg shadow border-0 overflow-hidden">

            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white fw-bold" id="supplierDetailsLabel<?= $supplier['Supplier_ID'] ?>">
                    <i class="fas fa-user-tie me-2"></i> Supplier Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-0">
                <div class="row g-0 flex-nowrap">
                    <!-- Left: Image -->
                    <div class="col-md-5 bg-light d-flex align-items-center justify-content-center p-4">
                        <?php if (!empty($supplier['File_Path'])): ?>
                            <img src="<?= $supplier['File_Path'] ?>" class="img-fluid rounded shadow-sm"
                                alt="<?= htmlspecialchars($supplier['Supplier_Name']) ?>"
                                style="max-height: 100%; object-fit: contain;">
                        <?php else: ?>
                            <div class="text-center text-muted">
                                <i class="fas fa-image fa-4x mb-3"></i>
                                <p>No Image Available</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Right: Info -->
                    <div class="col-md-7 bg-white p-4 d-flex flex-column">
                        <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                        <h4 class="fw-bold mb-0"><?= htmlspecialchars($supplier['Supplier_Name']) ?></h4>
                            <span class="badge <?= $supplier['Status'] === 'Available' ? 'bg-green-soft text-green' : 'bg-purple-soft text-purple' ?>">
                                Status: <?= htmlspecialchars($supplier['Status']) ?>
                            </span>
                            
                        </div>
                            <small class="text-muted">Company Name: <?= htmlspecialchars($supplier['Company_Name']) ?></small>
                        </div>

                        <div class="mb-3">
                            <h6 class="fw-bold"><i class="fas fa-align-left me-1"></i> Description</h6>
                            <ul class="list-unstyled small">
                                <?php
                                $descPoints = array_filter(explode("\n", $supplier['Description']));
                                if (!empty($descPoints)) {
                                    foreach ($descPoints as $point) {
                                        echo "<li class='mb-1'><i class='fas fa-check-circle text-success me-1'></i>" . htmlspecialchars(trim($point)) . "</li>";
                                    }
                                } else {
                                    echo "<li class='text-muted'>No description provided</li>";
                                }
                                ?>
                            </ul>
                        </div>

                        <div class="row small mb-3">
                            <div class="col-6 mb-2">
                                <strong><i class="fas fa-envelope me-1"></i>Email:</strong>
                                <div><?= htmlspecialchars($supplier['Email']) ?></div>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="fas fa-phone me-1"></i>Phone:</strong>
                                <div><?= htmlspecialchars($supplier['Phone']) ?></div>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="fas fa-map-marker-alt me-1"></i>Address:</strong>
                                <div><?= htmlspecialchars($supplier['Address']) ?></div>
                            </div>
                            <div class="col-6 mb-2">
                                <strong><i class="fas fa-map me-1"></i>State:</strong>
                                <div><?= htmlspecialchars($supplier['State_Name'] ?? 'N/A') ?></div>
                            </div>
                        </div>

                        <!-- Bottom: ID + Actions -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-auto">
                            <small class="text-muted"><i class="fas fa-id-badge me-1"></i> ID: <?= $supplier['Supplier_ID'] ?></small>
                            <div>
                                <a href="suppliers_add_edit.php?id=<?= $supplier['Supplier_ID'] ?>" class="btn btn-warning btn-sm me-2">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $supplier['Supplier_ID'] ?>)">
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