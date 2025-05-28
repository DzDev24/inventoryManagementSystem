<?php
require_once "../includes/db.php";

// Fetch roles from database (excluding Customer and Supplier roles)
$roles = $conn->query("SELECT Role_ID, Role_Name FROM roles WHERE Role_Name IN ('Admin', 'Product Manager', 'Sales Manager')");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Register User - Inventory System</title>
    <link href="../css/vendor/bootstrap.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="../../assets/img/favicon.png" />
    <script data-search-pseudo-elements defer src="../../js/vendor/font-awesome.min.js" crossorigin="anonymous"></script>
    <script src="../../js/vendor/feather.min.js" crossorigin="anonymous"></script>
</head>

<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container-xl px-4">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header justify-content-center">
                                    <h3 class="fw-light my-4">User Registration</h3>
                                </div>
                                <div class="card-body">
                                    <form action="auth_register_user.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                                        <div class="mb-3">
                                            <label class="form-label">Username <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="username" required placeholder="Enter username">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Real Name <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="real_name" required placeholder="Enter real name">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Email <span class="text-danger">*</span></label>
                                            <input class="form-control" type="email" name="email" required placeholder="e.g. user@example.com"
                                                pattern="[^@\s]+@[^@\s]+\.[^@\s]+">
                                        </div>

                                        <div class="row gx-3 mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                                <input class="form-control" type="password" id="password" name="password" required placeholder="Enter password">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                                <input class="form-control" type="password" id="confirm_password" name="confirm_password" required placeholder="Confirm password">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Role <span class="text-danger">*</span></label>
                                            <select class="form-select" name="role_id" required>
                                                <option value="">-- Select Role --</option>
                                                <?php while ($row = $roles->fetch_assoc()): ?>
                                                    <option value="<?= htmlspecialchars($row['Role_ID']) ?>">
                                                        <?= htmlspecialchars($row['Role_Name']) ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">User Image (Optional)</label>
                                            <input class="form-control" type="file" name="image" id="imageInput" accept="image/*" onchange="previewImage(event)">
                                        </div>

                                        <div class="text-center">
                                            <img id="imagePreview" style="max-height: 150px; display: none;" class="img-fluid rounded shadow-sm mt-3" alt="Preview">
                                            <button type="button" id="removeImageBtn" class="btn btn-sm btn-outline-danger mt-2" style="display: none;" onclick="removeImage()">
                                                <i class="fas fa-times me-1"></i> Remove Image
                                            </button>
                                        </div>

                                        <div class="d-grid mt-4">
                                            <button class="btn btn-primary" type="submit">Register As A User</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center">
                                    <div class="small">
                                        <a href="login.php">Already have an account? Login!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutAuthentication_footer">
            <footer class="footer-admin mt-auto footer-dark">
                <div class="container-xl px-4">
                    <div class="row">
                        <div class="col-md-6 small">Copyright &copy; Inventory System 2025</div>
                        <div class="col-md-6 text-md-end small">
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="../../js/vendor/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../../js/scripts.js"></script>
    <script>
        function validateForm() {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirm_password').value;
            if (password !== confirm) {
                alert('Passwords do not match!');
                return false;
            }
            return true;
        }

        function previewImage(event) {
            const preview = document.getElementById('imagePreview');
            const removeBtn = document.getElementById('removeImageBtn');
            const file = event.target.files[0];
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
                removeBtn.style.display = 'inline-block';
            }
        }

        function removeImage() {
            const input = document.getElementById('imageInput');
            const preview = document.getElementById('imagePreview');
            const removeBtn = document.getElementById('removeImageBtn');

            input.value = '';
            preview.src = '#';
            preview.style.display = 'none';
            removeBtn.style.display = 'none';
        }
    </script>

</body>

</html>