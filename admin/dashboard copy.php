<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    // Not logged in, redirect to login page
    header("Location: ../login.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<?php include 'head.php'; ?>


<body>
    <!-- Toggle Button for Mobile -->
    <button class="toggle-btn" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Overlay for mobile sidebar -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>


    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <div class="topbar-left">
                <div class="d-flex align-items-center">
                    <h4 class="mb-0">Dashboard</h4>
                    <div class="ms-3 text-muted d-none d-md-block">
                        <i class="fas fa-calendar me-1"></i>
                        <span id="currentDate">June 6, 2023</span>
                    </div>
                </div>
            </div>
            <div class="user-info">
                <div class="notification">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </div>
                <img src="https://randomuser.me/api/portraits/men/41.jpg" alt="Admin">
                <div>
                    <div class="fw-bold">John Doe</div>
                    <div class="text-muted small">Administrator</div>
                </div>
            </div>
        </div>

        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <div class="dashboard-title">
                <h2>Dashboard Overview</h2>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-download me-1"></i> Export Report
                    </button>
                    <button class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i> New Booking
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <i class="fas fa-calendar-check"></i>
                    <div class="card-title">Total Bookings</div>
                    <div class="card-value">142</div>
                    <div class="card-change">+12% from last month</div>
                    <div class="position-absolute top-0 end-0 p-3 opacity-10">
                        <i class="fas fa-calendar-check fa-2x"></i>
                    </div>
                </div>

                <div class="stat-card">
                    <i class="fas fa-bed"></i>
                    <div class="card-title">Rooms Occupied</div>
                    <div class="card-value">24/36</div>
                    <div class="card-change">66% occupancy rate</div>
                    <div class="position-absolute top-0 end-0 p-3 opacity-10">
                        <i class="fas fa-bed fa-2x"></i>
                    </div>
                </div>

                <div class="stat-card">
                    <i class="fas fa-dollar-sign"></i>
                    <div class="card-title">Monthly Revenue</div>
                    <div class="card-value">$28,450</div>
                    <div class="card-change">+8% from last month</div>
                    <div class="position-absolute top-0 end-0 p-3 opacity-10">
                        <i class="fas fa-dollar-sign fa-2x"></i>
                    </div>
                </div>

                <div class="stat-card">
                    <i class="fas fa-users"></i>
                    <div class="card-title">New Customers</div>
                    <div class="card-value">42</div>
                    <div class="card-change negative">-3% from last month</div>
                    <div class="position-absolute top-0 end-0 p-3 opacity-10">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="chart-grid">
                <div class="chart-card">
                    <div class="chart-header">
                        <h5>Revenue Overview</h5>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-secondary time-btn" data-period="week">Week</button>
                            <button class="btn btn-sm btn-outline-secondary active time-btn" data-period="month">Month</button>
                            <button class="btn btn-sm btn-outline-secondary time-btn" data-period="year">Year</button>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="revenueChart"></canvas>
                    </div>
                    <div class="chart-legend">
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #2a5d8a;"></div>
                            <span>Room Revenue</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #e9b44c;"></div>
                            <span>Services</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #d74e09;"></div>
                            <span>Other</span>
                        </div>
                    </div>
                </div>

                <div class="chart-card">
                    <div class="chart-header">
                        <h5>Occupancy Rate</h5>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-secondary">Daily</button>
                            <button class="btn btn-sm btn-outline-secondary active">Monthly</button>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="occupancyChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Bookings -->
            <div class="table-card">
                <div class="table-header">
                    <h5>Recent Bookings</h5>
                    <a href="#" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Customer</th>
                                <th>Room Type</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#SH-1254</td>
                                <td>John Smith</td>
                                <td>Deluxe Room</td>
                                <td>15 Jun 2023</td>
                                <td>18 Jun 2023</td>
                                <td>$387</td>
                                <td><span class="status-badge status-confirmed">Confirmed</span></td>
                                <td><button class="btn btn-sm btn-outline-primary btn-action">View</button></td>
                            </tr>
                            <tr>
                                <td>#SH-1253</td>
                                <td>Emma Johnson</td>
                                <td>Family Suite</td>
                                <td>14 Jun 2023</td>
                                <td>17 Jun 2023</td>
                                <td>$477</td>
                                <td><span class="status-badge status-confirmed">Confirmed</span></td>
                                <td><button class="btn btn-sm btn-outline-primary btn-action">View</button></td>
                            </tr>
                            <tr>
                                <td>#SH-1252</td>
                                <td>Michael Brown</td>
                                <td>Standard Room</td>
                                <td>13 Jun 2023</td>
                                <td>16 Jun 2023</td>
                                <td>$267</td>
                                <td><span class="status-badge status-pending">Pending</span></td>
                                <td><button class="btn btn-sm btn-outline-primary btn-action">View</button></td>
                            </tr>
                            <tr>
                                <td>#SH-1251</td>
                                <td>Sarah Davis</td>
                                <td>Deluxe Room</td>
                                <td>12 Jun 2023</td>
                                <td>15 Jun 2023</td>
                                <td>$387</td>
                                <td><span class="status-badge status-confirmed">Confirmed</span></td>
                                <td><button class="btn btn-sm btn-outline-primary btn-action">View</button></td>
                            </tr>
                            <tr>
                                <td>#SH-1250</td>
                                <td>Robert Wilson</td>
                                <td>Standard Room</td>
                                <td>11 Jun 2023</td>
                                <td>14 Jun 2023</td>
                                <td>$267</td>
                                <td><span class="status-badge status-canceled">Canceled</span></td>
                                <td><button class="btn btn-sm btn-outline-primary btn-action">View</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Customers -->
            <div class="table-card">
                <div class="table-header">
                    <h5>Recent Customers</h5>
                    <a href="#" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="table-responsive">

                    <table id="myTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Email</th>
                                <th>Phone</th>

                                <th>Last Booking</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>John Smith</td>
                                <td>john.smith@example.com</td>
                                <td>+1 (555) 123-4567</td>

                                <td>15 Jun 2023</td>
                                <td><span class="status-badge status-confirmed">Active</span></td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary btn-action">View</a></td>
                            </tr>
                            <tr>
                                <td>Emma Johnson</td>
                                <td>emma.j@example.com</td>
                                <td>+1 (555) 987-6543</td>

                                <td>14 Jun 2023</td>
                                <td><span class="status-badge status-confirmed">Active</span></td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary btn-action">View</a></td>
                            </tr>
                            <tr>
                                <td>Michael Brown</td>
                                <td>m.brown@example.com</td>
                                <td>+1 (555) 456-7890</td>

                                <td>13 Jun 2023</td>
                                <td><span class="status-badge status-confirmed">Active</span></td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary btn-action">View</a></td>
                            </tr>
                            <tr>
                                <td>Sarah Davis</td>
                                <td>sarahd@example.com</td>
                                <td>+1 (555) 321-6547</td>

                                <td>12 Jun 2023</td>
                                <td><span class="status-badge status-pending">Pending</span></td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary btn-action">View</a></td>
                            </tr>
                            <tr>
                                <td>Robert Wilson</td>
                                <td>rob.wilson@example.com</td>
                                <td>+1 (555) 789-0123</td>

                                <td>11 Jun 2023</td>
                                <td><span class="status-badge status-confirmed">Active</span></td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary btn-action">View</a></td>
                            </tr>
                        </tbody>
                    </table>



                </div>
            </div>
        </div>
    </div>

    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../node_modules/datatables.net/js/dataTables.min.js"></script>
    <script src="../node_modules/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>



    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Set current date
        const today = new Date();
        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        document.getElementById('currentDate').textContent = today.toLocaleDateString('en-US', options);

        // Sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const closeSidebar = document.getElementById('closeSidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const collapseBtn = document.getElementById('collapseSidebar');

            // Toggle sidebar on button click
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('expanded');
                sidebarOverlay.classList.toggle('active');
            });

            // Collapse sidebar
            collapseBtn.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
            });

            // Close sidebar when clicking the close button
            closeSidebar.addEventListener('click', function() {
                sidebar.classList.remove('expanded');
                sidebarOverlay.classList.remove('active');
            });

            // Close sidebar when clicking outside
            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.remove('expanded');
                sidebarOverlay.classList.remove('active');
            });

            // Handle window resize
            function handleResize() {
                if (window.innerWidth > 992) {
                    sidebar.classList.remove('expanded');
                    sidebarOverlay.classList.remove('active');
                }
            }

            // Add resize event listener
            window.addEventListener('resize', handleResize);

            // Initialize with correct state
            handleResize();

            // Time period buttons
            const timeButtons = document.querySelectorAll('.time-btn');
            timeButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    timeButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // Initialize charts
            initCharts();
        });

        // Initialize charts
        function initCharts() {
            // Revenue Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            const revenueChart = new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: ['1 Jun', '5 Jun', '10 Jun', '15 Jun', '20 Jun', '25 Jun', '30 Jun'],
                    datasets: [{
                            label: 'Room Revenue',
                            data: [4200, 5800, 5200, 6300, 7100, 6900, 7850],
                            borderColor: '#2a5d8a',
                            backgroundColor: 'rgba(42, 93, 138, 0.1)',
                            tension: 0.3,
                            fill: true
                        },
                        {
                            label: 'Services',
                            data: [1200, 1500, 1800, 1400, 1650, 1900, 2100],
                            borderColor: '#e9b44c',
                            backgroundColor: 'rgba(233, 180, 76, 0.1)',
                            tension: 0.3,
                            fill: true
                        },
                        {
                            label: 'Other',
                            data: [800, 900, 750, 1000, 950, 1100, 1250],
                            borderColor: '#d74e09',
                            backgroundColor: 'rgba(215, 78, 9, 0.1)',
                            tension: 0.3,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            },
                            ticks: {
                                callback: function(value) {
                                    return '$' + value;
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Occupancy Chart
            const occupancyCtx = document.getElementById('occupancyChart').getContext('2d');
            const occupancyChart = new Chart(occupancyCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Occupied', 'Available'],
                    datasets: [{
                        data: [66, 34],
                        backgroundColor: [
                            '#2a5d8a',
                            '#e0e0e0'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.parsed + '%';
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
</body>

</html>