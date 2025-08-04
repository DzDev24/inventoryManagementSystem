<?php
require_once "../includes/db.php";

// Fetch states
$states = $conn->query("SELECT State_ID, State_Name FROM states");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Register Supplier - Inventory System</title>
    <link href="../css/styles.css" rel="stylesheet" />
    <link href="../css/vendor/bootstrap.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="../../assets/img/icon.svg" />
    <script data-search-pseudo-elements defer src="../../js/vendor/font-awesome.min.js" crossorigin="anonymous"></script>
    <script src="../../js/vendor/feather.min.js" crossorigin="anonymous"></script>
    
    <style>
        /* Enhanced Register Supplier Page Styles - Blue Theme */
        :root {
            --primary-blue: #0d6efd;
            --primary-blue-dark: #0b5ed7;
            --primary-blue-light: #cfe2ff;
            --secondary-blue: #6ea8fe;
            --accent-blue: #3d8bfd;
            --white: #ffffff;
            --light-gray: #f8f9fa;
            --border-color: #dee2e6;
            --shadow-light: rgba(13, 110, 253, 0.1);
            --shadow-medium: rgba(13, 110, 253, 0.2);
            --shadow-strong: rgba(13, 110, 253, 0.3);
        }

        /* Blue gradient background */
        body.bg-primary {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 25%, #1e3c72 50%, #2a5298 75%, #1e3c72 100%);
            background-size: 400% 400%;
            animation: gradientMove 12s ease infinite;
            min-height: 100vh;
            position: relative;
        }

        @keyframes gradientMove {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        /* Floating geometric shapes */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
            pointer-events: none;
            z-index: 1;
        }

        #layoutAuthentication {
            position: relative;
            z-index: 2;
        }

        /* Enhanced card styling */
        .card.shadow-lg {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            box-shadow: 
                0 25px 50px rgba(13, 110, 253, 0.15),
                0 15px 35px rgba(13, 110, 253, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            animation: slideUp 0.8s ease-out;
            position: relative;
            overflow: hidden;
        }

        .card.shadow-lg::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(13, 110, 253, 0.1), transparent);
            animation: shimmer 3s infinite;
            z-index: 1;
        }


        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        /* Enhanced card header */
        .card-header {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--accent-blue) 100%);
            border-radius: 20px 20px 0 0 !important;
            border: none;
            padding: 2rem;
            position: relative;
            z-index: 2;
        }

        .card-header h3 {
            color: white;
            font-weight: 600;
            font-size: 1.8rem;
            margin: 0;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        /* Enhanced card body */
        .card-body {
            padding: 2.5rem;
            position: relative;
            z-index: 2;
        }

        /* Enhanced form inputs */
        .form-control,
        .form-select {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: var(--white);
            position: relative;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-blue);
            box-shadow: 
                0 0 0 0.2rem rgba(13, 110, 253, 0.1),
                0 8px 25px rgba(13, 110, 253, 0.15);
            background: var(--white);
            transform: translateY(-2px);
        }

        .form-control::placeholder {
            color: #adb5bd;
            transition: opacity 0.3s ease;
        }

        .form-control:focus::placeholder {
            opacity: 0.7;
        }

        /* Enhanced labels */
        .form-label {
            font-weight: 600;
            color: #495057;
            font-size: 0.875rem;
            margin-bottom: 0.5rem !important;
            transition: color 0.3s ease;
        }

        .mb-3:focus-within .form-label {
            color: var(--primary-blue);
        }

        /* Required field styling */
        .text-danger {
            color: #dc3545 !important;
        }

        /* Enhanced button */
        .btn.btn-primary {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--accent-blue) 100%);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(13, 110, 253, 0.3);
        }

        .btn.btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn.btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(13, 110, 253, 0.4);
        }

        .btn.btn-primary:hover::before {
            left: 100%;
        }

        .btn.btn-primary:active {
            transform: translateY(0);
        }

        /* Enhanced secondary button (remove image) */
        .btn.btn-outline-danger {
            border: 2px solid #dc3545;
            border-radius: 8px;
            color: #dc3545;
            background: transparent;
            transition: all 0.3s ease;
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
        }

        .btn.btn-outline-danger:hover {
            background: #dc3545;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        }

        /* Enhanced links */
        .small a {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }

        .small a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background: var(--primary-blue);
            transition: width 0.3s ease;
        }

        .small a:hover {
            color: var(--primary-blue-dark);
        }

        .small a:hover::after {
            width: 100%;
        }

        /* Enhanced card footer */
        .card-footer {
            background: var(--light-gray);
            border-radius: 0 0 20px 20px;
            border-top: 1px solid rgba(13, 110, 253, 0.1);
            padding: 1.5rem 2.5rem;
            position: relative;
            z-index: 2;
        }

        /* Enhanced footer - Fixed to match background */
        .footer-admin.footer-dark {
            background: transparent;
            backdrop-filter: none;
            color: rgba(255, 255, 255, 0.9);
            border-top: none;
        }

        .footer-admin.footer-dark a {
            color: rgba(255, 255, 255, 0.8);
            transition: color 0.3s ease;
        }

        .footer-admin.footer-dark a:hover {
            color: rgba(255, 255, 255, 1);
        }

        .footer-admin.footer-dark .small {
            color: rgba(255, 255, 255, 0.7);
        }

        /* Image preview styling */
        #imagePreview {
            border: 3px solid var(--primary-blue-light);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        #imagePreview:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(13, 110, 253, 0.2);
        }

        /* Add loading state */
        .btn-loading {
            pointer-events: none;
            position: relative;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            margin: auto;
            border: 2px solid transparent;
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Enhanced container spacing */
        .container-xl.px-4 {
            padding-top: 2rem;
            padding-bottom: 2rem;
        }

        /* Responsive improvements */
        @media (max-width: 576px) {
            .card-body {
                padding: 2rem 1.5rem;
            }
            
            .card-header {
                padding: 1.5rem;
            }
            
            .card-header h3 {
                font-size: 1.5rem;
            }
            
            .container-xl.px-4 {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }

        /* Form group enhancements */
        .mb-3 {
            position: relative;
            margin-bottom: 1.5rem !important;
        }

        /* Add focus ring to form controls */
        .form-control:focus,
        .form-select:focus {
            outline: none;
        }

        /* Textarea specific styling */
        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        /* File input styling */
        input[type="file"].form-control {
            padding: 0.5rem;
            border-style: dashed;
        }

        input[type="file"].form-control:focus {
            border-style: solid;
        }

        /* Grid spacing improvements */
        .row.gx-3 {
            margin-left: -0.75rem;
            margin-right: -0.75rem;
        }

        .row.gx-3 > * {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        /* Smooth transitions for all interactive elements */
        * {
            transition: color 0.3s ease, background-color 0.3s ease, border-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        }
    </style>
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
                                    <h3>Supplier Registration</h3>
                                </div>
                                <div class="card-body">
                                    <form action="auth_register_supplier.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                                        <div class="mb-3">
                                            <label class="form-label">👤 Supplier Name <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="supplier_name" required placeholder="Enter supplier name">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">🏪 Company Name <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="company_name" required placeholder="Enter company name">
                                        </div>

                                        <div class="row gx-3 mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label">🔒 Password <span class="text-danger">*</span></label>
                                                <input class="form-control" type="password" id="password" name="password" required placeholder="Enter password">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">🔒 Confirm Password <span class="text-danger">*</span></label>
                                                <input class="form-control" type="password" id="confirm_password" name="confirm_password" required placeholder="Confirm password">
                                            </div>
                                        </div>

                                        <div class="row gx-3 mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label">📧 Email <span class="text-danger">*</span></label>
                                                <input class="form-control" type="email" name="email" required placeholder="e.g. user@example.com"
                                                    pattern="[^@\s]+@[^@\s]+\.[^@\s]+">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">📞 Phone <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" name="phone" required placeholder="e.g. +213123456758 or 0555512345"
                                                    pattern="^(\+213|0)[0-9]{9}$">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">📍 Address <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="address" required placeholder="Enter address">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">🗺️ State <span class="text-danger">*</span></label>
                                            <select class="form-select" name="state_id" required>
                                                <option value="">-- Select State --</option>
                                                <?php while ($row = $states->fetch_assoc()): ?>
                                                    <option value="<?= htmlspecialchars($row['State_ID']) ?>">
                                                        <?= htmlspecialchars($row['State_Name']) ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">📝 Description (Optional)</label>
                                            <textarea class="form-control" name="description" rows="3" placeholder="Describe your company (optional)"></textarea>
                                        </div>

                                        <div class="row gx-3 mb-3 align-items-center">
                                            <div class="col-md-9">
                                                <label class="form-label">🖼️ Supplier Image (Optional)</label>
                                                <input class="form-control" type="file" name="image" id="imageInput" accept="image/*" onchange="previewImage(event)">
                                            </div>
                                            <div class="col-md-3 text-center">
                                                <img id="imagePreview" style="max-height: 150px; display: none;"
                                                    class="img-fluid rounded shadow-sm mt-3" alt="Preview">
                                                <button type="button" id="removeImageBtn"
                                                    class="btn btn-sm btn-outline-danger mt-2"
                                                    style="display: none;"
                                                    onclick="removeImage()">
                                                    <i class="fas fa-times me-1"></i> Remove Image
                                                </button>
                                            </div>
                                        </div>

                                        <div class="d-grid mt-4">
                                            <button class="btn btn-primary" type="submit" id="registerBtn">
                                            Register As A Supplier
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center">
                                    <div class="small">
                                        <a href="login.php">Already have an account? Login here!</a>
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
        // Enhanced form interactions
        document.addEventListener('DOMContentLoaded', function() {
            const registerForm = document.querySelector('form');
            const registerBtn = document.getElementById('registerBtn');
            const inputs = document.querySelectorAll('.form-control, .form-select');
            
            // Add loading state to register button
            registerForm.addEventListener('submit', function() {
                registerBtn.classList.add('btn-loading');
                registerBtn.innerHTML = 'Registering...';
            });
            
            // Enhanced input focus effects
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.closest('.mb-3').style.transform = 'translateX(5px)';
                });
                
                input.addEventListener('blur', function() {
                    this.closest('.mb-3').style.transform = 'translateX(0)';
                });
            });
        });

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