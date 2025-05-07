<?php if (!isset($customer)) return; ?>

<div class="modal fade" id="viewCustomerModal<?= $customer['Customer_ID'] ?>" tabindex="-1"
    aria-labelledby="customerDetailsLabel<?= $customer['Customer_ID'] ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-lg shadow border-0 overflow-hidden">

            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white fw-bold" id="customerDetailsLabel<?= $customer['Customer_ID'] ?>">
                    <i class="fas fa-user me-2"></i> Customer Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-0">
                <div class="row g-0 flex-nowrap">
                    <!-- Left: Image -->
                    <div class="col-md-5 bg-light d-flex align-items-center justify-content-center p-4">
                        <?php if (!empty($customer['File_Path'])): ?>
                            <img src="<?= $customer['File_Path'] ?>" class="img-fluid rounded shadow-sm"
                                alt="<?= htmlspecialchars($customer['Name']) ?>"
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
                        <?php
                        $statusClass = match ($customer['Status']) {
                            'Available' => 'bg-green-soft text-green',
                            'Unavailable' => 'bg-red-soft text-red',
                            default => 'bg-secondary'
                        };
                        
                        ?>
<h4 class="fw-bold mb-0"><?= htmlspecialchars($customer['Name']) ?></h4>
<span class="badge <?= $statusClass ?> text-white">
    Status: <?= htmlspecialchars($customer['Status']) ?>
</span>


                            
</div>
                        </div>

                        <div class="mb-3">
                            <h6 class="fw-bold"><i class="fas fa-truck me-1"></i> Shipping Address</h6>
                            <p><?= htmlspecialchars($customer['Shipping Address']) ?></p>
                        </div>

                        <div class="row small mb-3">

                        <div class="col-6 mb-2">
                                <strong><i class="fas fa-envelope me-1"></i>Email:</strong>
                                <div><?= htmlspecialchars($customer['Email']) ?></div>
                            </div>

                        <div class="col-6 mb-2">
                               <strong><i class="fas fa-solid fa-key"></i> Password:</strong>
                               <div><?= htmlspecialchars($customer['Password']) ?></div>
                            </div>


                            <div class="col-6 mb-2">
                                <strong><i class="fas fa-phone me-1"></i>Phone:</strong>
                                <div><?= htmlspecialchars($customer['Phone']) ?></div>
                            </div>

                            <div class="col-6 mb-2">
                                <strong><i class="fas fa-map me-1"></i>State:</strong>
                                <div><?= htmlspecialchars($customer['State_Name'] ?? 'N/A') ?></div>
                            </div>
                           
                            <div class="col-6 mb-2">
                                <strong><i class="fas fa-box-open me-1"></i>Orders:</strong>
                                <div><?= htmlspecialchars($customer['Orders']) ?></div>
                            </div>

                            <div class="col-6 mb-2">
                                <strong><i class="fas fa-money-bill-wave me-1"></i>Total Spend:</strong>
                                <div><?= htmlspecialchars($customer['Total_Spend']) ?> DZD</div>
                            </div>

                            
                        </div>

                        <!-- Bottom: ID + Actions -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-auto">
                            <small class="text-muted"><i class="fas fa-id-badge me-1"></i> ID: <?= $customer['Customer_ID'] ?></small>
                            <div>
                                <a href="customers_add_edit.php?id=<?= $customer['Customer_ID'] ?>" class="btn btn-warning btn-sm me-2">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $customer['Customer_ID'] ?>)">
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