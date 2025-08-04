<?php

require_once "./login_register/auth_session.php";

// Redirect if not admin or product manager
if ($_SESSION['user_role'] != 3) {
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
    <meta name="description" content="Product Manager Dashboard" />
    <meta name="author" content="" />
    <title>Product Manager Dashboard - Inventory System</title>
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
                                        <div class="page-header-icon"><i data-feather="users"></i></div>
                                        Product Manager Dashboard
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


                   
                    <div class="card card-waves mb-4 mt-5">
                        <div class="card-body p-5">
                            <div class="row align-items-center justify-content-between">
                                <div class="col">
                                    <h2 class="text-primary">Welcome back, Product Manager!</h2>
                                    <p class="text-gray-700">in this Dashboard, you can monitor product sales, added products, latest activity, suppliers, and more!</p>

                                </div>
                                <div class="col d-none d-lg-block mt-xxl-n4">
                                    <img class="img-fluid px-xl-4 mt-xxl-n5" src="assets/img/illustrations/statistics.svg" />
                                </div>
                            </div>
                        </div>
                    </div>


                
                    <div class="row">
                        <div class="col-lg-6 col-xl-3 mb-4">
                            <div class="card bg-primary text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">Total Products</div>
                                            <div class="text-lg fw-bold" id="totalProducts">Loading...</div>
                                        </div>
                                        <i class="feather-xl text-white-50" data-feather="package"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between small">
                                    <a class="text-white stretched-link" href="products.php">View Products</a>
                                    <div class="text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xl-3 mb-4">
                            <div class="card bg-warning text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">Low Stock Alerts</div>
                                            <div class="text-lg fw-bold" id="lowStockCount">Loading...</div>
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
                        <div class="col-lg-6 col-xl-3 mb-4">
                            <div class="card bg-success text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">Categories</div>
                                            <div class="text-lg fw-bold" id="categoryCount">Loading...</div>
                                        </div>
                                        <i class="feather-xl text-white-50" data-feather="list"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between small">
                                    <a class="text-white stretched-link" href="categories_list.php">Manage Categories</a>
                                    <div class="text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xl-3 mb-4">
                            <div class="card bg-danger text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-white-75 small">Active Suppliers</div>
                                            <div class="text-lg fw-bold" id="supplierCount">Loading...</div>
                                        </div>
                                        <i class="feather-xl text-white-50" data-feather="truck"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between small">
                                    <a class="text-white stretched-link" href="suppliers_list.php">View Suppliers</a>
                                    <div class="text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                
                    <div class="row">

                        <!-- Top Selling Products -->
                        <div class="col-xl-6 mb-4">
                            <div class="card card-header-actions h-100">
                                <div class="card-header">
                                    Top Selling Products
                                    <div class="dropdown no-caret">
                                        <button class="btn btn-transparent-dark btn-icon dropdown-toggle" id="topProductsDropdown" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="text-gray-500" data-feather="more-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end animated--fade-in-up" aria-labelledby="topProductsDropdown">
                                            <a class="dropdown-item" href="#" onclick="updateTopProductsChart('30')">Last 30 Days</a>
                                            <a class="dropdown-item" href="#" onclick="updateTopProductsChart('90')">Last 90 Days</a>
                                            <a class="dropdown-item" href="#" onclick="updateTopProductsChart('365')">Last Year</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="chart-bar">
                                        <canvas id="topProductsChart" width="100%" height="30"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Products Added Per Month -->
                        <div class="col-xl-6 mb-4">
                            <div class="card card-header-actions h-100">
                                <div class="card-header">
                                    Products Added Per Month
                                    <div class="dropdown no-caret">
                                        <button class="btn btn-transparent-dark btn-icon dropdown-toggle" id="barChartDropdown" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="text-gray-500" data-feather="more-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end animated--fade-in-up" aria-labelledby="barChartDropdown">
                                            <a class="dropdown-item" href="#" onclick="updateProductsAddedChart('6')">Last 6 Months</a>
                                            <a class="dropdown-item" href="#" onclick="updateProductsAddedChart('12')">Last 12 Months</a>
                                            <a class="dropdown-item" href="#" onclick="updateProductsAddedChart('24')">Last 24 Months</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="chart-bar">
                                        <canvas id="productsAddedChart" width="100%" height="30"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                   
                    <div class="row">
                        <!-- Category Distribution -->
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header">Category Distribution</div>
                                <div class="card-body">
                                    <div class="chart-pie mb-4">
                                        <canvas id="categoryDistributionChart" width="100%" height="50"></canvas>
                                    </div>
                                    <div class="list-group list-group-flush" id="categoryLegend">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Product Activity -->
                        <div class="col-lg-6 mb-4">
                            <div class="card card-header-actions h-100">
                                <div class="card-header">
                                    Recent Product Activity
                                    <div class="dropdown no-caret">
                                        <button class="btn btn-transparent-dark btn-icon dropdown-toggle" id="activityDropdown" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="text-gray-500" data-feather="more-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end animated--fade-in-up" aria-labelledby="activityDropdown">
                                            <h6 class="dropdown-header">Filter Activity:</h6>
                                            <a class="dropdown-item" href="#" onclick="filterActivity('all')">All Activities</a>
                                            <a class="dropdown-item" href="#" onclick="filterActivity('added')">Products Added</a>
                                            <a class="dropdown-item" href="#" onclick="filterActivity('updated')">Products Updated</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="timeline timeline-xs" id="productActivityTimeline">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Suppliers and Low Stock Products -->
                    <div class="row">
                        <!-- Top Suppliers -->
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header">
                                    Top Suppliers by Products Supplied
                                    <a class="btn btn-sm btn-primary-soft text-primary" href="suppliers_list.php">View All</a>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Supplier</th>
                                                    <th>Company</th>
                                                    <th>Products</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody id="topSuppliersTable">
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Low Stock Products -->
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header">
                                    Low Stock Products
                                    <a class="btn btn-sm btn-warning-soft text-warning" href="products_list.php">View All</a>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Category</th>
                                                    <th>Current Stock</th>
                                                    <th>Minimum</th>
                                                </tr>
                                            </thead>
                                            <tbody id="lowStockProductsTable">
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white"> <i class="fas fa-file-pdf me-2"></i> Generate Reports</div>
                        <div class="list-group list-group-flush small">
                            <a class="list-group-item list-group-item-action" href="reports/inventory_report.php" target="_blank">
                                <i class="fas fa-warehouse fa-fw text-danger me-2"></i>
                                Inventory Status Report
                            </a>
                            <a class="list-group-item list-group-item-action" href="reports/product_performance_report.php" target="_blank">
                                <i class="fas fa-chart-line fa-fw text-primary me-2"></i>
                                Product Performance Report
                            </a>
                            <a class="list-group-item list-group-item-action" href="reports/category_performance_report.php" target="_blank">
                                <i class="fas fa-sitemap fa-fw text-success me-2"></i>
                                Category Analysis Report
                            </a>
                            <a class="list-group-item list-group-item-action" href="reports/supplier_performance_report.php" target="_blank">
                                <i class="fas fa-truck fa-fw text-warning me-2"></i>
                                Supplier Performance Report
                            </a>
                        </div>
                    </div>



                    
                    <script src="./js/vendor/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
                    <script src="js/scripts.js"></script>
                    <script src="./js/vendor/Chart.min.js" crossorigin="anonymous"></script>
                    <script src="./js/vendor/simple-datatables.min.js" crossorigin="anonymous"></script>
                    <script src="./js/vendor/bundle.js" crossorigin="anonymous"></script>
                    <script src="./js/vendor/html2pdf.bundle.min.js"></script>

                    
                    <script>
                        
                        let topProductsChart;
                        let productsAddedChart;
                        let categoryDistributionChart;

                        
                        document.addEventListener('DOMContentLoaded', function() {
                            
                            initializeCharts();
                            loadDashboardData();

                            
                            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                                return new bootstrap.Tooltip(tooltipTriggerEl);
                            });

                            // Feather icons
                            feather.replace();
                        });

                        function initializeCharts() {
                            // Top Products Chart
                            var topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
                            topProductsChart = new Chart(topProductsCtx, {
                                type: 'bar',
                                data: {
                                    labels: [],
                                    datasets: [{
                                        label: 'Units Sold',
                                        data: [],
                                        backgroundColor: 'rgba(54, 185, 204, 0.75)',
                                        borderColor: 'rgba(54, 185, 204, 1)',
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    indexAxis: 'y', // Horizontal bar chart
                                    maintainAspectRatio: false,
                                    scales: {
                                        x: {
                                            beginAtZero: true
                                        }
                                    }
                                }
                            });

                            // Products Added Chart
                            var productsCtx = document.getElementById('productsAddedChart').getContext('2d');
                            productsAddedChart = new Chart(productsCtx, {
                                type: 'bar',
                                data: {
                                    labels: [],
                                    datasets: [{
                                        label: 'Products Added',
                                        data: [],
                                        backgroundColor: 'rgba(124, 54, 204, 0.75)',
                                        borderColor: 'rgba(124, 54, 204, 1)',
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    maintainAspectRatio: false,
                                    scales: {
                                        yAxes: [{
                                            ticks: {
                                                beginAtZero: true
                                            }
                                        }]
                                    }
                                }
                            });

                            // Category Distribution Chart
                            var categoryCtx = document.getElementById('categoryDistributionChart').getContext('2d');
                            categoryDistributionChart = new Chart(categoryCtx, {
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
                            fetch('api/product-stats.php')
                                .then(response => response.json())
                                .then(data => {
                                    document.getElementById('totalProducts').textContent = data.totalProducts;
                                    document.getElementById('lowStockCount').textContent = data.lowStockCount;
                                    document.getElementById('categoryCount').textContent = data.categoryCount;
                                    document.getElementById('supplierCount').textContent = data.supplierCount;
                                });

                            updateTopProductsChart('30');

                          
                            updateProductsAddedChart('12');

                           
                            fetch('api/category-distribution.php')
                                .then(response => response.json())
                                .then(data => {
                                    categoryDistributionChart.data.labels = data.labels;
                                    categoryDistributionChart.data.datasets[0].data = data.data;
                                    categoryDistributionChart.update();

                                    
                                    let legendHtml = '';
                                    data.labels.forEach((label, index) => {
                                        const color = categoryDistributionChart.data.datasets[0].backgroundColor[index];
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
                                    document.getElementById('categoryLegend').innerHTML = legendHtml;
                                });

                        
                            fetch('api/recent-product-activity.php')
                                .then(response => response.json())
                                .then(data => {
                                    let timelineHtml = '';
                                    data.forEach(activity => {
                                        let iconColor = '';
                                        let icon = '';

                                        if (activity.type === 'added') {
                                            iconColor = 'bg-green';
                                            icon = 'plus';
                                        } else if (activity.type === 'updated') {
                                            iconColor = 'bg-blue';
                                            icon = 'edit';
                                        } else {
                                            iconColor = 'bg-purple';
                                            icon = 'trash-2';
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
                                    <span class="fw-bold text-dark">${activity.product_name}</span>
                                </div>
                            </div>
                        `;
                                    });
                                    document.getElementById('productActivityTimeline').innerHTML = timelineHtml;
                                    feather.replace();
                                });

                           
                            fetch('api/top-suppliers.php')
                                .then(response => response.json())
                                .then(data => {
                                    let suppliersHtml = '';
                                    data.forEach(supplier => {
                                        suppliersHtml += `
                            <tr>
                                <td>${supplier.name}</td>
                                <td>${supplier.company}</td>
                                <td>${supplier.product_count}</td>
                                <td><span class="badge ${supplier.status === 'Available' ? 'bg-green-soft text-green' : 'bg-red-soft text-red'}">${supplier.status}</span></td>
                            </tr>
                        `;
                                    });
                                    document.getElementById('topSuppliersTable').innerHTML = suppliersHtml;
                                });

                            fetch('api/low-stock-products.php')
                                .then(response => response.json())
                                .then(data => {
                                    let productsHtml = '';
                                    data.forEach(product => {
                                        productsHtml += `
                            <tr>
                                <td><a href="product_details_modal.php?id=${product.id}">${product.name}</a></td>
                                <td>${product.category}</td>
                                <td>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar ${product.quantity <= product.min_stock ? 'bg-danger' : 'bg-warning'}" 
                                             role="progressbar" 
                                             style="width: ${Math.min(100, (product.quantity / product.min_stock) * 100)}%" 
                                             aria-valuenow="${product.quantity}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="${product.min_stock * 2}">
                                        </div>
                                    </div>
                                    <small>${product.quantity} ${product.unit}</small>
                                </td>
                                <td>${product.min_stock} ${product.unit}</td>
                            </tr>
                        `;
                                    });
                                    document.getElementById('lowStockProductsTable').innerHTML = productsHtml;
                                });
                        }

                       

                        function initializeTopProductsChart() {
                            var ctx = document.getElementById('topProductsChart').getContext('2d');
                            topProductsChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: [],
                                    datasets: [{
                                        label: 'Units Sold',
                                        data: [],
                                        backgroundColor: 'rgba(54, 185, 204, 0.5)',
                                        borderColor: 'rgba(54, 185, 204, 1)',
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    indexAxis: 'y', 
                                    maintainAspectRatio: false,
                                    scales: {
                                        x: {
                                            beginAtZero: true
                                        }
                                    }
                                }
                            });
                        }

                        function updateTopProductsChart(days) {
                            fetch(`api/top-products.php?days=${days}`)
                                .then(response => response.json())
                                .then(data => {
                                    topProductsChart.data.labels = data.labels;
                                    topProductsChart.data.datasets[0].data = data.data;
                                    topProductsChart.update();
                                });
                        }

                        
                        function updateProductsAddedChart(months) {
                            fetch(`api/products-added.php?months=${months}`)
                                .then(response => response.json())
                                .then(data => {
                                    productsAddedChart.data.labels = data.labels;
                                    productsAddedChart.data.datasets[0].data = data.data;
                                    productsAddedChart.update();
                                });
                        }

                        // Filter activity timeline
                        function filterActivity(type) {
                            const items = document.querySelectorAll('#productActivityTimeline .timeline-item');
                            items.forEach(item => {
                                if (type === 'all' || item.getAttribute('data-type') === type) {
                                    item.style.display = 'flex';
                                } else {
                                    item.style.display = 'none';
                                }
                            });
                        }
                    </script>

                    <script>
                       
                        function updateDateTime() {
                            const now = new Date();

                            // Format day of week
                            const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                            document.getElementById('current-day').textContent = days[now.getDay()];

                            // Format date
                            const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                            const dateStr = `${months[now.getMonth()]} ${now.getDate()}, ${now.getFullYear()}`;
                            document.getElementById('current-date').textContent = dateStr;

                            // Format time
                            let hours = now.getHours();
                            const ampm = hours >= 12 ? 'PM' : 'AM';
                            hours = hours % 12;
                            hours = hours ? hours : 12; 
                            const minutes = now.getMinutes().toString().padStart(2, '0');
                            document.getElementById('current-time').textContent = `${hours}:${minutes} ${ampm}`;
                        }

                        
                        updateDateTime();

                        
                        setInterval(updateDateTime, 60000);
                    </script>

            </main>
            <?php include 'includes/footer.php'; ?>
        </div> 
    </div> 
</body>

</html>