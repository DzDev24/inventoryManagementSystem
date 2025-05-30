<?php

require_once "./login_register/auth_session.php";

if ($_SESSION['user_role'] != 2) {
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
    <meta name="description" content="Sales Manager Dashboard" />
    <meta name="author" content="" />
    <title>Sales Manager Dashboard - Inventory System</title>
    <link href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" rel="stylesheet" />
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
                                        <div class="page-header-icon"><i data-feather="dollar-sign"></i></div>
                                        Sales Manager Dashboard
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
                                    <h2 class="text-primary">Welcome back, Sales Manager!</h2>
                                    <p class="text-gray-700">Track sales performance, revenue trends, customer orders, and more in real-time.</p>
                                </div>
                                <div class="col d-none d-lg-block mt-xxl-n4">
                                    <img class="img-fluid px-xl-4 mt-xxl-n5" src="assets/img/illustrations/statistics.svg" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sales KPI Cards -->
                    <div class="row">
                        <div class="col-lg-6 col-xl-3 mb-4">
                            <div class="card bg-primary text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">Total Revenue (This Month)</div>
                                            <div class="text-lg fw-bold" id="monthlyRevenue">Loading...</div>
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
                        <div class="col-lg-6 col-xl-3 mb-4">
                            <div class="card bg-warning text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">Orders Count</div>
                                            <div class="text-lg fw-bold" id="ordersCount">Loading...</div>
                                        </div>
                                        <i class="feather-xl text-white-50" data-feather="shopping-cart"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between small">
                                    <a class="text-white stretched-link" href="sales_list.php">View Orders</a>
                                    <div class="text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xl-3 mb-4">
                            <div class="card bg-success text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">Average Order Value</div>
                                            <div class="text-lg fw-bold" id="avgOrderValue">Loading...</div>
                                        </div>
                                        <i class="feather-xl text-white-50" data-feather="bar-chart-2"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between small">
                                    <a class="text-white stretched-link" href="sales_list.php">View Reports</a>
                                    <div class="text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-xl-3 mb-4">
                            <div class="card bg-danger text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">Total Customers</div>
                                            <div class="text-lg fw-bold" id="totalCustomers">Loading...</div>
                                        </div>
                                        <i class="feather-xl text-white-50" data-feather="users"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between small">
                                    <a class="text-white stretched-link" href="customers_list.php">View All</a>
                                    <div class="text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Charts Row -->
                    <div class="row">
                        <!-- Monthly Sales/Revenue Chart -->
                        <div class="col-xl-6 mb-4">
                            <div class="card card-header-actions h-100">
                                <div class="card-header">
                                    Monthly Sales Revenue
                                    <div class="dropdown no-caret">
                                        <button class="btn btn-transparent-dark btn-icon dropdown-toggle" id="salesTrendsDropdown" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="text-gray-500" data-feather="more-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end animated--fade-in-up" aria-labelledby="salesTrendsDropdown">
                                            <a class="dropdown-item" href="#" onclick="updateSalesTrendsChart('3')">Last 3 Months</a>
                                            <a class="dropdown-item" href="#" onclick="updateSalesTrendsChart('6')">Last 6 Months</a>
                                            <a class="dropdown-item" href="#" onclick="updateSalesTrendsChart('12')">Last 12 Months</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="salesTrendsChart" width="100%" height="30"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sales by Product Chart -->
                        <div class="col-xl-6 mb-4">
                            <div class="card card-header-actions h-100">
                                <div class="card-header">
                                    Sales by Product
                                    <div class="dropdown no-caret">
                                        <button class="btn btn-transparent-dark btn-icon dropdown-toggle" id="productsSalesDropdown" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="text-gray-500" data-feather="more-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end animated--fade-in-up" aria-labelledby="productsSalesDropdown">
                                            <a class="dropdown-item" href="#" onclick="updateProductsSalesChart('30')">Last 30 Days</a>
                                            <a class="dropdown-item" href="#" onclick="updateProductsSalesChart('90')">Last 90 Days</a>
                                            <a class="dropdown-item" href="#" onclick="updateProductsSalesChart('365')">Last Year</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="chart-bar">
                                        <canvas id="productsSalesChart" width="100%" height="30"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Orders and Customer Stats -->
                    <div class="row">
                        <!-- Recent Orders Table -->
                        <div class="col-lg-8 mb-4">
                            <div class="card card-header-actions h-100">
                                <div class="card-header">
                                    Recent Orders (This Month)
                                    <div class="dropdown no-caret">
                                        <button class="btn btn-transparent-dark btn-icon dropdown-toggle" id="recentOrdersDropdown" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="text-gray-500" data-feather="more-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end animated--fade-in-up" aria-labelledby="recentOrdersDropdown">
                                            <a class="dropdown-item" href="#" onclick="loadRecentOrders('7')">Last 7 Days</a>
                                            <a class="dropdown-item" href="#" onclick="loadRecentOrders('30')">Last 30 Days</a>
                                            <a class="dropdown-item" href="#" onclick="loadRecentOrders('90')">Last 90 Days</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover" id="recentOrdersTable">
                                            <thead>
                                                <tr>
                                                    <th>Order ID</th>
                                                    <th>Customer</th>
                                                    <th>Date</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Orders will be populated by JavaScript -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Top Customers -->
                        <div class="col-lg-4 mb-4">
                            <div class="card h-100">
                                <div class="card-header">
                                    Top Customers

                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-borderless">
                                            <thead>
                                                <tr>
                                                    <th>Customer</th>
                                                    <th>Orders</th>
                                                    <th>Spent</th>
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
                    </div>

                    <!-- Reports Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <i class="fas fa-file-pdf me-2"></i> Generate Reports
                        </div>
                        <div class="list-group list-group-flush small">
                            <a class="list-group-item list-group-item-action" href="reports/sales_performance_report.php" target="_blank">
                                <i class="fas fa-chart-line fa-fw text-primary me-2"></i>
                                Sales Performance Report
                            </a>
                            <a class="list-group-item list-group-item-action" href="reports/revenue_report.php" target="_blank">
                                <i class="fas fa-dollar-sign fa-fw text-success me-2"></i>
                                Revenue Analysis Report
                            </a>
                            <a class="list-group-item list-group-item-action" href="reports/customer_report.php" target="_blank">
                                <i class="fas fa-users fa-fw text-warning me-2"></i>
                                Customer Purchase Report
                            </a>
                            <a class="list-group-item list-group-item-action" href="reports/product_sales_report.php" target="_blank">
                                <i class="fas fa-boxes fa-fw text-info me-2"></i>
                                Product Sales Report
                            </a>
                        </div>
                    </div>
                </div>
            </main>
            <?php include 'includes/footer.php'; ?>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/bundle.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <!-- Custom JavaScript for Sales Manager Dashboard -->
    <script>
        // Global chart variables
        let salesTrendsChart;
        let productsSalesChart;

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

            // Initialize date/time display
            updateDateTime();
            setInterval(updateDateTime, 60000);
        });

        function initializeCharts() {
            // Sales Trends Chart (Area Chart)
            var salesTrendsCtx = document.getElementById('salesTrendsChart').getContext('2d');
            salesTrendsChart = new Chart(salesTrendsCtx, {
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
                        fill: 'origin'
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                return 'Revenue: $' + tooltipItem.yLabel.toLocaleString();
                            }
                        }
                    }
                }
            });

            // Products Sales Chart (Bar Chart)
            var productsSalesCtx = document.getElementById('productsSalesChart').getContext('2d');
            productsSalesChart = new Chart(productsSalesCtx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Revenue',
                        data: [],
                        backgroundColor: 'rgba(54, 185, 204, 0.7)',
                        borderColor: 'rgba(54, 185, 204, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y', // Horizontal bar chart
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                return 'Revenue: $' + tooltipItem.xLabel.toLocaleString();
                            }
                        }
                    }
                }
            });
        }

        // Load all dashboard data
        function loadDashboardData() {
            // Load KPI cards
            fetch('api/sales-kpis.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('monthlyRevenue').textContent = data.monthlyRevenue.toLocaleString() + 'DA';
                    document.getElementById('ordersCount').textContent = data.ordersCount.toLocaleString();
                    document.getElementById('avgOrderValue').textContent = data.avgOrderValue.toLocaleString() + 'DA';
                    document.getElementById('totalCustomers').textContent = data.totalCustomers.toLocaleString();

                });

            // Load sales trends chart data
            updateSalesTrendsChart('6');

            // Load products sales chart data
            updateProductsSalesChart('30');

            // Load recent orders
            loadRecentOrders('30');

            // Load top customers
            fetch('api/top-customers.php')
                .then(response => response.json())
                .then(data => {
                    let customersHtml = '';
                    data.forEach(customer => {
                        customersHtml += `
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <img class="avatar-img rounded-circle" src="${customer.avatar || 'assets/img/default-user.png'}" alt="${customer.name}">
                                        </div>
                                        <div>
                                            <div class="fw-500">${customer.name}</div>
                                            <div class="small text-gray-500">${customer.email}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>${customer.orders}</td>
                                <td>$${customer.total_spend.toLocaleString()}</td>
                            </tr>
                        `;
                    });
                    document.getElementById('topCustomersTable').innerHTML = customersHtml;
                });
        }

        // Update sales trends chart based on months
        function updateSalesTrendsChart(months) {
            fetch(`api/sales-trends.php?months=${months}`)
                .then(response => response.json())
                .then(data => {
                    salesTrendsChart.data.labels = data.labels;
                    salesTrendsChart.data.datasets[0].data = data.data;
                    salesTrendsChart.update();
                });
        }

        // Update products sales chart based on days
        function updateProductsSalesChart(days) {
            fetch(`api/products-sales.php?days=${days}`)
                .then(response => response.json())
                .then(data => {
                    productsSalesChart.data.labels = data.labels;
                    productsSalesChart.data.datasets[0].data = data.data;
                    productsSalesChart.update();
                });
        }

        // Load recent orders based on days
        function loadRecentOrders(days) {
            fetch(`api/recent-orders.php?days=${days}`)
                .then(response => response.json())
                .then(data => {
                    let ordersHtml = '';
                    data.forEach(order => {
                        let statusBadge = '';
                        if (order.status === 'Sold') {
                            statusBadge = '<span class="badge bg-green-soft text-green">Completed</span>';
                        } else if (order.status === 'Pending') {
                            statusBadge = '<span class="badge bg-yellow-soft text-yellow">Pending</span>';
                        } else {
                            statusBadge = '<span class="badge bg-red-soft text-red">Canceled</span>';
                        }

                        let paymentStatus = '';
                        if (order.payment_status === 'Paid') {
                            paymentStatus = '<span class="badge bg-green-soft text-green">Paid</span>';
                        } else if (order.payment_status === 'Partial') {
                            paymentStatus = '<span class="badge bg-yellow-soft text-yellow">Partial</span>';
                        } else {
                            paymentStatus = '<span class="badge bg-red-soft text-red">Unpaid</span>';
                        }

                        ordersHtml += `
                            <tr>
                                <td>#${order.Sale_ID}</td>
                                <td>${order.customer_name || 'Guest'}</td>
                                <td>${new Date(order.Sale_Date).toLocaleDateString()}</td>
                                <td>$${order.Total_Amount.toLocaleString()}</td>
                                <td>
                                    ${statusBadge}
                                    ${paymentStatus}
                                </td>
                               
                            </tr>
                        `;
                    });
                    document.querySelector('#recentOrdersTable tbody').innerHTML = ordersHtml;
                });
        }

        // View order details
        function viewOrderDetails(orderId) {
            // Implement order details modal or page navigation
            window.location.href = `order_details.php?id=${orderId}`;
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