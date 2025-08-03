<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit;
}

include 'connect.php';


// Fetch bookings

$sql = "
    SELECT 
        g.*, 
        r.room_id, 
        rt.type AS room_type
    FROM guests g
    LEFT JOIN rooms r ON g.room_id = r.room_id
    LEFT JOIN room_types rt ON r.room_type_id = rt.room_type_id
    WHERE g.status = 'checked_out'
";

$result = $conn->query($sql);


?>
<!DOCTYPE html>
<html lang="en">


<?php include 'head.php'; ?>


<body>
    <!-- Toggle Button -->
    <button class="toggle-btn" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Overlay for mobile sidebar -->
    <div class="overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>


    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Topbar -->
        <div class="topbar">
            <div class="topbar-left">
                <div class="d-flex align-items-center">
                    <h4>History</h4>

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
                        <?php echo $_SESSION['admin_name']; ?>
                    </div>
                    <div class="text-muted small">Administrator</div>
                </div>

            </div>
        </div>

        <!-- Messages -->


        <!-- Card Content -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-list me-2"></i> List
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="bookingsTable" class="table table-hover">
                        <thead>
                            <tr>
                                <!-- <th>Booking ID</th> -->
                                <th>Guest Name</th>
                                <th>Check-in Date</th>
                                <th>Check-out Date</th>
                                <th>Room Type</th>
                                <th>Assigned Room</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result && $result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <!-- <td>SHIOJI-<?php echo str_pad($row['guest_id'], 5, '0', STR_PAD_LEFT); ?></td> -->
                                        <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($row['checkin_date'])); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($row['checkout_date'])); ?></td>

                                        <td><?php echo htmlspecialchars($row['room_type'] ?? 'Standard'); ?></td>
                                        <td><?php echo htmlspecialchars($row['room_id'] ?? '—'); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <!-- <tr>
                                    <td colspan="5" class="text-center py-4">No bookings found</td>
                                </tr> -->
                            <?php endif; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <!-- Assign Room Modal -->



    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../node_modules/datatables.net/js/dataTables.min.js"></script>
    <script src="../node_modules/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>



</body>

</html>