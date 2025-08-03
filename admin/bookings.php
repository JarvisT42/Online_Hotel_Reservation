<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit;
}

include 'connect.php';

// // Handle form submissions
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     // Handle room assignment
//     if (isset($_POST['assign_room'])) {
//         $booking_id = $_POST['booking_id'];
//         $room_id = $_POST['room_id'];




//         // Update booking with room number
//         $stmt = $conn->prepare("UPDATE guest SET room_id = ?, status = 'checked_in' WHERE guest_id = ?");
//         $stmt->bind_param("si", $room_id, $guest_id);

//         if ($stmt->execute()) {
//             // Update room status to occupied
//             $updateRoom = $conn->prepare("UPDATE rooms SET guest_id = ?, status = 'occupied'  WHERE room_id = ?");
//             $updateRoom->bind_param("is", $guest_id, $room_id);
//             $updateRoom->execute();

//             $_SESSION['success_message'] = "Room $room_id assigned successfully!";
//             header("Location: bookings.php");
//             exit;
//         } else {
//             $_SESSION['error_message'] = "Error assigning room: " . $conn->error;
//         }
//     }
// }


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['assign_room'])) {
        $booking_id = $_POST['booking_id'];
        $room_id = $_POST['room_id'];
        $checkin_date = date('Y-m-d');
        $status = "checked_in";


        // Step 1: Transfer data from bookings to guests
        $transferQuery = "
            INSERT INTO guests (
                first_name,
                last_name,
                email,
                phone,
                checkin_date,
            
                no_of_guest,
              
                status,
                room_id
            )
            SELECT
                first_name,
                last_name,
                email,
                phone,
                '$checkin_date',
            
                no_of_guest,
              
                    '$status',
                '$room_id'
            FROM bookings
            WHERE booking_id = '$booking_id'
        ";

        if ($conn->query($transferQuery) === TRUE) {
            // Get new guest ID
            $guest_id = $conn->insert_id;

            // Step 2: Mark room as occupied
            $updateRoomQuery = "UPDATE rooms SET guest_id = $guest_id, status = 'occupied' WHERE room_id = '$room_id'";
            $conn->query($updateRoomQuery);

            // ✅ Step 3: Mark booking as completed
            $updateBookingQuery = "UPDATE bookings SET status = 'completed' WHERE booking_id = '$booking_id'";
            $conn->query($updateBookingQuery);

            header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
        } else {

            $_SESSION['error_message'] = "Error transferring guest data: " . $conn->error;
            header("Location: " . $_SERVER['PHP_SELF'] . "?error=1");
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cancel'])) {
        include 'connect.php'; // include your DB connection

        $booking_id = $_POST['booking_id'];

        // Update the booking status to 'cancelled'
        $stmt = $conn->prepare("UPDATE bookings SET status = 'cancelled' WHERE booking_id = ?");
        $stmt->bind_param("i", $booking_id);

        if ($stmt->execute()) {
            header("Location: " . $_SERVER['PHP_SELF'] . "?cancelled=1");
        } else {
            $_SESSION['error_message'] = "Error cancelling booking: " . $conn->error;
        }

        $stmt->close();

        exit;
    }
}


// Fetch bookings
$sql = "SELECT * FROM bookings WHERE status = 'pending'";
$result = $conn->query($sql);

// Fetch available rooms

$roomQuery = "
    SELECT rooms.room_id, rooms.status, room_types.type
    FROM rooms
    LEFT JOIN room_types ON rooms.room_type_id = room_types.room_type_id
";
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
                    <h4>Bookings</h4>
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
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Guest transferred, room assigned, and booking marked as completed.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>


        <?php if (isset($_GET['cancelled']) && $_GET['cancelled'] == 1): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Booking cancelled successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>


        <?php if (isset($_GET['error']) && isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_SESSION['error_message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>


        <!-- Card Content -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-list me-2"></i> Booking List
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="bookingsTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Guest Name</th>
                                <th>Book Date</th>
                                <th>No. of Guest</th>
                                <th>Status</th>
                                <th>Assigned Room</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result && $result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td>SHIOJI-<?php echo str_pad($row['booking_id'],  STR_PAD_LEFT); ?></td>
                                        <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($row['booking_date'])); ?></td>
                                        <td><?php echo htmlspecialchars($row['no_of_guest']); ?></td>
                                        <td>
                                            <span class="status-badge status-<?php echo $row['status']; ?>">
                                                <?php
                                                $status = $row['status'];
                                                if ($status == 'confirmed') echo 'Confirmed';
                                                elseif ($status == 'checked_in') echo 'Checked In';
                                                elseif ($status == 'checked_out') echo 'Checked Out';
                                                elseif ($status == 'cancelled') echo 'Cancelled';
                                                else echo ucfirst($status);
                                                ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (!empty($row['room_id'])): ?>
                                                <span class="badge bg-primary">Room <?php echo $row['room_id']; ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">Not assigned</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (empty($row['room_id'])): ?>
                                                <!-- Assign Room Button -->
                                                <button class="btn btn-primary btn-sm assign-btn"
                                                    data-book-id="<?php echo $row['booking_id']; ?>"
                                                    data-guest-name="<?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#assignModal">
                                                    Assign Room
                                                </button>

                                                <!-- Cancel Button (optional functionality) -->
                                                <form method="POST" action="" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                                    <input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">
                                                    <button type="submit" name="cancel" class="btn btn-danger btn-sm">
                                                        Cancel
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <!-- Already Assigned -->
                                                <button class="btn btn-success btn-sm" disabled>
                                                    <i class="fas fa-check"></i> Assigned
                                                </button>
                                            <?php endif; ?>
                                        </td>

                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <!-- <tr>
                                    <td colspan="1" class="text-center py-4">No bookings found</td>
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
                        <input type="hidden" name="booking_id" id="modalBookId">

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
                                            <option
                                                value="<?php echo $room['room_id']; ?>"
                                                <?php echo ($room['status'] === 'occupied') ? 'disabled' : ''; ?>>
                                                Room <?php echo $room['room_id']; ?> - <?php echo htmlspecialchars($room['type']); ?>
                                                <?php echo ($room['status'] === 'occupied') ? ' (Occupied)' : ''; ?>
                                            </option>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <option disabled>No rooms found</option>
                                    <?php endif; ?>
                                </select>

                            </div>
                            <div class="form-text">Occupied rooms are disabled and cannot be selected.</div>
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