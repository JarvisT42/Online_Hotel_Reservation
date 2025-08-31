<?php
session_start();
if (!isset($_SESSION['guest_logged_in'])) {
    header("Location: ../login.php");
    exit;
}

include '../connect.php';





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
                    include '../connect.php';
                    $guestId = $_SESSION['guest_id'] ?? null;

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

                                // ✅ First day of current month
                                $firstDay = new DateTime('first day of this month');
                                $today    = new DateTime();

                                // Count days from 1st until today (inclusive)
                                $days = $firstDay->diff($today)->days + 1;

                                // Multiply by daily rate
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



        <div class="card shadow-sm p-4 mb-4">
            <h3>Bill History</h3>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Room #</th>
                        <th>Room Type</th>
                        <th>Description</th>
                        <th>Daily Rate</th>
                        <th>Amount</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include '../connect.php';
                    $guestId = $_SESSION['guest_id'] ?? null;

                    if ($guestId) {
                        $sql = "SELECT 
                            t.bill_month,
                            r.room_id,
                            rt.type AS room_type,
                            t.description,
                            rt.price AS daily_rate,
                            t.amount,
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

                                echo "<tr>";
                                echo "<td>" . ($billMonth ? $billMonth->format('F Y') : htmlspecialchars($row['bill_month'])) . "</td>";
                                echo "<td>" . htmlspecialchars($row['room_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['room_type']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                                echo "<td>₱" . number_format($row['daily_rate'], 2) . " /day</td>";
                                echo "<td>₱" . number_format($row['amount'], 2) . "</td>";
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