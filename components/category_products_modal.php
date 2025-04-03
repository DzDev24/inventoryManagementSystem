<?php
if (!isset($category)) return;


?>

<div class="modal fade" id="productsModal<?= $category['Category_ID'] ?>" tabindex="-1" aria-labelledby="productsModalLabel<?= $category['Category_ID'] ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-lg border-0 shadow">

            <div class="modal-header bg-info text-secondary">
                <h5 class="modal-title fw-bold" id="productsModalLabel<?= $category['Category_ID'] ?>">
                    <i class="fas fa-boxes me-2"></i> Products in <?= htmlspecialchars($category['Category_Name']) ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <?php if (count($products) > 0): ?>
                    <div class="table-responsive">
                        <table id="datatableCategory<?= $category['Category_ID'] ?>" class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>Product Name</th>
                                    <th class="text-end">Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $prod): ?>
                                    <tr>
                                        <td>
                                            <i class="fas fa-box-open text-primary me-2"></i>
                                            <?= htmlspecialchars($prod['Product_Name']) ?>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-primary-soft text-primary px-3 py-2">
                                                <strong><?= $prod['Quantity'] ?></strong>
                                                <?= $prod['Unit_abrev'] ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning text-center mb-0">
                        <i class="fas fa-info-circle me-2"></i> No products found in this category.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>