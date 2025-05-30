<?php
require_once "../login_register/auth_session.php";
require_once "../includes/db.php";

// Restrict to suppliers only
if ($_SESSION['user_type'] !== 'supplier') {
    header("Location: ../login_register/login.php");
    exit;
}

$supplier_id = $_SESSION['supplier_id'];
$supplier = $conn->query("SELECT * FROM supplier WHERE Supplier_ID = $supplier_id")->fetch_assoc();
$states = $conn->query("SELECT * FROM states")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Supplier Profile</title>
    <link href="../css/vendor/bootstrap.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <link rel="icon" href="../assets/img/icon.svg" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/js/all.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js"></script>
</head>

<body>
    <div class="container py-5">
        <h2 class="mb-4">My Profile</h2>
        <div class="row">
            <!-- Profile Picture Card -->
            <div class="col-xl-4">
                <div class="card mb-4">
                    <div class="card-header">Profile Picture</div>
                    <div class="card-body text-center">
                        <?php
                        $profileImage = 'assets/img/illustrations/profiles/profile-1.png';
                        if (!empty($supplier['Media_ID'])) {
                            $stmt = $conn->prepare("SELECT File_Path FROM media WHERE Media_ID = ?");
                            $stmt->bind_param("i", $supplier['Media_ID']);
                            $stmt->execute();
                            $res = $stmt->get_result()->fetch_assoc();
                            if ($res && file_exists('../' . $res['File_Path'])) {
                                $profileImage = '../' . $res['File_Path'];
                            }
                        }
                        ?>
                        <img id="profilePreview" class="img-account-profile rounded-circle mb-2" src="<?= htmlspecialchars($profileImage) ?>" alt="Profile Image" style="width: 160px; height: 160px; object-fit: cover;" />
                        <div class="small text-muted mb-2">JPG or PNG no larger than 5MB</div>
                    </div>
                </div>
            </div>

            <!-- Account Details Form -->
            <div class="col-xl-8">
                <div class="card mb-4">
                    <div class="card-header">Account Details</div>
                    <div class="card-body">
                        <form method="POST" action="update_supplier_profile.php" enctype="multipart/form-data">
                            <input type="hidden" name="supplier_id" value="<?= $supplier_id ?>">

                            <div class="mb-3">
                                <label class="small mb-1" for="supplier_name">Supplier Name</label>
                                <input class="form-control" id="supplier_name" name="supplier_name" type="text" required value="<?= htmlspecialchars($supplier['Supplier_Name']) ?>" />
                            </div>

                            <div class="mb-3">
                                <label class="small mb-1" for="company_name">Company Name</label>
                                <input class="form-control" id="company_name" name="company_name" type="text" value="<?= htmlspecialchars($supplier['Company_Name']) ?>" />
                            </div>

                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1" for="password">New Password</label>
                                    <input class="form-control" id="password" name="password" type="password" placeholder="Leave blank to keep current" />
                                </div>
                                <div class="col-md-6">
                                    <label class="small mb-1" for="confirm_password">Confirm Password</label>
                                    <input class="form-control" id="confirm_password" name="confirm_password" type="password" />
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="small mb-1" for="email">Email</label>
                                <input class="form-control" id="email" name="email" type="email" required value="<?= htmlspecialchars($supplier['Email']) ?>" />
                            </div>

                            <div class="mb-3">
                                <label class="small mb-1" for="phone">Phone</label>
                                <input class="form-control" id="phone" name="phone" type="text" value="<?= htmlspecialchars($supplier['Phone']) ?>" />
                            </div>

                            <div class="mb-3">
                                <label class="small mb-1" for="address">Shipping Address</label>
                                <input class="form-control" id="address" name="address" type="text" value="<?= htmlspecialchars($supplier['Address']) ?>" />
                            </div>

                            <div class="mb-3">
                                <label class="small mb-1" for="state_id">State</label>
                                <select name="state_id" class="form-control" required>
                                    <?php foreach ($states as $state): ?>
                                        <option value="<?= $state['State_ID'] ?>" <?= $supplier['State_ID'] == $state['State_ID'] ? 'selected' : '' ?>>
                                            <?= $state['State_Name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="d-flex justify-content-center gap-3 mt-4">
                                <input type="file" name="profile_picture" id="profile_picture" accept="image/*" onchange="previewImage(event)" class="d-none">
                                <label for="profile_picture" class="btn btn-outline-primary">
                                    <i class="fas fa-upload me-1"></i> Choose New Image
                                </label>
                                <button class="btn btn-primary" type="submit">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php include '../includes/footer.php'; ?>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const img = document.getElementById('profilePreview');
                if (img) img.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

</body>

</html>