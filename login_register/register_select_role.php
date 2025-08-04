<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Choose role to register" />
    <meta name="author" content="" />
    <title>Choose Role - Register | Inventory System</title>
    <link href="../css/styles.css" rel="stylesheet" />
    <link href="../css/vendor/bootstrap.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="../assets/img/icon.svg" />
    <script data-search-pseudo-elements defer src="../js/vendor/all.min.js" crossorigin="anonymous"></script>
    <script src="../js/vendor/feather.min.js" crossorigin="anonymous"></script>
    
    <style>
        
        :root {
            --primary-blue: #0d6efd;
            --primary-blue-dark: #0b5ed7;
            --primary-blue-light: #cfe2ff;
            --secondary-blue: #6ea8fe;
            --accent-blue: #3d8bfd;
            --success-green: #198754;
            --success-green-dark: #157347;
            --info-cyan: #0dcaf0;
            --info-cyan-dark: #0aa2c0;
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

        .card-body p {
            color: #495057;
            font-size: 1.1rem;
            font-weight: 500;
            margin-bottom: 2rem;
        }

        
        .d-grid {
            gap: 1.5rem !important;
        }

        .btn.btn-lg {
            border-radius: 15px;
            padding: 1rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            border: none;
            text-decoration: none;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(10px);
        }

        .btn.btn-lg::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn.btn-lg:hover::before {
            left: 100%;
        }

        .btn.btn-lg:hover {
            transform: translateY(-3px);
            text-decoration: none;
        }

        .btn.btn-lg:active {
            transform: translateY(-1px);
        }

        
        .btn.btn-primary {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--accent-blue) 100%);
            color: white;
        }

        .btn.btn-primary:hover {
            box-shadow: 0 12px 35px rgba(13, 110, 253, 0.4);
            color: white;
        }

        
        .btn.btn-success {
            background: linear-gradient(135deg, var(--success-green) 0%, #20c997 100%);
            color: white;
        }

        .btn.btn-success:hover {
            box-shadow: 0 12px 35px rgba(25, 135, 84, 0.4);
            color: white;
        }

        
        .btn.btn-info {
            background: linear-gradient(135deg, var(--info-cyan) 0%, #6f42c1 100%);
            color: white;
        }

        .btn.btn-info:hover {
            box-shadow: 0 12px 35px rgba(13, 202, 240, 0.4);
            color: white;
        }

       
        .btn i {
            font-size: 1.2rem;
            margin-right: 0.75rem;
            transition: transform 0.3s ease;
        }

        .btn:hover i {
            transform: scale(1.1);
        }

        
        .card-footer {
            background: var(--light-gray);
            border-radius: 0 0 20px 20px;
            border-top: 1px solid rgba(13, 110, 253, 0.1);
            padding: 1.5rem 2.5rem;
            position: relative;
            z-index: 2;
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
            text-decoration: none;
        }

        .small a:hover::after {
            width: 100%;
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

       
        .container-xl.px-4 {
            padding-top: 2rem;
            padding-bottom: 2rem;
        }

        
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

            .btn.btn-lg {
                padding: 0.75rem 1.5rem;
                font-size: 1rem;
            }
        }

       
        .btn:nth-child(1) {
            animation: slideUp 0.8s ease-out 0.1s both;
        }

        .btn:nth-child(2) {
            animation: slideUp 0.8s ease-out 0.2s both;
        }

        .btn:nth-child(3) {
            animation: slideUp 0.8s ease-out 0.3s both;
        }

        
        * {
            transition: color 0.3s ease, background-color 0.3s ease, border-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        }

       
        .card-body p {
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        
        @keyframes pulse {
            0% { box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15); }
            50% { box-shadow: 0 12px 35px rgba(0, 0, 0, 0.25); }
            100% { box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15); }
        }
    </style>
</head>

<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container-xl px-4">
                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header justify-content-center">
                                    <h3>Register As</h3>
                                </div>
                                <div class="card-body text-center">
                                    <p class="mb-4">Please select your role to continue registration:</p>
                                    <div class="d-grid gap-3">
                                        <a class="btn btn-primary btn-lg" href="register_supplier.php">
                                            <i class="fas fa-truck me-2"></i> Supplier
                                        </a>
                                        <a class="btn btn-success btn-lg" href="register_customer.php">
                                            <i class="fas fa-user me-2"></i> Customer
                                        </a>
                                        <a class="btn btn-info btn-lg" href="register_user.php">
                                            <i class="fas fa-users-cog me-2"></i> Staff User
                                        </a>
                                    </div>
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
                        <div class="col-md-6 small">Copyright &copy; Your Company 2025</div>
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
    <script src="../js/vendor/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>

    <script>
        // Enhanced button interactions
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.btn.btn-lg');
            
            // Add enhanced hover effects
            buttons.forEach((button, index) => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px) scale(1.02)';
                });
                
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
                
                button.addEventListener('mousedown', function() {
                    this.style.transform = 'translateY(-2px) scale(0.98)';
                });
                
                button.addEventListener('mouseup', function() {
                    this.style.transform = 'translateY(-5px) scale(1.02)';
                });
            });
        });
    </script>
</body>

</html>