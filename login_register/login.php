<?php
require_once "../includes/db.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Login - Inventory Management System</title>
    <link href="../css/styles.css" rel="stylesheet" />
    <link href="../css/vendor/bootstrap.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="../../assets/img/icon.svg" />
    <script data-search-pseudo-elements defer src="../../js/vendor/font-awesome.min.js" crossorigin="anonymous"></script>
    <script src="../../js/vendor/feather.min.js" crossorigin="anonymous"></script>
    
    <style>
        
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

        .card-body {
            padding: 2.5rem;
            position: relative;
            z-index: 2;
        }

        
        .form-control {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: var(--white);
            position: relative;
        }

        .form-control:focus {
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

      
        .small.mb-1 {
            font-weight: 600;
            color: #495057;
            font-size: 0.875rem;
            margin-bottom: 0.5rem !important;
            transition: color 0.3s ease;
        }

        .mb-3:focus-within .small.mb-1 {
            color: var(--primary-blue);
        }

        
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

       
        .alert {
            border: none;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            font-weight: 500;
            animation: slideDown 0.5s ease-out;
            position: relative;
            overflow: hidden;
        }

        .alert::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f1aeb5 100%);
            color: #721c24;
            box-shadow: 0 8px 25px rgba(220, 53, 69, 0.2);
        }

        .alert-danger::before {
            background: #dc3545;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            color: #856404;
            box-shadow: 0 8px 25px rgba(255, 193, 7, 0.2);
        }

        .alert-warning::before {
            background: #ffc107;
        }

        .alert-success {
            background: linear-gradient(135deg, #d1edff 0%, #b8daff 100%);
            color: #0f5132;
            box-shadow: 0 8px 25px rgba(25, 135, 84, 0.2);
        }

        .alert-success::before {
            background: #198754;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card-footer {
            background: var(--light-gray);
            border-radius: 0 0 20px 20px;
            border-top: 1px solid rgba(13, 110, 253, 0.1);
            padding: 1.5rem 2.5rem;
            position: relative;
            z-index: 2;
        }

        
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
        .form-control:focus {
            outline: none;
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
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header justify-content-center">
                                    <h3>Welcome Back</h3>
                                </div>
                                <div class="card-body">
                                    <?php
                                    if (isset($_GET['error']) && $_GET['error'] == 'invalid') {
                                        echo '<div class="alert alert-danger text-center">❌ Invalid email or password!</div>';
                                    }
                                    if (isset($_GET['error']) && $_GET['error'] == 'not_accepted') {
                                        echo '<div class="alert alert-warning text-center">⏳ Your account is not yet approved by the admin. Please wait for approval.</div>';
                                    }
                                    if (isset($_GET['registered']) && $_GET['registered'] == 'success') {
                                        if (isset($_GET['type']) && $_GET['type'] == 'user')
                                            echo '<div class="alert alert-success text-center">✅ Account registered successfully. Your account requires admin approval before you can log in.</div>';
                                        else
                                            echo '<div class="alert alert-success text-center">✅ Account registered successfully. Please log in.</div>';
                                    }
                                    ?>
                                    <form action="auth_login.php" method="POST" id="loginForm">
                                        <div class="mb-3">
                                            <label class="small mb-1" for="inputEmail">📧 Email Address</label>
                                            <input class="form-control" id="inputEmail" name="email" type="email" placeholder="Enter your email address" required />
                                        </div>

                                        <div class="mb-3">
                                            <label class="small mb-1" for="inputPassword">🔒 Password</label>
                                            <input class="form-control" id="inputPassword" name="password" type="password" placeholder="Enter your password" required />
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <a class="small" href="#">Forgot Password?</a>
                                            <button type="submit" class="btn btn-primary" id="loginBtn">
                                                Sign In
                                            </button>
                                        </div>
                                    </form>
                                        

                                </div>
                                <div class="card-footer text-center">
                                    <div class="small">
                                        <a href="register_select_role.php">Don't have an account? Create one here!</a>
                                    </div>

                                    <div class="small">
                                        <a href="guest_login.php">Or continue as a Guest!</a>
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
        
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const loginBtn = document.getElementById('loginBtn');
            const inputs = document.querySelectorAll('.form-control');
            
            
            loginForm.addEventListener('submit', function() {
                loginBtn.classList.add('btn-loading');
                loginBtn.innerHTML = 'Signing In...';
            });
            
           
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.closest('.mb-3').style.transform = 'translateX(5px)';
                });
                
                input.addEventListener('blur', function() {
                    this.closest('.mb-3').style.transform = 'translateX(0)';
                });
            });
            
            
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-20px)';
                    setTimeout(() => {
                        alert.style.display = 'none';
                    }, 300);
                }, 6000);
            });
        });
    </script>
</body>

</html>