<?php if (!isset($user)) return; ?>

<div class="modal fade" id="viewModalDetails<?= $user['User_ID'] ?>" tabindex="-1"
    aria-labelledby="userDetailsLabel<?= $user['User_ID'] ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-lg shadow border-0 overflow-hidden">

            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white fw-bold" id="userDetailsLabel<?= $user['User_ID'] ?>">
                    <i class="fas fa-user me-2"></i> User Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-0">
                <div class="row g-0 flex-nowrap">
                    <!-- Left: Image -->
                    <div class="col-md-5 bg-light d-flex align-items-center justify-content-center p-4">
                        <?php if (!empty($user['File_Path'])): ?>
                            <img src="<?= $user['File_Path'] ?>" class="img-fluid rounded shadow-sm"
                                alt="<?= htmlspecialchars($user['Real_Name']) ?>"
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
                                <h4 class="fw-bold mb-0"><?= htmlspecialchars($user['Real_Name']) ?></h4>
                                <span class="badge <?= $user['Status'] === 'Online' ? 'bg-success' : 'bg-secondary' ?>">
                                    Status: <?= htmlspecialchars($user['Status']) ?>
                                </span>
                            </div>
                            <small class="text-muted">Username: @<?= htmlspecialchars($user['Username']) ?></small>
                        </div>

                        <div class="row small mb-3">
                            <div class="col-6 mb-2">
                                <strong><i class="fas fa-envelope me-1"></i>Email:</strong>
                                <div><?= htmlspecialchars($user['Email']) ?></div>
                            </div>



                            <div class="col-6 mb-2">
                                <strong><i class="fas fa-user-tag me-1"></i>Role:</strong>
                                <div><?= htmlspecialchars($user['Role_Name'] ?? 'N/A') ?></div>
                            </div>

                            <div class="col-6 mb-2">
                                <strong><i class="fas fa-clock me-1"></i>Last Login:</strong>
                                <div><?= $user['Last_Login'] ? date('Y-m-d H:i', strtotime($user['Last_Login'])) : 'Never' ?></div>
                            </div>
                        </div>



                    </div>
                </div>
            </div>

        </div>
    </div>
</div>