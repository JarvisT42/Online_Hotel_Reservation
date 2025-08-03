<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit;
}

include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['check_out'])) {

    $guest_id = trim($_POST['guest_id']);
    $checkout_date = date('Y-m-d');

    // Correct SQL with placeholders
    $stmt = $conn->prepare("UPDATE guests SET checkout_date = ?, status = 'checked_out' WHERE guest_id = ?");
    $stmt->bind_param("si", $checkout_date, $guest_id); // "s" for string (date), "i" for integer (guest_id)

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Guest successfully checked out.";
    } else {
        $_SESSION['error_message'] = "Failed to check out guest.";
    }

    $stmt->close();

    // Redirect to avoid form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}




// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle room assignment
    if (isset($_POST['assign_room'])) {
        $guest_id = $_POST['guest_id'];
        $room_id = $_POST['room_id'];

        // Update booking with room number
        $stmt = $conn->prepare("UPDATE guests SET room_id = ?, status = 'checked_in' WHERE guest_id = ?");
        $stmt->bind_param("si", $room_id, $guest_id);

        if ($stmt->execute()) {
            // Update room status to occupied
            $updateRoom = $conn->prepare("UPDATE rooms SET guest_id = ?, status = 'occupied'  WHERE room_id = ?");
            $updateRoom->bind_param("is", $guest_id, $room_id);
            $updateRoom->execute();

            $_SESSION['success_message'] = "Room $room_id assigned successfully!";
            header("Location: bookings.php");
            exit;
        } else {
            $_SESSION['error_message'] = "Error assigning room: " . $conn->error;
        }
    }
}

// Fetch bookings
$sql = "SELECT * FROM guests WHERE status = 'checked_in' ";
$result = $conn->query($sql);

// Fetch available rooms
$roomQuery = "SELECT room_id FROM rooms WHERE status = 'available'";
$roomResult = $conn->query($roomQuery);
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
                    <h4>Guest</h4>
                    <div class="ms-3 text-muted d-none d-md-block">
                        <i class="fas fa-bed me-1"></i>
                        <span>Total <?php echo $result->num_rows; ?> bookings</span>
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
                        <?php echo $_SESSION['admin_name']; ?>
                    </div>
                    <div class="text-muted small">Administrator</div>
                </div>

            </div>
        </div>

        <!-- Messages -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo $_SESSION['success_message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?php echo $_SESSION['error_message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <!-- Card Content -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-list me-2"></i> Guest List
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="bookingsTable" class="table table-hover">
                        <thead>
                            <tr>
                                <!-- <th>Booking ID</th> -->
                                <th>Guest Name</th>
                                <th>Check-in Date</th>
                                <th>Room Type</th>
                                <th>Status</th>
                                <th>Assigned Room</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result && $result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <!-- <td>SHIOJI-<?php echo str_pad($row['guest_id'], 5, '0', STR_PAD_LEFT); ?></td> -->
                                        <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($row['checkin_date'])); ?></td>
                                        <td><?php echo htmlspecialchars($row['room_type'] ?? 'Standard'); ?></td>
                                        <td>
                                            <span class="status-badge status-<?php echo $row['status']; ?>">
                                                <?php
                                                $status = $row['status'];
                                                echo match ($status) {
                                                    'confirmed'   => 'Confirmed',
                                                    'checked_in'  => 'Checked In',
                                                    'checked_out' => 'Checked Out',
                                                    'cancelled'   => 'Cancelled',
                                                    default       => ucfirst($status),
                                                };
                                                ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (!empty($row['room_id'])): ?>
                                                <span class="badge bg-primary">Room <?php echo htmlspecialchars($row['room_id']); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">Not assigned</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (empty($row['room_id'])): ?>
                                                <button
                                                    class="btn btn-primary btn-sm assign-btn"
                                                    data-book-id="<?php echo $row['guest_id']; ?>"
                                                    data-guest-name="<?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#assignModal">
                                                    Assign Room
                                                </button>
                                            <?php else: ?>
                                                <form action="" method="POST" onsubmit="return confirm('Are you sure you want to check out this guest?');">
                                                    <input type="hidden" name="guest_id" value="<?php echo htmlspecialchars($row['guest_id']); ?>">
                                                    <button type="submit" name="check_out" class="btn btn-secondary btn-sm">
                                                        <i class="fas fa-door-open"></i> Check Out
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <!-- <tr>
                                    <td colspan="7" class="text-center py-4">No bookings found</td>
                                </tr> -->
                            <?php endif; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <!-- Assign Room Modal -->
    <div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="assignModalLabel">Assign Room to Guest</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="guest_id" id="modalBookId">

                        <div class="mb-4">
                            <label class="form-label">Guest</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="guestName" readonly>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="roomNumber" class="form-label">Select Room</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-door-open"></i></span>
                                <select class="form-select" name="room_id" id="roomNumber" required>
                                    <option value="" disabled selected>Select a room</option>
                                    <?php if ($roomResult && $roomResult->num_rows > 0): ?>
                                        <?php while ($room = $roomResult->fetch_assoc()): ?>
                                            <option value="<?php echo $room['room_id']; ?>">
                                                Room <?php echo $room['room_id']; ?>
                                            </option>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <option disabled>No available rooms</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-text">Only available rooms are shown</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="assign_room" class="btn btn-primary">Assign Room</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../node_modules/datatables.net/js/dataTables.min.js"></script>
    <script src="../node_modules/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#bookingsTable').DataTable({
                responsive: true,
                order: [
                    [0, 'desc']
                ]
            });

            // Handle assign button click
            $('.assign-btn').on('click', function() {
                const bookId = $(this).data('book-id');
                const guestName = $(this).data('guest-name');

                $('#modalBookId').val(bookId);
                $('#guestName').val(guestName);
            });

            // Handle sidebar toggle
            $('#sidebarToggle').on('click', function() {
                $('#sidebar').toggleClass('active');
                $('#sidebarOverlay').toggleClass('active');
                $('#mainContent').toggleClass('sidebar-active');
            });

            // Close sidebar when clicking overlay
            $('#sidebarOverlay').on('click', function() {
                $('#sidebar').removeClass('active');
                $('#sidebarOverlay').removeClass('active');
                $('#mainContent').removeClass('sidebar-active');
            });

            // Auto-close alerts after 5 seconds
            $('.alert').delay(5000).fadeOut(400);
        });
    </script>
</body>

</html>