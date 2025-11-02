<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../assets/PHPMailer-master/src/Exception.php';
require '../assets/PHPMailer-master/src/PHPMailer.php';
require '../assets/PHPMailer-master/src/SMTP.php';

$mail = new PHPMailer(true);

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit;
}

include '../connect.php';

// Function to check if guest has unpaid bills for CURRENT stay only
function hasUnpaidBills($guest_id, $conn)
{
    // Get the current check-in date for this specific stay
    $checkin_sql = "SELECT checkin_date, checkout_date FROM guests WHERE guest_id = ? AND status = 'checked_in'";
    $stmt_check = $conn->prepare($checkin_sql);
    $stmt_check->bind_param("i", $guest_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $guest_data = $result_check->fetch_assoc();
    $stmt_check->close();

    if (!$guest_data) {
        return false; // Guest not found or not checked in
    }

    $current_checkin = $guest_data['checkin_date'];

    // If guest is checked out, use checkout_date as end date, otherwise use current date
    $end_date = $guest_data['checkout_date'] ? $guest_data['checkout_date'] : date('Y-m-d');

    // Calculate months from CURRENT check-in date to end date
    $checkin = new DateTime($current_checkin);
    $end = new DateTime($end_date);

    // Start from the check-in month
    $checkin->modify('first day of this month');
    $end->modify('first day of this month');

    $expected_months = [];
    while ($checkin <= $end) {
        $expected_months[] = $checkin->format('Y-m');
        $checkin->modify('+1 month');
    }

    if (empty($expected_months)) {
        return false; // No months expected
    }

    // Check if we have PAID transaction records for all expected months of CURRENT stay
    $placeholders = str_repeat('?,', count($expected_months) - 1) . '?';
    $sql = "
        SELECT COUNT(*) as transaction_count 
        FROM transactions 
        WHERE guest_id = ? 
        AND bill_month IN ($placeholders)
        AND transaction_date >= ?
        AND is_paid = '1'  -- Only count PAID transactions
    ";

    $stmt = $conn->prepare($sql);
    $types = 'i' . str_repeat('s', count($expected_months)) . 's';
    $params = array_merge([$guest_id], $expected_months, [$current_checkin]);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    $transaction_count = $row['transaction_count'];

    // If PAID transaction count matches expected months, guest is fully paid for CURRENT stay
    $has_all_transactions = $transaction_count == count($expected_months);

    // Check for unpaid additional charges from CURRENT stay only
    $sql_charges = "
        SELECT COUNT(*) as unpaid_count 
        FROM additional_charge 
        WHERE guest_id = ? 
        AND paid = 0
        AND date >= ?
    ";

    $stmt2 = $conn->prepare($sql_charges);
    $stmt2->bind_param("is", $guest_id, $current_checkin);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    $row2 = $result2->fetch_assoc();
    $stmt2->close();

    $unpaid_charges = $row2['unpaid_count'] > 0;

    // Guest has unpaid bills if:
    // 1. Missing PAID transaction records for some months of CURRENT stay, OR
    // 2. Has unpaid additional charges from CURRENT stay
    return !$has_all_transactions || $unpaid_charges;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['check_out'])) {
    $guest_id = trim($_POST['guest_id']);
    $email = $_POST['email'];

    // Check if guest has unpaid bills before allowing checkout
    if (hasUnpaidBills($guest_id, $conn)) {
        $_SESSION['error_message'] = "Cannot check out guest. There are unpaid bills or additional charges.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    $checkout_date = date('Y-m-d');

    // Correct SQL with placeholders
    $stmt = $conn->prepare("UPDATE guests SET checkout_date = ?, status = 'checked_out' WHERE guest_id = ?");
    $stmt->bind_param("si", $checkout_date, $guest_id);

    if ($stmt->execute()) {
        // Now, update rooms table
        $roomStmt = $conn->prepare("UPDATE rooms SET status = 'available', guest_id = null WHERE guest_id = ?");
        $roomStmt->bind_param("i", $guest_id);

        if ($roomStmt->execute()) {
            $_SESSION['success_message'] = "Guest successfully checked out and room marked as available.";

            // ✅ Send checkout email
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.hostinger.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'support@shibokbill.space';
                $mail->Password   = 'Shibokbill@302';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;

                // Recipients
                $mail->setFrom('support@shibokbill.space', 'Shioji Apartelle');
                $mail->addAddress($email);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Check-out Confirmation - Shioji Apartelle';
                $mail->Body    = "
                    <h2>Check-out Confirmation</h2>
                    <p>Dear Guest,</p>
                    <p>We hope you enjoyed your stay at Shioji Apartelle.</p>
                    <p><b>Check-out Date:</b> {$checkout_date}</p>
                    <p>Thank you for choosing us, and we look forward to welcoming you again.</p>
                    <br>
                    <p>Best regards,<br>Shioji Apartelle Team</p>
                ";

                $mail->send();
            } catch (Exception $e) {
                error_log("Mailer Error (Check-out): {$mail->ErrorInfo}");
            }
        } else {
            $_SESSION['error_message'] = "Guest checked out, but failed to update room status.";
        }

        $roomStmt->close();
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

    if (isset($_POST['archive'])) {
        $guest_id = $_POST['guest_id'];

        $stmt = $conn->prepare("UPDATE guests SET status = 'archive' WHERE guest_id = ?");
        $stmt->bind_param("i", $guest_id);
        $stmt->execute();
        $stmt->close();
    }

    if (isset($_POST['save_charge'])) {
        $guest_id = $_POST['guest_id'];
        $description = trim($_POST['charge_description']);
        $amount = floatval($_POST['charge_amount']);
        $date = $_POST['charge_date'];

        if (!empty($guest_id) && !empty($description) && $amount >= 0 && !empty($date)) {
            $stmt = $conn->prepare("INSERT INTO additional_charge (guest_id, description, amount, date) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isds", $guest_id, $description, $amount, $date);
            if ($stmt->execute()) {
                echo "<script>alert('Additional charge saved successfully.');</script>";
            } else {
                echo "<script>alert('Failed to save charge.');</script>";
            }
        } else {
            echo "<script>alert('Please fill in all fields correctly.');</script>";
        }
    }
    if (isset($_POST['additional_c_save_charge'])) {
        $guest_id = intval($_POST['guest_id']);
        $description = trim($_POST['charge_description']);
        $amount = floatval($_POST['charge_amount']);
        $date = $_POST['charge_date'];

        // Debug output
        echo "<script>
        alert(
            'Guest ID: $guest_id\n' +
            'Description: $description\n' +
            'Amount: $amount\n' +
            'Date: $date'
        );
        </script>";

        // Basic validation
        if (!empty($guest_id) && !empty($description) && $amount >= 0 && !empty($date)) {
            // Prepare insert query
            $stmt = $conn->prepare("INSERT INTO additional_charge (guest_id, description, amount, date) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isds", $guest_id, $description, $amount, $date);

            if ($stmt->execute()) {
                $_SESSION['charge_success'] = "Charge added successfully!";
            } else {
                $_SESSION['charge_success'] = "Error saving charge.";
            }

            $stmt->close();

            // ✅ Redirect to prevent resubmission
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "<script>alert('⚠️ Please fill in all required fields correctly.');</script>";
        }
    }
}

// Fetch bookings
$sql = "
    SELECT g.*, r.room_type_id, rt.type, rt.price
    FROM guests g
    LEFT JOIN rooms r ON g.room_id = r.room_id
    LEFT JOIN room_types rt ON r.room_type_id = rt.room_type_id
    WHERE g.status = 'checked_in'
";
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

        <?php if (isset($_SESSION['charge_success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo $_SESSION['charge_success']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['charge_success']); ?>
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
                <i class="fas fa-list me-2"></i> Guest Check-In List
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="bookingsTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th>Guest Name</th>
                                <th>Check-in Date</th>
                                <th>Room Type</th>
                                <th>Status</th>
                                <th>Assigned Room</th>
                                <th>Payment Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result && $result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($row['checkin_date'])); ?></td>
                                        <td><?php echo htmlspecialchars($row['type']); ?></td>
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
                                            <?php
                                            $has_unpaid = hasUnpaidBills($row['guest_id'], $conn);
                                            if ($has_unpaid): ?>
                                                <span class="badge bg-danger">Unpaid Bills</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Fully Paid</span>
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
                                                <?php
                                                $has_unpaid = hasUnpaidBills($row['guest_id'], $conn);
                                                ?>

                                                <!-- Check Out Form - Only show if fully paid -->
                                                <?php if (!$has_unpaid): ?>
                                                    <form action="" method="POST" onsubmit="return confirm('Are you sure you want to check out this guest?');" style="display:inline;">
                                                        <input type="hidden" name="email" value="<?php echo htmlspecialchars($row['email']); ?>">
                                                        <input type="hidden" name="guest_id" value="<?php echo htmlspecialchars($row['guest_id']); ?>">
                                                        <button type="submit" name="check_out" class="btn btn-secondary btn-sm">
                                                            <i class="fas fa-door-open"></i> Check Out
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <button type="button" class="btn btn-outline-secondary btn-sm" disabled title="Guest has unpaid bills">
                                                        <i class="fas fa-door-open"></i> Check Out
                                                    </button>
                                                <?php endif; ?>

                                                <!-- ✅ Additional Charge Button (Triggers Modal) -->
                                                <button type="button" class="btn btn-warning btn-sm additional-charge-btn" data-bs-toggle="modal" data-bs-target="#addChargeModal"
                                                    data-guest-id="<?php echo htmlspecialchars($row['guest_id']); ?>"
                                                    data-guest-name="<?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>">
                                                    <i class="fas fa-plus-circle"></i> Additional Charge
                                                </button>

                                                <button type="button" class="btn btn-info btn-sm view-incident-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewIncidentModal"
                                                    data-guest-id="<?php echo htmlspecialchars($row['guest_id']); ?>"
                                                    data-guest-name="<?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>">
                                                    <i class="fas fa-exclamation-circle"></i> View Incident
                                                </button>
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

        <div class="card mt-4">
            <div class="card-header">
                <i class="fas fa-list me-2"></i> Guest Check-Out List
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="bookingsTable2" class="table table-hover">
                        <thead>
                            <tr>
                                <th>Guest Name</th>
                                <th>Check-in Date</th>
                                <th>Check-out Date</th>
                                <th>Room Type</th>
                                <th>Assigned Room</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "
                        SELECT g.*, r.room_type_id, rt.type, rt.price, r.room_id
                        FROM guests g
                        LEFT JOIN rooms r ON g.room_id = r.room_id
                        LEFT JOIN room_types rt ON r.room_type_id = rt.room_type_id
                        WHERE g.status = 'checked_out'
                    ";
                            $result2 = $conn->query($sql);
                            ?>

                            <?php if ($result2 && $result2->num_rows > 0): ?>
                                <?php while ($row = $result2->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                        <td>
                                            <?php echo $row['checkin_date']
                                                ? date('M d, Y', strtotime($row['checkin_date']))
                                                : ''; ?>
                                        </td>

                                        <td>
                                            <?php echo $row['checkout_date']
                                                ? date('M d, Y', strtotime($row['checkout_date']))
                                                : ''; ?>
                                        </td>

                                        <td><?php echo htmlspecialchars($row['type'] ?? 'Standard'); ?></td>
                                        <td><?php echo htmlspecialchars($row['room_id'] ?? ''); ?></td>

                                        <td>
                                            <form action="" method="POST"
                                                onsubmit="return confirm('Are you sure you want to check out this guest?');">
                                                <input type="hidden" name="email" value="<?php echo htmlspecialchars($row['email']); ?>">
                                                <input type="hidden" name="guest_id" value="<?php echo htmlspecialchars($row['guest_id']); ?>">
                                                <button type="submit" name="archive" class="btn btn-secondary btn-sm">
                                                    <i class="fas fa-door-open"></i> Archive
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <!-- <tr>
                                    <td colspan="6" class="text-center py-4">No checked-out guests found</td>
                                </tr> -->
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ View Incident Modal (Single instance) -->
    <div class="modal fade" id="viewIncidentModal" tabindex="-1" aria-labelledby="viewIncidentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewIncidentModalLabel">View Additional Charges</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="incidentGuestId">
                    <h6>Guest: <span id="incidentGuestName"></span></h6>
                    <hr>

                    <!-- Table for Additional Charges -->
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered text-center align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Amount (₱)</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="incidentTableBody">
                                <tr>
                                    <td colspan="5" class="text-muted">No records found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Controls -->
                    <nav>
                        <ul class="pagination justify-content-center" id="incidentPagination"></ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ Additional Charge Modal (Single instance) -->
    <div class="modal fade" id="addChargeModal" tabindex="-1" aria-labelledby="addChargeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addChargeModalLabel">Add Additional Charge</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="guest_id" id="modalGuestId">

                        <!-- Guest Name Field (UNCOMMENTED) -->
                        <div class="mb-3">
                            <label class="form-label">Guest Name</label>
                            <input type="text" id="modalGuestName" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <input type="text" class="form-control" name="charge_description" placeholder="Enter description" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Amount (₱)</label>
                            <input type="number" class="form-control" name="charge_amount" step="0.01" min="0" placeholder="Enter amount" required>
                        </div>

                        <!-- ✅ Date Field -->
                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" class="form-control" name="charge_date" id="chargeDate" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="additional_c_save_charge" class="btn btn-primary">Save Charge</button>
                    </div>
                </form>
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
            $('#bookingsTable2').DataTable({
                responsive: true,
                order: [
                    [0, 'desc']
                ],
                columnDefs: [{
                        targets: 4,
                        className: "dt-head-left"
                    } // 4 means 5th column (Assigned Room)
                ]
            });

            // Handle assign button click
            $('.assign-btn').on('click', function() {
                const bookId = $(this).data('book-id');
                const guestName = $(this).data('guest-name');

                $('#modalBookId').val(bookId);
                $('#guestName').val(guestName);
            });

            // ✅ Handle additional charge button click
            $(document).on('click', '.additional-charge-btn', function() {
                const guestId = $(this).data('guest-id');
                const guestName = $(this).data('guest-name');

                console.log('Setting guest data:', guestId, guestName); // Debug log

                $('#modalGuestId').val(guestId);
                $('#modalGuestName').val(guestName);

                // ✅ Auto-fill today's date in the date input
                const today = new Date().toISOString().split('T')[0];
                $('#chargeDate').val(today);
            });

            // ✅ Handle view incident modal
            $(document).on('click', '.view-incident-btn', function() {
                const guestId = $(this).data('guest-id');
                const guestName = $(this).data('guest-name');

                $('#incidentGuestId').val(guestId);
                $('#incidentGuestName').text(guestName);

                // Fetch incidents (additional charges) via AJAX
                fetch('fetch_incidents.php?guest_id=' + guestId)
                    .then(response => response.json())
                    .then(data => {
                        const tbody = $('#incidentTableBody');
                        const pagination = $('#incidentPagination');
                        tbody.empty();
                        pagination.empty();

                        console.log('Raw data from server:', data); // Debug log

                        if (data.length === 0) {
                            tbody.html('<tr><td colspan="5" class="text-muted">No additional charges found.</td></tr>');
                            return;
                        }

                        const perPage = 5;
                        let currentPage = 1;

                        function renderPage(page) {
                            tbody.empty();
                            const start = (page - 1) * perPage;
                            const end = start + perPage;
                            const pageData = data.slice(start, end);

                            pageData.forEach((row, index) => {
                                // FIXED: Properly check if paid is 1 (using parseInt for safety)
                                const isPaid = parseInt(row.paid) === 1;
                                console.log('Row:', row, 'isPaid:', isPaid); // Debug log

                                const statusBadge = isPaid ?
                                    '<span class="badge bg-success">Paid</span>' :
                                    '<span class="badge bg-danger">Unpaid</span>';

                                tbody.append(`
                                    <tr>
                                        <td>${start + index + 1}</td>
                                        <td>${row.date}</td>
                                        <td>${row.description}</td>
                                        <td>₱${parseFloat(row.amount).toFixed(2)}</td>
                                        <td>${statusBadge}</td>
                                    </tr>
                                `);
                            });

                            renderPagination(page);
                        }

                        function renderPagination(page) {
                            pagination.empty();
                            const totalPages = Math.ceil(data.length / perPage);

                            for (let i = 1; i <= totalPages; i++) {
                                pagination.append(`
                                    <li class="page-item ${i === page ? 'active' : ''}">
                                        <button class="page-link">${i}</button>
                                    </li>
                                `);
                            }

                            pagination.find('.page-link').each(function(i, btn) {
                                $(btn).on('click', function() {
                                    currentPage = i + 1;
                                    renderPage(currentPage);
                                });
                            });
                        }

                        renderPage(1);
                    })
                    .catch(err => {
                        console.error('Error fetching incidents:', err);
                        $('#incidentTableBody').html('<tr><td colspan="5" class="text-danger">Error loading data.</td></tr>');
                    });
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