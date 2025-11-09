<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

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
        $days = $_POST['days'];

        // Clean up the amounts (remove peso symbol and commas)
        $room_charge_raw = $_POST['room_charge'];
        $room_charge = floatval(preg_replace('/[^\d.]/', '', $room_charge_raw));

        $total_amount_raw = $_POST['total_amount'];
        $total_amount = floatval(preg_replace('/[^\d.]/', '', $total_amount_raw));

        // Validate inputs
        if ($room_charge <= 0) {
            $_SESSION['error_message'] = "Invalid room charge amount.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }

        if ($days < 1 || $days > 31) {
            $_SESSION['error_message'] = "Invalid number of days.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }

        // Check for duplicate billing
        $duplicate_check = $conn->prepare("
    SELECT t.transaction_id 
    FROM transactions t
    INNER JOIN guests g ON t.guest_id = g.guest_id
    WHERE t.guest_id = ? 
    AND t.bill_month = ? 
    AND t.transaction_date >= g.checkin_date
");
        $duplicate_check->bind_param("is", $guest_id, $bill_month);
        $duplicate_check->execute();
        $duplicate_result = $duplicate_check->get_result();

        if ($duplicate_result->num_rows > 0) {
            $_SESSION['error_message'] = "Bill already exists for this guest and month in their current stay.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }

        $paid = 1;

        // Get room price via guest_id with validation
        $stmt = $conn->prepare("SELECT room_type_id, room_id FROM rooms WHERE guest_id = ?");
        $stmt->bind_param("i", $guest_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $room_id = $row['room_id'];
            $room_type_id = $row['room_type_id'];

            // Validate guest is actually assigned to this room
            $validate_guest = $conn->prepare("SELECT guest_id FROM rooms WHERE guest_id = ? AND room_id = ?");
            $validate_guest->bind_param("ii", $guest_id, $room_id);
            $validate_guest->execute();
            $validation_result = $validate_guest->get_result();

            if ($validation_result->num_rows === 0) {
                $_SESSION['error_message'] = "Guest is not assigned to this room.";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            }
        } else {
            $_SESSION['error_message'] = "Guest is not assigned to any room.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }

        // Insert into transactions
        $stmt = $conn->prepare("
            INSERT INTO transactions 
            (guest_id, room_id, room_type_id, bill_month, days_rendered, room_charge, total_amount, is_paid) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "iiisiddi",
            $guest_id,
            $room_id,
            $room_type_id,
            $bill_month,
            $days,
            $room_charge,
            $total_amount,
            $paid
        );

        if ($stmt->execute()) {
            // Mark additional charges as paid
            $transaction_id = $stmt->insert_id;

            if (isset($_POST['incident_charges']) && !empty($_POST['incident_charges'])) {
                $incident_ids = $_POST['incident_charges'];
                $placeholders = str_repeat('?,', count($incident_ids) - 1) . '?';

                $sql_update = "UPDATE additional_charge SET paid = 1, transaction_id = ? WHERE id IN ($placeholders)";
                $stmt_update = $conn->prepare($sql_update);

                $params = array_merge([$transaction_id], $incident_ids);
                $types = 'i' . str_repeat('i', count($incident_ids));

                $stmt_update->bind_param($types, ...$params);
                $stmt_update->execute();
            }

            $_SESSION['success_message'] = "Bill successfully recorded.";
        } else {
            $_SESSION['error_message'] = "Error saving bill: " . $stmt->error;
        }

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// FIXED: Function to get unpaid months for CURRENT stay only
function getUnpaidMonthsgg($guest_id, $conn)
{
    $unpaidMonths = [];

    // Get current check-in date for this specific stay
    $checkin_sql = "SELECT checkin_date, checkout_date FROM guests WHERE guest_id = ? AND status = 'checked_in'";
    $stmt_check = $conn->prepare($checkin_sql);
    $stmt_check->bind_param("i", $guest_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $guest_data = $result_check->fetch_assoc();
    $stmt_check->close();

    if (!$guest_data) {
        return $unpaidMonths; // Guest not found or not checked in
    }

    $current_checkin = $guest_data['checkin_date'];

    // If guest is checked out, use checkout_date as end date, otherwise use current date
    $end_date = $guest_data['checkout_date'] ? $guest_data['checkout_date'] : date('Y-m-d');

    $start = new DateTime($current_checkin);
    $start->modify('first day of this month');

    $now = new DateTime($end_date);
    $now->modify('first day of this month');

    while ($start <= $now) {
        $billMonth = $start->format('Y-m');

        // Check if transaction exists for this month AND is from current stay period
        $stmt = $conn->prepare("
            SELECT 1 FROM transactions 
            WHERE guest_id = ? AND bill_month = ? 
            AND transaction_date >= ?
        ");
        $stmt->bind_param("iss", $guest_id, $billMonth, $current_checkin);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            $unpaidMonths[] = [
                'value' => $billMonth,
                'label' => $start->format('F Y'),
                'days_in_month' => $start->format('t'),
                'checkin_date' => $current_checkin
            ];
        }
        $stmt->close();
        $start->modify('+1 month');
    }
    return $unpaidMonths;
}

// Fixed calculateActualDays function
// Fixed calculateActualDays function in PHP
// Fixed calculateActualDays function in PHP
function calculateActualDays($checkinDate, $billMonth)
{
    $checkin = new DateTime($checkinDate);
    $billStart = new DateTime($billMonth . '-01');
    $billEnd = new DateTime($billMonth . '-01');
    $billEnd->modify('last day of this month');

    // Get current date for current month comparison
    $currentDate = new DateTime();
    $currentYearMonth = $currentDate->format('Y-m');

    // If billing for current month, use current date as end date
    $endDate = $billEnd;
    if ($billMonth === $currentYearMonth) {
        $endDate = $currentDate;
    }

    // If check-in is after the end date, return 0
    if ($checkin > $endDate) {
        return 0;
    }

    // If check-in is before bill month, use bill start
    if ($checkin < $billStart) {
        $startDate = $billStart;
    } else {
        $startDate = $checkin;
    }

    // Reset times to avoid time calculation issues
    $startDate->setTime(0, 0, 0);
    $endDate->setTime(0, 0, 0);

    // Calculate days (inclusive)
    $interval = $startDate->diff($endDate);
    $days = $interval->days + 1;

    return $days;
}

// FIXED: Function to get unpaid incident charges for CURRENT stay only
function getUnpaidIncidentCharges($guestId, $conn)
{
    $charges = [];

    // Get current check-in date
    $checkin_sql = "SELECT checkin_date FROM guests WHERE guest_id = ? AND status = 'checked_in'";
    $stmt_check = $conn->prepare($checkin_sql);
    $stmt_check->bind_param("i", $guestId);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $guest_data = $result_check->fetch_assoc();
    $stmt_check->close();

    if (!$guest_data) {
        return $charges;
    }

    $current_checkin = $guest_data['checkin_date'];

    $stmt = $conn->prepare("
        SELECT id, description, date, amount 
        FROM additional_charge 
        WHERE guest_id = ? AND paid = 0 AND date >= ?
    ");
    $stmt->bind_param("is", $guestId, $current_checkin);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $charges[] = $row;
    }
    return $charges;
}
?>
<!DOCTYPE html>
<html>
<?php include 'head.php'; ?>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="topbar">
            <h4>Generate New Bill (Monthly)</h4>
        </div>

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

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $guestId = $row['guest_id'];
                    $guestName = htmlspecialchars($row['first_name'] . ' ' . $row['last_name']);
                    $roomType = htmlspecialchars($row['room_type'] ?? 'N/A');
                    $price = isset($row['price']) ? number_format($row['price'], 2) : '0.00';

                    // FIXED: Pass guest_id and connection only
                    $unpaid = getUnpaidMonthsgg($guestId, $conn);
                    $incidentCharges = getUnpaidIncidentCharges($guestId, $conn);

                    $guestData[$guestId] = [
                        'room_type' => $roomType,
                        'price' => $price,
                        'unpaid_months' => $unpaid,
                        'incident_charges' => $incidentCharges,
                        'checkin_date' => $row['checkin_date']
                    ];

                    $optionsHtml[] = "<option value='{$guestId}'>{$guestName}</option>";
                }
            }
            ?>

            <form method="POST" action="" id="billingForm">
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
                        <input type="hidden" id="checkinDate" value="">
                    </div>
                </div>

                <!-- Unpaid Months Dropdown + Days Input -->
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Select Month</label>
                        <select class="form-select" name="bill_month" id="billMonthSelect" required>
                            <option value="">-- Select Month --</option>
                        </select>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label">Days</label>
                        <input type="number" class="form-control" name="days" id="daysInput" min="1" max="31" value="0" required readonly>
                        <small class="form-text text-muted" id="daysExplanation"></small>
                    </div>
                </div>

                <!-- Room Amount Calculation -->
                <div class="mb-3">
                    <label class="form-label">Room Amount</label>
                    <input type="text" class="form-control" name="room_charge" id="roomAmount" readonly placeholder="Room price × days">
                </div>

                <!-- Incident Charges Section -->
                <div class="mb-4" id="incidentChargesSection" style="display: none;">
                    <h5>Unpaid Incident Charges</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>Include</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody id="incidentChargesBody">
                                <!-- Incident charges will be populated here by JavaScript -->
                            </tbody>
                        </table>
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

                // Function to calculate actual days stayed
                // Fixed calculateActualDays function
                // Fixed calculateActualDays function
                function calculateActualDays(checkinDate, billMonth) {
                    const checkin = new Date(checkinDate);
                    const billStart = new Date(billMonth + '-01');
                    const billEnd = new Date(billStart.getFullYear(), billStart.getMonth() + 1, 0);

                    // Get current date for the current month
                    const currentDate = new Date();
                    const currentYearMonth = currentDate.getFullYear() + '-' + String(currentDate.getMonth() + 1).padStart(2, '0');

                    // If billing for current month, use current date as end date
                    let endDate = billEnd;
                    if (billMonth === currentYearMonth) {
                        endDate = currentDate;
                    }

                    // If check-in is after the end date, return 0
                    if (checkin > endDate) {
                        return 0;
                    }

                    // If check-in is before bill month, use bill start
                    let startDate = checkin;
                    if (checkin < billStart) {
                        startDate = billStart;
                    }

                    // Reset times to midnight to avoid time calculation issues
                    startDate.setHours(0, 0, 0, 0);
                    endDate.setHours(0, 0, 0, 0);

                    // Calculate days (inclusive) - this is the key fix
                    const timeDiff = endDate.getTime() - startDate.getTime();
                    const days = Math.floor(timeDiff / (1000 * 3600 * 24)) + 1;

                    return days;
                }

                document.getElementById('guestSelect').addEventListener('change', function() {
                    const selectedId = this.value;
                    const roomInfo = document.getElementById('roomInfo');
                    const monthSelect = document.getElementById('billMonthSelect');
                    const roomPriceInput = document.getElementById('roomPrice');
                    const daysInput = document.getElementById('daysInput');
                    const checkinDateInput = document.getElementById('checkinDate');
                    const incidentSection = document.getElementById('incidentChargesSection');
                    const incidentBody = document.getElementById('incidentChargesBody');
                    const daysExplanation = document.getElementById('daysExplanation');

                    // Reset fields
                    monthSelect.innerHTML = '<option value="">-- Select Month --</option>';
                    daysInput.value = 0;
                    daysInput.setAttribute('readonly', true);
                    daysExplanation.textContent = '';
                    incidentBody.innerHTML = '';
                    incidentSection.style.display = 'none';

                    if (guestData[selectedId]) {
                        const price = parseFloat(guestData[selectedId].price.replace(/,/g, '')) || 0;
                        const checkinDate = guestData[selectedId].checkin_date;

                        // Display room info
                        roomInfo.value = `${guestData[selectedId].room_type} (₱${guestData[selectedId].price}/day)`;
                        roomPriceInput.value = price;
                        checkinDateInput.value = checkinDate;

                        // Populate unpaid months
                        guestData[selectedId].unpaid_months.forEach(month => {
                            const opt = document.createElement('option');
                            opt.value = month.value;
                            opt.textContent = month.label;
                            opt.setAttribute('data-days-in-month', month.days_in_month);
                            monthSelect.appendChild(opt);
                        });

                        // Populate incident charges
                        const incidentCharges = guestData[selectedId].incident_charges || [];
                        if (incidentCharges.length > 0) {
                            incidentSection.style.display = 'block';
                            incidentCharges.forEach(charge => {
                                const row = document.createElement('tr');
                                row.innerHTML = `
                                    <td>
                                        <input type="checkbox" name="incident_charges[]" value="${charge.id}" 
                                               class="incident-checkbox" data-amount="${charge.amount}" checked>
                                    </td>
                                    <td>${charge.description}</td>
                                    <td>${charge.date}</td>
                                    <td>₱${parseFloat(charge.amount).toLocaleString(undefined, {minimumFractionDigits: 2})}</td>
                                `;
                                incidentBody.appendChild(row);
                            });
                        }

                        updateTotal();
                    } else {
                        roomInfo.value = '';
                        roomPriceInput.value = '';
                        checkinDateInput.value = '';
                        daysInput.value = 0;
                        daysExplanation.textContent = '';
                        updateTotal();
                    }
                });

                document.getElementById('billMonthSelect').addEventListener('change', function() {
                    const selectedId = document.getElementById('guestSelect').value;
                    const monthValue = this.value;
                    const daysInput = document.getElementById('daysInput');
                    const checkinDate = document.getElementById('checkinDate').value;
                    const daysExplanation = document.getElementById('daysExplanation');

                    if (monthValue && selectedId && checkinDate) {
                        const actualDays = calculateActualDays(checkinDate, monthValue);

                        // Get current month for comparison
                        const currentDate = new Date();
                        const currentYearMonth = currentDate.getFullYear() + '-' + String(currentDate.getMonth() + 1).padStart(2, '0');
                        const isCurrentMonth = (monthValue === currentYearMonth);

                        if (isCurrentMonth) {
                            // Current month - allow editing since guest is still staying
                            daysInput.removeAttribute('readonly');
                            daysExplanation.textContent = `Check-in Date: ${checkinDate}`;
                        } else {
                            // Past month - fixed based on actual stay
                            daysInput.setAttribute('readonly', true);
                            const billStart = new Date(monthValue + '-01');
                            const billEnd = new Date(billStart.getFullYear(), billStart.getMonth() + 1, 0);
                            // daysExplanation.textContent = `Check-in Date: ${checkinDate}. Fixed for past month (${monthValue}-01 to ${monthValue}-${billEnd.getDate()}).`;
                            daysExplanation.textContent = `Check-in Date: ${checkinDate}`;

                        }

                        daysInput.value = actualDays;
                    } else {
                        daysInput.value = 0;
                        daysInput.setAttribute('readonly', true);
                        daysExplanation.textContent = '';
                    }
                    updateTotal();
                });

                // Prevent invalid day values
                document.getElementById('daysInput').addEventListener('input', function() {
                    let value = parseInt(this.value);
                    if (isNaN(value) || value < 1) {
                        this.value = 1;
                    } else if (value > 31) {
                        this.value = 31;
                    }
                    updateTotal();
                });

                // Event delegation for incident checkboxes
                document.addEventListener('change', function(e) {
                    if (e.target.classList.contains('incident-checkbox')) {
                        updateTotal();
                    }
                });

                function updateTotal() {
                    const roomPrice = parseFloat(document.getElementById('roomPrice').value) || 0;
                    const days = parseInt(document.getElementById('daysInput').value) || 0;

                    // Calculate room amount
                    const roomAmount = roomPrice * days;
                    document.getElementById('roomAmount').value = `₱${roomAmount.toLocaleString(undefined, {minimumFractionDigits: 2})}`;

                    // Calculate incident charges total
                    let incidentTotal = 0;
                    document.querySelectorAll('.incident-checkbox:checked').forEach(checkbox => {
                        incidentTotal += parseFloat(checkbox.dataset.amount) || 0;
                    });

                    let total = roomAmount + incidentTotal;

                    document.getElementById('totalAmount').value =
                        `₱${total.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
                }

                // Form validation
                document.getElementById('billingForm').addEventListener('submit', function(e) {
                    const days = parseInt(document.getElementById('daysInput').value);
                    const roomAmount = parseFloat(document.getElementById('roomPrice').value) * days;

                    if (days < 1 || days > 31) {
                        e.preventDefault();
                        alert('Please enter a valid number of days (1-31).');
                        return false;
                    }

                    if (roomAmount <= 0) {
                        e.preventDefault();
                        alert('Invalid room amount calculation.');
                        return false;
                    }

                    return true;
                });
            </script>
        </div>

        <!-- Guest List with Unpaid Months -->
        <div class="room-content ">
            <div class="card shadow-sm p-4">
                <?php
                // FIXED: Function to get unpaid months for CURRENT stay only
                function getUnpaidMonths($guestId, $conn)
                {
                    $unpaidMonths = [];

                    // Get current check-in date for this specific stay
                    $checkin_sql = "SELECT checkin_date, checkout_date FROM guests WHERE guest_id = ? AND status = 'checked_in'";
                    $stmt_check = $conn->prepare($checkin_sql);
                    $stmt_check->bind_param("i", $guestId);
                    $stmt_check->execute();
                    $result_check = $stmt_check->get_result();
                    $guest_data = $result_check->fetch_assoc();
                    $stmt_check->close();

                    if (!$guest_data) {
                        return $unpaidMonths;
                    }

                    $current_checkin = $guest_data['checkin_date'];

                    // If guest is checked out, use checkout_date as end date, otherwise use current date
                    $end_date = $guest_data['checkout_date'] ? $guest_data['checkout_date'] : date('Y-m-d');

                    $start = new DateTime($current_checkin);
                    $start->modify('first day of this month');
                    $now = new DateTime($end_date);
                    $now->modify('first day of this month');

                    while ($start <= $now) {
                        $billMonth = $start->format('Y-m');

                        // Check if transaction exists for this month AND is from current stay period
                        $stmt = $conn->prepare("
                            SELECT 1 FROM transactions 
                            WHERE guest_id = ? AND bill_month = ? 
                            AND transaction_date >= ?
                        ");
                        $stmt->bind_param("iss", $guestId, $billMonth, $current_checkin);
                        $stmt->execute();
                        $stmt->store_result();

                        if ($stmt->num_rows === 0) {
                            $unpaidMonths[] = $start->format('F Y');
                        }
                        $stmt->close();
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
                            // FIXED: Pass guest_id and connection only
                            $unpaidMonths = getUnpaidMonths($row['guest_id'], $conn);

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
            $('.alert').delay(5000).fadeOut(400);
        });
    </script>
</body>

</html>