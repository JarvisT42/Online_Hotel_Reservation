<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (!isset($_SESSION['guest_logged_in'])) {
    header("Location: ../login.php");
    exit;
}

include '../connect.php';

$guestId = $_SESSION['guest_id'] ?? null;
?>
<!DOCTYPE html>
<html>
<?php include 'head.php'; ?>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="topbar">
            <h4>Guest Billing Information</h4>
        </div>

        <!-- Next Bill Section -->
        <div class="card shadow-sm p-4 mb-4">
            <h3>Next Bill</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Room #</th>
                        <th>Room Type</th>
                        <th>Daily Rate</th>
                        <th>Next Bill</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($guestId) {
                        $sql = "SELECT 
                            r.room_id,
                            rt.type AS room_type,
                            rt.price AS daily_rate,
                            g.checkin_date
                        FROM guests g
                        LEFT JOIN rooms r ON g.room_id = r.room_id
                        LEFT JOIN room_types rt ON r.room_type_id = rt.room_type_id
                        WHERE g.guest_id = ? AND g.status = 'checked_in'";

                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $guestId);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $dailyRate = (float)$row['daily_rate'];
                                $checkinDate = new DateTime($row['checkin_date']);

                                // Calculate from checkin date or first of current month, whichever is later
                                $firstDay = new DateTime('first day of this month');
                                $today = new DateTime();

                                $startDate = $checkinDate > $firstDay ? $checkinDate : $firstDay;
                                $days = $startDate->diff($today)->days + 1;

                                $nextBill = $days * $dailyRate;

                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['room_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['room_type']) . "</td>";
                                echo "<td>₱" . number_format($dailyRate, 2) . " /day</td>";
                                echo "<td><span class='text-danger'>₱" . number_format($nextBill, 2) . "</span></td>";
                                echo "</tr>";
                            }
                        } else {
                        }
                        $stmt->close();
                    } else {
                        echo "<tr><td colspan='4' class='text-center'>Please login</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>



        <!-- NEW: All Additional Charges Table -->
        <div class="card shadow-sm p-4 mb-4">
            <h3>All Additional Charges</h3>
            <table class="table table-bordered" id="allChargesTable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Transaction ID</th>
                        <th>Bill Month</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($guestId) {
                        $sql = "SELECT 
                            ac.id,
                            ac.date,
                            ac.description,
                            ac.amount,
                            ac.paid,
                            ac.transaction_id,
                            t.bill_month,
                            t.transaction_date
                        FROM additional_charge ac
                        LEFT JOIN transactions t ON ac.transaction_id = t.transaction_id
                        WHERE ac.guest_id = ?
                        ORDER BY ac.date DESC, ac.id DESC";

                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $guestId);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            $totalAllCharges = 0;
                            $totalPaid = 0;
                            $totalUnpaidAll = 0;

                            while ($row = $result->fetch_assoc()) {
                                $status = $row['paid'] ?
                                    "<span class='badge bg-success'>Paid</span>" :
                                    "<span class='badge bg-danger'>Unpaid</span>";

                                $transactionId = $row['transaction_id'] ?
                                    "<span class='badge bg-info'>#" . $row['transaction_id'] . "</span>" :
                                    "<span class='text-muted'>Not assigned</span>";

                                $billMonth = $row['bill_month'] ?
                                    date("F Y", strtotime($row['bill_month'] . '-01')) :
                                    "<span class='text-muted'>-</span>";

                                $totalAllCharges += $row['amount'];
                                if ($row['paid']) {
                                    $totalPaid += $row['amount'];
                                } else {
                                    $totalUnpaidAll += $row['amount'];
                                }

                                echo "<tr>";
                                echo "<td>" . date("M d, Y", strtotime($row['date'])) . "</td>";
                                echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                                echo "<td>₱" . number_format($row['amount'], 2) . "</td>";
                                echo "<td>$status</td>";
                                echo "<td>$transactionId</td>";
                                echo "<td>$billMonth</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>No additional charges found</td></tr>";
                        }
                        $stmt->close();
                    }
                    ?>
                </tbody>
            </table>

            <!-- Summary Section - Moved outside the DataTable -->
            <?php
            if ($guestId && isset($totalAllCharges) && $totalAllCharges > 0) {
                echo '<div class="mt-3">';
                echo '<div class="row">';
                echo '<div class="col-md-4">';
                echo '<div class="card bg-success text-white">';
                echo '<div class="card-body p-3">';
                echo '<h6 class="card-title">Total Paid Charges</h6>';
                echo '<h4>₱' . number_format($totalPaid, 2) . '</h4>';
                echo '</div>';
                echo '</div>';
                echo '</div>';

                echo '<div class="col-md-4">';
                echo '<div class="card bg-danger text-white">';
                echo '<div class="card-body p-3">';
                echo '<h6 class="card-title">Total Unpaid Charges</h6>';
                echo '<h4>₱' . number_format($totalUnpaidAll, 2) . '</h4>';
                echo '</div>';
                echo '</div>';
                echo '</div>';

                echo '<div class="col-md-4">';
                echo '<div class="card bg-primary text-white">';
                echo '<div class="card-body p-3">';
                echo '<h6 class="card-title">Grand Total</h6>';
                echo '<h4>₱' . number_format($totalAllCharges, 2) . '</h4>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>

        <!-- Bill History -->
        <div class="card shadow-sm p-4 mb-4">
            <h3>Bill History</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Room #</th>
                        <th>Room Type</th>
                        <th>Room Charge</th>
                        <th>Description Charge</th>
                        <th>Additional Charge</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($guestId) {
                        $sql = "SELECT 
                t.transaction_id,
                t.bill_month,
                r.room_id,
                rt.type AS room_type,
                t.room_charge,
                t.total_amount,
                t.is_paid,
                t.transaction_date
            FROM transactions t
            LEFT JOIN rooms r ON t.room_id = r.room_id
            LEFT JOIN room_types rt ON t.room_type_id = rt.room_type_id
            WHERE t.guest_id = ?
            ORDER BY t.transaction_date DESC";

                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $guestId);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                // Convert YYYY-MM to Month Year
                                $billMonth = DateTime::createFromFormat('Y-m', $row['bill_month']);

                                // Get additional charges for this transaction
                                $additional_sql = "SELECT 
                        SUM(amount) as total_additional,
                        GROUP_CONCAT(description SEPARATOR ', ') as descriptions 
                        FROM additional_charge 
                        WHERE transaction_id = ?";

                                $stmt2 = $conn->prepare($additional_sql);
                                $stmt2->bind_param("i", $row['transaction_id']);
                                $stmt2->execute();
                                $additional_result = $stmt2->get_result();
                                $additional_row = $additional_result->fetch_assoc();
                                $additional_charges = $additional_row['total_additional'] ?? 0;
                                $description_charges = $additional_row['descriptions'] ?? 'None';

                                $stmt2->close();

                                echo "<tr>";
                                echo "<td>" . ($billMonth ? $billMonth->format('F Y') : htmlspecialchars($row['bill_month'])) . "</td>";
                                echo "<td>" . htmlspecialchars($row['room_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['room_type']) . "</td>";
                                echo "<td>₱" . number_format($row['room_charge'], 2) . "</td>";
                                echo "<td><small>" . htmlspecialchars($description_charges) . "</small></td>";
                                echo "<td>₱" . number_format($additional_charges, 2) . "</td>";
                                echo "<td><strong>₱" . number_format($row['total_amount'], 2) . "</strong></td>";

                                // Status
                                $status = $row['is_paid']
                                    ? "<span class='badge bg-success'>Paid</span>"
                                    : "<span class='badge bg-danger'>Unpaid</span>";
                                echo "<td>$status</td>";

                                // Created at
                                echo "<td>" . date("M d, Y H:i", strtotime($row['transaction_date'])) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9' class='text-center'>No billing history found</td></tr>";
                        }
                        $stmt->close();
                    } else {
                        echo "<tr><td colspan='9' class='text-center'>Please login</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>

    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../node_modules/datatables.net/js/dataTables.min.js"></script>
    <script src="../node_modules/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTables for regular tables
            $('.table:not(#allChargesTable)').DataTable({
                responsive: true,
                order: [
                    [0, 'desc']
                ]
            });

            // Initialize DataTables for All Additional Charges table with proper configuration
            $('#allChargesTable').DataTable({
                responsive: true,
                order: [
                    [0, 'desc']
                ], // Order by first column (Date) descending
                columns: [{
                        data: 'date'
                    }, // Column 0: Date
                    {
                        data: 'description'
                    }, // Column 1: Description
                    {
                        data: 'amount'
                    }, // Column 2: Amount
                    {
                        data: 'status'
                    }, // Column 3: Status
                    {
                        data: 'transaction'
                    }, // Column 4: Transaction ID
                    {
                        data: 'bill_month'
                    } // Column 5: Bill Month
                ],
                language: {
                    emptyTable: "No additional charges found"
                }
            });

            // Auto-close alerts after 5 seconds
            $('.alert').delay(5000).fadeOut(400);
        });
    </script>
</body>

</html>