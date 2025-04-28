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
    <link rel="icon" type="image/x-icon" href="/inventory_management_system/assets/img/favicon.png" />
    <script data-search-pseudo-elements defer src="/inventory_management_system/js/vendor/font-awesome.min.js" crossorigin="anonymous"></script>
    <script src="/inventory_management_system/js/vendor/feather.min.js" crossorigin="anonymous"></script>
</head>

<body class="bg-primary">
<div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
        <main>
            <div class="container-xl px-4">
                <div class="row justify-content-center">
                    <div class="col-lg-5">
                        <div class="card shadow-lg border-0 rounded-lg mt-5">
                            <div class="card-header justify-content-center"><h3 class="fw-light my-4">Login</h3></div>
                            <div class="card-body">
                                <?php
                                if (isset($_GET['error']) && $_GET['error'] == 'invalid') {
                                    echo '<div class="alert alert-danger text-center">Invalid email or password!</div>';
                                }
                                if (isset($_GET['registered']) && $_GET['registered'] == 'success') {
                                    echo '<div class="alert alert-success text-center">Account registered successfully. Please log in.</div>';
                                }
                                ?>
                                <form action="auth_login.php" method="POST">
                                    <div class="mb-3">
                                        <label class="small mb-1" for="inputEmail">Email</label>
                                        <input class="form-control" id="inputEmail" name="email" type="email" placeholder="Enter email" required />
                                    </div>

                                    <div class="mb-3">
                                        <label class="small mb-1" for="inputPassword">Password</label>
                                        <input class="form-control" id="inputPassword" name="password" type="password" placeholder="Enter password" required />
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                        <a class="small" href="#">Forgot Password?</a> <!-- Placeholder, can be linked later -->
                                        <button type="submit" class="btn btn-primary">Login</button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-center">
                                <div class="small">
                                    <a href="register_select_role.php">Don't have an account? Register!</a>
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

<script src="/inventory_management_system/js/vendor/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="/inventory_management_system/js/scripts.js"></script>
</body>
</html>
