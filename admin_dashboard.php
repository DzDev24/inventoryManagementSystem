<?php

require_once "./login_register/auth_session.php";


if ($_SESSION['user_role'] != 1) {
    header("Location: ./unauthorized.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Administrator Dashboard" />
    <meta name="author" content="" />
    <title>Administrator Dashboard - Inventory System</title>
    <link href="./css/vendor/litepicker.css" rel="stylesheet" />
    <link href="css/vendor/datatables-style.min.css" rel="stylesheet" />
    <link href="css/vendor/bootstrap.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/icon.svg" />
    <script data-search-pseudo-elements defer src="js/vendor/font-awesome.min.js" crossorigin="anonymous"></script>
    <script src="js/vendor/feather.min.js" crossorigin="anonymous"></script>
    <link href="css/styles.css" rel="stylesheet" />
    <?php include 'includes/common_head_elements.php'; ?>

</head>

<body class="nav-fixed">
    <?php include 'includes/header.php'; ?>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <?php include 'includes/sidebar.php'; ?>
        </div>
        <div id="layoutSidenav_content">
            <main>


                <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
                    <div class="container-fluid px-4">
                        <div class="page-header-content">
                            <div class="row align-items-center justify-content-between pt-3">
                                <div class="col-auto mb-3">
                                    <h1 class="page-header-title">
                                        <div class="page-header-icon"><i data-feather="shield"></i></div>
                                        Administrator Dashboard
                                    </h1>
                                </div>
                                <div class="col-auto mb-3">
                                    <div class="small">
                                        <span class="fw-500 text-primary" id="current-day"></span>
                                        &middot; <span id="current-date"></span>
                                        &middot; <span id="current-time"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <div class="container-fluid px-4 mt-4">
                    <!-- Welcome Card -->
                    <div class="card card-waves mb-4 mt-5">
                        <div class="card-body p-5">
                            <div class="row align-items-center justify-content-between">
                                <div class="col">
                                    <h2 class="text-primary">Welcome back, Administrator!</h2>
                                    <p class="text-gray-700">Monitor all aspects of your inventory management system - sales, products, customers, suppliers, and system health.</p>
                                </div>
                                <div class="col d-none d-lg-block mt-xxl-n4">
                                    <img class="img-fluid px-xl-4 mt-xxl-n5" src="assets/img/illustrations/statistics.svg" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System Stats Cards -->
                    <div class="row">
                        <!-- Monthly Revenue -->
                        <div class="col-lg-6 col-xl-3 mb-4">
                            <div class="card bg-primary text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">Monthly Revenue</div>
                                            <div class="text-lg fw-bold" id="monthlyRevenue">Loading...</div>
                                            <div class="text-white-75 small" id="revenueChange"></div>
                                        </div>
                                        <i class="feather-xl text-white-50" data-feather="dollar-sign"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between small">
                                    <a class="text-white stretched-link" href="sales_list.php">View Sales</a>
                                    <div class="text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>

                        <!-- New Signups -->
                        <div class="col-lg-6 col-xl-3 mb-4">
                            <div class="card bg-success text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">New Signups (30d)</div>
                                            <div class="text-lg fw-bold" id="newSignups">Loading...</div>
                                            <div class="text-white-75 small" id="signupsChange"></div>
                                        </div>
                                        <i class="feather-xl text-white-50" data-feather="users"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between small">
                                    <a class="text-white stretched-link" href="customers_list.php">View Customers</a>
                                    <div class="text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>

                        <!-- Inventory Value -->
                        <div class="col-lg-6 col-xl-3 mb-4">
                            <div class="card bg-warning text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">Inventory Value</div>
                                            <div class="text-lg fw-bold" id="inventoryValue">Loading...</div>
                                            <div class="text-white-75 small" id="inventoryChange"></div>
                                        </div>
                                        <i class="feather-xl text-white-50" data-feather="package"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between small">
                                    <a class="text-white stretched-link" href="products_list.php">View Inventory</a>
                                    <div class="text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>

                        <!-- Low Stock Items -->
                        <div class="col-lg-6 col-xl-3 mb-4">
                            <div class="card bg-danger text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">Low Stock Items</div>
                                            <div class="text-lg fw-bold" id="lowStockCount">Loading...</div>
                                            <div class="text-white-75 small">Needs attention</div>
                                        </div>
                                        <i class="feather-xl text-white-50" data-feather="alert-triangle"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between small">
                                    <a class="text-white stretched-link" href="products_list.php">View Low Stock</a>
                                    <div class="text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row -->
                    <div class="row">
                        <!-- Revenue Trend -->
                        <div class="col-xl-6 mb-4">
                            <div class="card card-header-actions h-100">
                                <div class="card-header">
                                    Monthly Sales Revenue
                                    <div class="dropdown no-caret">
                                        <button class="btn btn-transparent-dark btn-icon dropdown-toggle" id="revenueTrendDropdown" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="text-gray-500" data-feather="more-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end animated--fade-in-up" aria-labelledby="revenueTrendDropdown">
                                            <a class="dropdown-item" href="#" onclick="updateRevenueTrendChart('3')">Last 3 Months</a>
                                            <a class="dropdown-item" href="#" onclick="updateRevenueTrendChart('6')">Last 6 Months</a>
                                            <a class="dropdown-item" href="#" onclick="updateRevenueTrendChart('12')">Last 12 Months</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="revenueTrendChart" width="100%" height="30"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- User Roles Distribution -->
                        <div class="col-xl-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header">
                                    User Roles Distribution
                                    <div class="dropdown no-caret">

                                        <div class="dropdown-menu dropdown-menu-end animated--fade-in-up" aria-labelledby="rolesDropdown">
                                            <h6 class="dropdown-header">Filter By:</h6>
                                            <a class="dropdown-item" href="#" onclick="updateRolesChart('all')">All Users</a>
                                            <a class="dropdown-item" href="#" onclick="updateRolesChart('active')">Active Users</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="chart-pie mb-4">
                                        <canvas id="rolesDistributionChart" width="100%" height="50"></canvas>
                                    </div>
                                    <div class="list-group list-group-flush small" id="rolesLegend">
                                        <!-- Legend will be populated by JavaScript -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Activity and System Health -->
                    <div class="row">
                        <!-- Recent System Activity -->
                        <div class="col-lg-6 mb-4">
                            <div class="card card-header-actions h-100">
                                <div class="card-header">
                                    Recent System Activity
                                    <div class="dropdown no-caret">
                                        <button class="btn btn-transparent-dark btn-icon dropdown-toggle" id="activityDropdown" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="text-gray-500" data-feather="more-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end animated--fade-in-up" aria-labelledby="activityDropdown">
                                            <h6 class="dropdown-header">Filter Activity:</h6>
                                            <a class="dropdown-item" href="#" onclick="filterActivity('all')">All Activities</a>
                                            <a class="dropdown-item" href="#" onclick="filterActivity('login')">Logins</a>
                                            <a class="dropdown-item" href="#" onclick="filterActivity('purchase')">Purchases</a>
                                            <a class="dropdown-item" href="#" onclick="filterActivity('sale')">Sales</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="timeline timeline-xs" id="systemActivityTimeline">
                                        <!-- Timeline items will be populated by JavaScript -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- System Health -->
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header">
                                    System Health
                                    <div class="badge bg-green-soft text-green">Operational</div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6 mb-4">
                                            <div class="card bg-blue-soft h-100">
                                                <div class="card-body text-center">
                                                    <i class="feather-xl text-blue mb-3" data-feather="database"></i>
                                                    <div class="h5" id="databaseStatus">Online</div>
                                                    <div class="small text-muted">Database</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-4">
                                            <div class="card bg-green-soft h-100">
                                                <div class="card-body text-center">
                                                    <i class="feather-xl text-green mb-3" data-feather="server"></i>
                                                    <div class="h5" id="serverStatus">Online</div>
                                                    <div class="small text-muted">Web Server</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="card bg-yellow-soft h-100">
                                                <div class="card-body text-center">
                                                    <i class="feather-xl text-yellow mb-3" data-feather="hard-drive"></i>
                                                    <div class="h5" id="storageStatus">Normal</div>
                                                    <div class="small text-muted">Storage</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="card bg-purple-soft h-100">
                                                <div class="card-body text-center">
                                                    <i class="feather-xl text-purple mb-3" data-feather="shield"></i>
                                                    <div class="h5" id="securityStatus">Secure</div>
                                                    <div class="small text-muted">Security</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Customers and Recent Purchases -->
                    <div class="row">
                        <!-- Top Customers -->
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header">
                                    Top Customers by Spending
                                    <a class="btn btn-sm btn-primary-soft text-primary" href="customers_list.php">View All</a>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Customer</th>
                                                    <th>Orders</th>
                                                    <th>Total Spend</th>
                                                    <th>Location</th>
                                                </tr>
                                            </thead>
                                            <tbody id="topCustomersTable">
                                                <!-- Customer data will be populated by JavaScript -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Purchases -->
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header">
                                    Recent Purchases
                                    <a class="btn btn-sm btn-success-soft text-success" href="purchases_list.php">View All</a>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Supplier</th>
                                                    <th>Products</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody id="recentPurchasesTable">
                                                <!-- Purchases data will be populated by JavaScript -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reports and Quick Actions -->



                    <!-- Reports Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <i class="fas fa-file-pdf me-2"></i> Generate Reports
                        </div>
                        <div class="list-group list-group-flush small">
                            <a class="list-group-item list-group-item-action" href="reports/sales_performance_report.php" target="_blank">
                                <i class="fas fa-chart-line fa-fw text-primary me-2"></i>
                                Sales Report
                            </a>
                            <a class="list-group-item list-group-item-action" href="reports/purchases_report.php" target="_blank">
                                <i class="fas fa-dollar-sign fa-fw text-success me-2"></i>
                                Purchases report
                            </a>
                            <a class="list-group-item list-group-item-action" href="reports/customer_report.php" target="_blank">
                                <i class="fas fa-users fa-fw text-warning me-2"></i>
                                Customer Analysis Report
                            </a>
                            <a class="list-group-item list-group-item-action" href="reports/supplier_performance_report.php" target="_blank">
                                <i class="fas fa-truck fa-fw text-warning me-2"></i>
                                Supplier performace report
                            </a>
                        </div>
                    </div>



                </div>

            </main>
            <?php include 'includes/footer.php'; ?>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="./js/vendor/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="./js/vendor/Chart.min.js" crossorigin="anonymous"></script>
    <script src="./js/vendor/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="./js/vendor/bundle.js" crossorigin="anonymous"></script>
    <script src="./js/vendor/html2pdf.bundle.min.js"></script>

    <!-- Custom JavaScript for Admin Dashboard -->
    <script>
        // Global chart variables
        let revenueTrendChart;
        let rolesDistributionChart;

        // Document ready function
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize charts and load data
            initializeCharts();
            loadDashboardData();

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Feather icons
            feather.replace();

            // Update date and time
            updateDateTime();
            setInterval(updateDateTime, 60000);
        });

        function initializeCharts() {
            // Revenue Trend Chart
            var revenueCtx = document.getElementById('revenueTrendChart').getContext('2d');
            revenueTrendChart = new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Revenue',
                        data: [],
                        backgroundColor: 'rgba(78, 115, 223, 0.05)',
                        borderColor: 'rgba(78, 115, 223, 1)',
                        pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                        borderWidth: 2,
                        fill: true
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Roles Distribution Chart
            var rolesCtx = document.getElementById('rolesDistributionChart').getContext('2d');
            rolesDistributionChart = new Chart(rolesCtx, {
                type: 'doughnut',
                data: {
                    labels: [],
                    datasets: [{
                        data: [],
                        backgroundColor: [
                            'rgba(78, 115, 223, 0.8)',
                            'rgba(54, 185, 204, 0.8)',
                            'rgba(28, 200, 138, 0.8)',
                            'rgba(246, 194, 62, 0.8)',
                            'rgba(231, 74, 59, 0.8)',
                            'rgba(142, 68, 173, 0.8)'
                        ],
                        hoverBackgroundColor: [
                            'rgba(78, 115, 223, 1)',
                            'rgba(54, 185, 204, 1)',
                            'rgba(28, 200, 138, 1)',
                            'rgba(246, 194, 62, 1)',
                            'rgba(231, 74, 59, 1)',
                            'rgba(142, 68, 173, 1)'
                        ],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    cutoutPercentage: 70,
                    legend: {
                        display: false
                    }
                }
            });
        }

        // Load all dashboard data
        function loadDashboardData() {
            // Load stats cards
            fetch('api/admin-stats.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('monthlyRevenue').textContent = formatCurrency(data.monthlyRevenue);
                    document.getElementById('newSignups').textContent = data.newSignups;
                    document.getElementById('inventoryValue').textContent = formatCurrency(data.inventoryValue);
                    document.getElementById('lowStockCount').textContent = data.lowStockCount;

                    // Update change indicators
                    updateChangeIndicator('revenueChange', data.revenueChange);
                    updateChangeIndicator('signupsChange', data.signupsChange);
                    updateChangeIndicator('inventoryChange', data.inventoryChange);
                });

            // Load revenue trend chart data
            updateRevenueTrendChart('6');

            // Load roles distribution data
            updateRolesChart('all');

            // Load recent system activity
            fetch('api/recent-system-activity.php')
                .then(response => response.json())
                .then(data => {
                    let timelineHtml = '';
                    data.forEach(activity => {
                        let iconColor = '';
                        let icon = '';

                        if (activity.type === 'login') {
                            iconColor = 'bg-blue';
                            icon = 'log-in';
                        } else if (activity.type === 'purchase') {
                            iconColor = 'bg-green';
                            icon = 'shopping-cart';
                        } else if (activity.type === 'sale') {
                            iconColor = 'bg-purple';
                            icon = 'dollar-sign';
                        } else if (activity.type === 'product') {
                            iconColor = 'bg-yellow';
                            icon = 'package';
                        } else {
                            iconColor = 'bg-gray';
                            icon = 'activity';
                        }

                        timelineHtml += `
                            <div class="timeline-item" data-type="${activity.type}">
                                <div class="timeline-item-marker">
                                    <div class="timeline-item-marker-text">${activity.time}</div>
                                    <div class="timeline-item-marker-indicator ${iconColor}"></div>
                                </div>
                                <div class="timeline-item-content">
                                    <i class="me-1" data-feather="${icon}"></i>
                                    ${activity.description}
                                </div>
                            </div>
                        `;
                    });
                    document.getElementById('systemActivityTimeline').innerHTML = timelineHtml;
                    feather.replace();
                });

            // Load top customers
            fetch('api/top-customers2.php')
                .then(response => response.json())
                .then(data => {
                    let customersHtml = '';
                    data.forEach(customer => {
                        customersHtml += `
                            <tr>
                                <td>${customer.name}</td>
                                <td>${customer.orders}</td>
                                <td>${formatCurrency(customer.total_spend)}</td>
                                <td>${customer.location}</td>
                            </tr>
                        `;
                    });
                    document.getElementById('topCustomersTable').innerHTML = customersHtml;
                });

            // Load recent purchases
            fetch('api/recent-purchases.php')
                .then(response => response.json())
                .then(data => {
                    let purchasesHtml = '';
                    data.forEach(purchase => {
                        purchasesHtml += `
                            <tr>
                                <td>${purchase.supplier}</td>
                                <td>${purchase.products}</td>
                                <td>${formatCurrency(purchase.amount)}</td>
                                <td><span class="badge ${purchase.status === 'Recieved' ? 'bg-green-soft text-green' : 'bg-yellow-soft text-yellow'}">${purchase.status}</span></td>
                            </tr>
                        `;
                    });
                    document.getElementById('recentPurchasesTable').innerHTML = purchasesHtml;
                });
        }

        // Update revenue trend chart based on months
        function updateRevenueTrendChart(months) {
            fetch(`api/sales-trends.php?months=${months}`)
                .then(response => response.json())
                .then(data => {
                    revenueTrendChart.data.labels = data.labels;
                    revenueTrendChart.data.datasets[0].data = data.data;
                    revenueTrendChart.update();
                });
        }

        // Update roles distribution chart
        function updateRolesChart(filter) {
            fetch(`api/roles-distribution.php?filter=${filter}`)
                .then(response => response.json())
                .then(data => {
                    rolesDistributionChart.data.labels = data.labels;
                    rolesDistributionChart.data.datasets[0].data = data.data;
                    rolesDistributionChart.update();

                    // Update legend
                    let legendHtml = '';
                    data.labels.forEach((label, index) => {
                        const color = rolesDistributionChart.data.datasets[0].backgroundColor[index];
                        legendHtml += `
                            <div class="list-group-item d-flex align-items-center justify-content-between small px-0 py-2">
                                <div class="me-3">
                                    <i class="fas fa-circle fa-sm me-1" style="color: ${color}"></i>
                                    ${label}
                                </div>
                                <div class="fw-500 text-dark">${data.data[index]} (${data.percentages[index]}%)</div>
                            </div>
                        `;
                    });
                    document.getElementById('rolesLegend').innerHTML = legendHtml;
                });
        }

        // Filter activity timeline
        function filterActivity(type) {
            const items = document.querySelectorAll('#systemActivityTimeline .timeline-item');
            items.forEach(item => {
                if (type === 'all' || item.getAttribute('data-type') === type) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Update change indicator with arrow and color
        function updateChangeIndicator(elementId, change) {
            const element = document.getElementById(elementId);
            if (!element) return;

            if (change > 0) {
                element.innerHTML = `<i class="fas fa-arrow-up text-success"></i> ${Math.abs(change)}% increase`;
                element.classList.add('text-success');
                element.classList.remove('text-danger');
            } else if (change < 0) {
                element.innerHTML = `<i class="fas fa-arrow-down text-danger"></i> ${Math.abs(change)}% decrease`;
                element.classList.add('text-danger');
                element.classList.remove('text-success');
            } else {
                element.innerHTML = 'No change';
                element.classList.remove('text-success', 'text-danger');
            }
        }

        // Format currency
        function formatCurrency(amount) {
            return 'DA ' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        // Function to update date and time display
        function updateDateTime() {
            const now = new Date();

            // Format day of week
            const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            document.getElementById('current-day').textContent = days[now.getDay()];

            // Format date (e.g., September 20, 2021)
            const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            const dateStr = `${months[now.getMonth()]} ${now.getDate()}, ${now.getFullYear()}`;
            document.getElementById('current-date').textContent = dateStr;

            // Format time (12-hour format with AM/PM)
            let hours = now.getHours();
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // Convert 0 to 12
            const minutes = now.getMinutes().toString().padStart(2, '0');
            document.getElementById('current-time').textContent = `${hours}:${minutes} ${ampm}`;
        }
    </script>
</body>

</html>