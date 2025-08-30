<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit;
}

include '../connect.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['pay'])) {
        include '../connect.php';

        $guest_id = $_POST['guest_id'];
        $bill_month = $_POST['bill_month'];
        $additional_charges = $_POST['description'];
        $amount = (float) $_POST['amount'];

        // Clean up the total amount (remove peso symbol and commas)
        $total_amount_raw = $_POST['total_amount'];
        $total_amount = floatval(preg_replace('/[^\d.]/', '', $total_amount_raw));

        $paid = 1; // assuming is_paid is a boolean/int in DB

        // Get room price via guest_id
        $stmt = $conn->prepare("SELECT room_type_id, room_id FROM rooms WHERE guest_id = ?");
        $stmt->bind_param("i", $guest_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $room_id = $row['room_id'];
            $room_type_id = $row['room_type_id'];
        }

        // Insert into transactions
        $stmt = $conn->prepare("
            INSERT INTO transactions 
            (guest_id, room_id, room_type_id, bill_month, description, amount, total_amount, is_paid) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "iiissddi",
            $guest_id,
            $room_id,
            $room_type_id,
            $bill_month,
            $additional_charges,
            $amount,
            $total_amount,
            $paid
        );

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Bill successfully recorded.";
        } else {
            $_SESSION['error_message'] = "Error saving bill: " . $stmt->error;
        }

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

             

        </div> -->

        <!-- Billing Form -->
        <div class="card shadow-sm p-4 mb-4">
            <?php
            // Fetch guest + room data
            $sql = "
    SELECT 
        guests.guest_id, 
        guests.first_name, 
        guests.last_name, 
        room_types.type AS room_type, 
        room_types.price,
        guests.checkin_date
    FROM guests
    LEFT JOIN rooms ON guests.room_id = rooms.room_id
    LEFT JOIN room_types ON rooms.room_type_id = room_types.room_type_id
    WHERE guests.status = 'checked_in'
";
            $result = $conn->query($sql);

            $guestData = [];
            $optionsHtml = [];


            function getUnpaidMonthsgg($checkinDate, $guestId, $conn)
            {
                $unpaidMonths = [];
                $start = new DateTime($checkinDate);
                $start->modify('first day of this month');

                $now = new DateTime();
                $now->modify('first day of this month');

                while ($start <= $now) {
                    $billMonth = $start->format('Y-m');
                    $stmt = $conn->prepare("SELECT 1 FROM transactions WHERE guest_id = ? AND bill_month = ?");
                    $stmt->bind_param("is", $guestId, $billMonth);
                    $stmt->execute();
                    $stmt->store_result();

                    if ($stmt->num_rows === 0) {
                        $unpaidMonths[] = [
                            'value' => $billMonth,
                            'label' => $start->format('F Y')
                        ];
                    }
                    $start->modify('+1 month');
                }
                return $unpaidMonths;
            }

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $guestId = $row['guest_id'];
                    $guestName = htmlspecialchars($row['first_name'] . ' ' . $row['last_name']);
                    $roomType = htmlspecialchars($row['room_type'] ?? 'N/A');
                    $price = isset($row['price']) ? number_format($row['price'], 2) : '0.00';

                    $unpaid = getUnpaidMonthsgg($row['checkin_date'], $guestId, $conn);

                    $guestData[$guestId] = [
                        'room_type' => $roomType,
                        'price' => $price,
                        'unpaid_months' => $unpaid
                    ];

                    $optionsHtml[] = "<option value='{$guestId}'>{$guestName}</option>";
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
                            <?= implode('', $optionsHtml) ?>
                        </select>
                    </div>

                    <!-- Room Type Info -->
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Room Type & Price</label>
                        <input type="text" class="form-control" id="roomInfo" readonly>
                        <input type="hidden" name="room_price" id="roomPrice">
                    </div>
                </div>

                <!-- Unpaid Months Dropdown -->
                <!-- Unpaid Months Dropdown + Days Input -->
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Select Guest</label>
                        <select class="form-select" name="bill_month" id="billMonthSelect" required>
                            <option value="">-- Select Month --</option>
                        </select>
                    </div>

                    <!-- Room Type Info -->
                    <div class="mb-3 col-md-6">
                        <small class="form-label">Days</small>
                        <input type="number" class="form-control" name="days" id="daysInput" min="1" value="0" required>

                    </div>






                </div>






                <!-- Additional Charges -->
                <div class="mb-4">
                    <h5>Additional Charges</h5>
                    <div class="service-item row mb-2">
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="description" placeholder="Description">
                        </div>
                        <div class="col-md-4">
                            <input type="number" class="form-control" name="amount" id="additionalAmount" placeholder="Amount" step="0.01" min="0">
                        </div>
                    </div>
                </div>

                <!-- Total Amount -->
                <div class="mb-3">
                    <label class="form-label">Total Amount</label>
                    <input type="text" name="total_amount" class="form-control" id="totalAmount" readonly>
                </div>

                <button type="submit" name="pay" class="btn btn-primary">Pay Bill</button>
            </form>


            <script>
                const guestData = <?= json_encode($guestData) ?>;

                document.getElementById('guestSelect').addEventListener('change', function() {
                    const selectedId = this.value;
                    const roomInfo = document.getElementById('roomInfo');
                    const monthSelect = document.getElementById('billMonthSelect');
                    const roomPriceInput = document.getElementById('roomPrice');
                    const daysInput = document.getElementById('daysInput');

                    // Reset month dropdown
                    monthSelect.innerHTML = '<option value="">-- Select Month --</option>';
                    daysInput.value = 0;

                    if (guestData[selectedId]) {
                        const price = parseFloat(guestData[selectedId].price.replace(/,/g, '')) || 0;

                        // Display in room info
                        roomInfo.value = `${guestData[selectedId].room_type} (₱${guestData[selectedId].price}/day)`;

                        // Store numeric daily price
                        roomPriceInput.value = price;

                        // Populate unpaid months
                        guestData[selectedId].unpaid_months.forEach(month => {
                            const opt = document.createElement('option');
                            opt.value = month.value;
                            opt.textContent = month.label;
                            monthSelect.appendChild(opt);
                        });

                        updateTotal();
                    } else {
                        roomInfo.value = '';
                        roomPriceInput.value = '';
                        daysInput.value = 0;
                        updateTotal();
                    }
                });

                document.getElementById('additionalAmount').addEventListener('input', updateTotal);
                document.getElementById('billMonthSelect').addEventListener('change', function() {
                    const monthValue = this.value;
                    const daysInput = document.getElementById('daysInput');

                    if (monthValue) {
                        const [year, month] = monthValue.split('-').map(Number);
                        const daysInMonth = new Date(year, month, 0).getDate();
                        daysInput.value = daysInMonth; // ✅ default full month days
                    } else {
                        daysInput.value = 0;
                    }
                    updateTotal();
                });
                document.getElementById('daysInput').addEventListener('input', updateTotal);

                function updateTotal() {
                    const roomPrice = parseFloat(document.getElementById('roomPrice').value) || 0;
                    const additional = parseFloat(document.getElementById('additionalAmount').value) || 0;
                    const days = parseInt(document.getElementById('daysInput').value) || 0;

                    let total = (roomPrice * days) + additional;

                    document.getElementById('totalAmount').value =
                        `₱${total.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
                }
            </script>



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
                include '../connect.php';

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
        WHERE g.status = 'checked_in' 
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