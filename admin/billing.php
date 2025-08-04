<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit;
}

include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['pay'])) {
        include 'connect.php';

        $guest_id = $_POST['guest_id'];
        $bill_month = $_POST['bill_month'];
        $additional_charges = $_POST['description'];

        $amount = $_POST['amount'];
        $paid = "paid";

        // Get room price via guest_id
        $stmt = $conn->prepare("SELECT room_type_id, room_id FROM rooms WHERE guest_id = ?");
        $stmt->bind_param("i", $guest_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $room_type_id = null;
        if ($row = $result->fetch_assoc()) {
            $room_id = $row['room_id'];

            $room_type_id = $row['room_type_id'];
        }

        // Step 2: Get price from room_types table
        $room_price = 0;
        if ($room_type_id !== null) {
            $stmt = $conn->prepare("SELECT price FROM room_types WHERE room_type_id = ?");
            $stmt->bind_param("i", $room_type_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                $room_price = $row['price'];
            }
        }



        $stmt = $conn->prepare("INSERT INTO transactions (guest_id, room_id, room_type_id, bill_month, description, amount, is_paid) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiissds", $guest_id, $room_id, $room_type_id, $bill_month, $additional_charges, $amount, $paid);
        $stmt->execute();

        // 2. Insert each additional service


        $_SESSION['success_message'] = "Bill successfully recorded.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}





?>
<!DOCTYPE html>
<html>
<?php include 'head.php'; ?>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="topbar">
            <h4>Generate New Bill</h4>
        </div>


        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo $_SESSION['success_message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>


        <!-- Summary Boxes with Icons -->
        <!-- <div class="row mb-4">
            <div class="col-md-6">
                <?php
                // Assume $conn is your active mysqli connection
                $sql = "SELECT
                g.guest_id,
                g.first_name,
                g.last_name,
                g.checkin_date,
                r.room_id,
                rt.type AS room_type
                FROM guests g
                LEFT JOIN rooms r ON g.room_id = r.room_id
                LEFT JOIN room_types rt ON r.room_type_id = rt.room_type_id
                WHERE g.checkin_date IS NOT NULL
                ORDER BY g.checkin_date DESC";

                $result = $conn->query($sql);
                $total_bills = $result->num_rows;
                ?>

                <div class="card text-center shadow-sm p-3">
                    <div class="mb-2">
                        <i class="fas fa-file-invoice fa-2x text-primary"></i>
                    </div>
                    <h3 class="mb-1"><?php echo $total_bills; ?></h3>
                    <small class="text-muted">Total Bills</small>
                </div>

            </div>

            <div class="col-md-6">
                <div class="card text-center shadow-sm p-3">
                    <div class="mb-2">
                        <i class="fas fa-hourglass-half fa-2x text-warning"></i>
                    </div>
                    <h3 class="mb-1">0</h3>
                    <small class="text-muted">Pending Payments</small>
                </div>
            </div>

        </div> -->

        <!-- Billing Form -->
        <div class="card shadow-sm p-4 mb-4">
            <?php
            // Fetch guests with room data
            $sql = "
                SELECT 
                    guests.guest_id, 
                    guests.first_name, 
                    guests.last_name, 
                    room_types.type AS room_type, 
                    room_types.price 
                FROM guests
                LEFT JOIN rooms ON guests.room_id = rooms.room_id
                LEFT JOIN room_types ON rooms.room_type_id = room_types.room_type_id
            ";
            $result = $conn->query($sql);

            // Prepare guest options with room info in JavaScript
            $guestData = [];
            $optionsHtml = '';

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $guestId = $row['guest_id'];
                    $guestName = htmlspecialchars($row['first_name'] . ' ' . $row['last_name']);
                    $roomType = htmlspecialchars($row['room_type'] ?? 'N/A');
                    $price = isset($row['price']) ? number_format($row['price'], 2) : '0.00';

                    $guestData[$guestId] = [
                        'room_type' => $roomType,
                        'price' => $price
                    ];

                    $optionsHtml .= "<option value='{$guestId}'>{$guestName}</option>";
                }
            }
            ?>

            <form method="POST" action="">
                <div class="row">
                    <!-- Select Guest -->
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Select Guest</label>
                        <select class="form-select" name="guest_id" id="guestSelect" required>
                            <option value="">-- Select Guest --</option>
                            <?= $optionsHtml ?>
                        </select>
                    </div>

                    <!-- Room Type Info (readonly) -->
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Room Type & Price</label>
                        <input type="text" class="form-control" id="roomInfo" readonly>
                    </div>
                </div>

                <!-- Month -->
                <div class="mb-3">
                    <label class="form-label">For the Month of</label>
                    <input type="month" class="form-control" name="bill_month" required>
                </div>

                <!-- Additional Charges -->
                <div class="mb-4">
                    <h5>Additional Charges</h5>
                    <div class="service-item row mb-2">
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="description" placeholder="Description">
                        </div>
                        <div class="col-md-4">
                            <input type="number" class="form-control" name="amount" placeholder="Amount" step="0.01" min="0">
                        </div>
                    </div>
                </div>

                <button type="submit" name="pay" class="btn btn-primary">Pay Bill</button>
            </form>

            <!-- JS to auto-fill room info -->
            <script>
                const guestRoomData = <?= json_encode($guestData) ?>;

                const guestSelect = document.getElementById('guestSelect');
                const roomInfo = document.getElementById('roomInfo');

                guestSelect.addEventListener('change', function() {
                    const selectedId = this.value;
                    if (guestRoomData[selectedId]) {
                        const data = guestRoomData[selectedId];
                        roomInfo.value = `${data.room_type} (₱${data.price})`;
                    } else {
                        roomInfo.value = '';
                    }
                });
            </script>


        </div>



        <div class="room-content ">
            <div class="card shadow-sm p-4">
                <?php
                include 'connect.php';

                function getUnpaidMonths($checkinDate, $guestId, $conn)
                {
                    $unpaidMonths = [];

                    // Start from the check-in month
                    $start = new DateTime($checkinDate);
                    $start->modify('first day of this month');

                    // Up to the current month
                    $now = new DateTime();
                    $now->modify('first day of this month');

                    while ($start <= $now) {
                        $billMonth = $start->format('Y-m'); // e.g., "2025-07"

                        // Check if this month has a transaction
                        $stmt = $conn->prepare("SELECT 1 FROM transactions WHERE guest_id = ? AND bill_month = ?");
                        $stmt->bind_param("is", $guestId, $billMonth);
                        $stmt->execute();
                        $stmt->store_result();

                        if ($stmt->num_rows === 0) {
                            $unpaidMonths[] = $start->format('F Y'); // e.g., "July 2025"
                        }

                        $start->modify('+1 month');
                    }

                    return $unpaidMonths;
                }
                ?>

                <table id="myTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Guest Name</th>
                            <th>Room #</th>
                            <th>Room Type</th>
                            <th>Check-in Date</th>
                            <th>Unpaid Months</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT 
                    g.guest_id,
                    g.first_name,
                    g.last_name,
                    g.checkin_date,
                    r.room_id,
                    rt.type AS room_type
                FROM guests g
                LEFT JOIN rooms r ON g.room_id = r.room_id
                LEFT JOIN room_types rt ON r.room_type_id = rt.room_type_id
                WHERE g.checkin_date IS NOT NULL
                ORDER BY g.checkin_date DESC";

                        $result = $conn->query($sql);

                        while ($row = $result->fetch_assoc()) {
                            $unpaidMonths = getUnpaidMonths($row['checkin_date'], $row['guest_id'], $conn);

                            if (!empty($unpaidMonths)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['room_id'] ?? '—') . "</td>";
                                echo "<td>" . htmlspecialchars($row['room_type'] ?? '—') . "</td>";
                                echo "<td>" . htmlspecialchars($row['checkin_date']) . "</td>";
                                echo "<td><span class='text-danger'>No payment for " . implode(', ', $unpaidMonths) . "</span></td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>




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
    <script>
        $(document).ready(function() {
            // Initialize DataTable


            // Auto-close alerts after 5 seconds
            $('.alert').delay(5000).fadeOut(400);
        });
    </script>
</body>

</html>