<?php
session_start();
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
                            echo "<tr><td colspan='4' class='text-center'>No room assigned</td></tr>";
                        }
                        $stmt->close();
                    } else {
                        echo "<tr><td colspan='4' class='text-center'>Please login</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Unpaid Additional Charges -->
        <div class="card shadow-sm p-4 mb-4">
            <h3>Unpaid Additional Charges</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description Charge</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($guestId) {
                        $sql = "SELECT 
                            date,
                            description,
                            amount,
                            paid
                        FROM additional_charge 
                        WHERE guest_id = ? AND paid = 0
                        ORDER BY date DESC";

                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $guestId);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            $totalUnpaid = 0;
                            while ($row = $result->fetch_assoc()) {
                                $status = $row['paid'] ?
                                    "<span class='badge bg-success'>Paid</span>" :
                                    "<span class='badge bg-danger'>Unpaid</span>";

                                $totalUnpaid += $row['amount'];

                                echo "<tr>";
                                echo "<td>" . date("M d, Y", strtotime($row['date'])) . "</td>";
                                echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                                echo "<td>₱" . number_format($row['amount'], 2) . "</td>";
                                echo "<td>$status</td>";
                                echo "</tr>";
                            }
                            // Display total row
                            echo "<tr class='table-warning'>";
                            echo "<td colspan='2'><strong>Total Unpaid Charges</strong></td>";
                            echo "<td><strong>₱" . number_format($totalUnpaid, 2) . "</strong></td>";
                            echo "<td></td>";
                            echo "</tr>";
                        } else {
                            // echo "<tr><td colspan='4' class='text-center'>No unpaid additional charges</td></tr>";
                        }
                        $stmt->close();
                    }
                    ?>
                </tbody>
            </table>
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
                t.created_at
            FROM transactions t
            LEFT JOIN rooms r ON t.room_id = r.room_id
            LEFT JOIN room_types rt ON t.room_type_id = rt.room_type_id
            WHERE t.guest_id = ?
            ORDER BY t.created_at DESC";

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
                                echo "<td>" . date("M d, Y H:i", strtotime($row['created_at'])) . "</td>";
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

        <!-- Transaction Details Modal -->
        <div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="transactionModalLabel">Transaction Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="transactionDetails">
                        <!-- Transaction details will be loaded here via JavaScript -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
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
            // Initialize DataTables
            $('.table').DataTable({
                responsive: true,
                order: [
                    [0, 'desc']
                ]
            });

            // Function to view transaction details
            function viewTransactionDetails(transactionId) {
                $.ajax({
                    url: 'get_transaction_details.php',
                    type: 'GET',
                    data: {
                        transaction_id: transactionId
                    },
                    success: function(response) {
                        $('#transactionDetails').html(response);
                        $('#transactionModal').modal('show');
                    },
                    error: function() {
                        $('#transactionDetails').html('<div class="alert alert-danger">Error loading transaction details.</div>');
                        $('#transactionModal').modal('show');
                    }
                });
            }

            // Auto-close alerts after 5 seconds
            $('.alert').delay(5000).fadeOut(400);
        });
    </script>
</body>

</html>