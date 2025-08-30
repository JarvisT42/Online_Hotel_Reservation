<?php
session_start();
include '../connect.php';
if (!isset($_SESSION['guest_logged_in'])) {
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
                <!-- <div class="notification">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </div> -->
                <img src="https://www.w3schools.com/howto/img_avatar.png" alt="Admin Avatar" class="rounded-circle" width="40" height="40">
                <div>
                    <div class="fw-bold">
                        <?php echo isset($_SESSION['guest_name']) && !empty($_SESSION['guest_name'])
                            ? htmlspecialchars($_SESSION['guest_name'])
                            : 'Guest'; ?>
                    </div>


                </div>

            </div>
        </div>

        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <div class="dashboard-title">
                <h2>Dashboard Overview</h2>

            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">

                <div class="stat-card">
                    <i class="fas fa-calendar-check"></i>
                    <div class="card-title">My Pending Bookings</div>
                    <div class="card-value">
                        <?php
                        if (isset($_SESSION['guest_email'])) {
                            $guestEmail = $_SESSION['guest_email'];

                            // Query to get pending booking dates
                            $sql = "SELECT booking_date 
                        FROM bookings 
                        WHERE email = ? AND status = 'pending'";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("s", $guestEmail);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    // Show each pending booking date
                                    echo "<div>" . htmlspecialchars($row['booking_date']) . "</div>";
                                }
                            } else {
                                echo "No pending bookings";
                            }
                        } else {
                            echo "Please login";
                        }
                        ?>
                    </div>
                    <div class="card-change">Booking Date</div>
                    <div class="position-absolute top-0 end-0 p-3 opacity-10">
                        <i class="fas fa-calendar-check fa-2x"></i>
                    </div>
                </div>

                <div class="stat-card">
                    <i class="fas fa-bed"></i>
                    <div class="card-title">My Rooms</div>
                    <div class="card-value">
                        <?php
                        if (isset($_SESSION['guest_id'])) {
                            $guestId = $_SESSION['guest_id'];

                            // Query to get rooms assigned to this guest
                            $sql = "SELECT r.room_id, rt.type 
                        FROM rooms r
                        JOIN room_types rt ON r.room_type_id = rt.room_type_id
                        WHERE r.guest_id = ? AND r.status = 'occupied'";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("i", $guestId);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<div>Room " . htmlspecialchars($row['room_id']) . " - " . htmlspecialchars($row['type']) . "</div>";
                                }
                            } else {
                                echo "No occupied rooms";
                            }
                        } else {
                            echo "Please login";
                        }
                        ?>
                    </div>
                    <div class="card-change">Assigned Rooms</div>
                    <div class="position-absolute top-0 end-0 p-3 opacity-10">
                        <i class="fas fa-bed fa-2x"></i>
                    </div>
                </div>

                <div class="stat-card">
                    <i class="fas fa-user-check"></i>
                    <div class="card-title">My Status</div>
                    <div class="card-value">
                        <?php
                        if (isset($_SESSION['guest_id'])) {
                            $guestId = $_SESSION['guest_id'];

                            // Fetch the guest status
                            $sql = "SELECT status FROM guests WHERE guest_id = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("i", $guestId);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($row = $result->fetch_assoc()) {
                                if (empty($row['status'])) {
                                    echo "Not Checked-in"; // ✅ if status is null or empty
                                } else {
                                    echo ucfirst($row['status']); // ✅ e.g. "Checked_in"
                                }
                            } else {
                                echo "No status found";
                            }
                        } else {
                            echo "Please login";
                        }
                        ?>
                    </div>
                    <div class="card-change">Current Status</div>
                    <div class="position-absolute top-0 end-0 p-3 opacity-10">
                        <i class="fas fa-user-check fa-2x"></i>
                    </div>
                </div>

            </div>


            <!-- Charts Section -->




            <!-- Recent Customers -->
            <!-- <div class="table-card">
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
            </div> -->
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