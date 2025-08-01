<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit;
}

include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['pay'])) {
        include 'connect.php'; // Make sure to connect to your DB

        $guest_id = $_POST['guest_id'];
        $bill_month = $_POST['bill_month'];
        $services = $_POST['services'];
        $paid = "paid";

        foreach ($services as $service) {
            $description = $service['description'];
            $amount = $service['amount'];

            // Optional: Skip empty rows
            if (empty($description) || $amount === '' || $amount < 0) continue;

            $stmt = $conn->prepare("INSERT INTO transactions (guest_id, bill_month, description, amount, is_paid) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issds", $guest_id, $bill_month, $description, $amount, $paid);
            $stmt->execute();
        }

        echo "<div class='alert alert-success mt-3'>Bill successfully recorded.</div>";
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

        <!-- Summary Boxes with Icons -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center shadow-sm p-3">
                    <div class="mb-2">
                        <i class="fas fa-file-invoice fa-2x text-primary"></i>
                    </div>
                    <h3 class="mb-1">18</h3>
                    <small class="text-muted">Total Bills</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow-sm p-3">
                    <div class="mb-2">
                        <i class="fas fa-coins fa-2x text-success"></i>
                    </div>
                    <h3 class="mb-1">₱28,560</h3>
                    <small class="text-muted">Total Revenue</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow-sm p-3">
                    <div class="mb-2">
                        <i class="fas fa-hourglass-half fa-2x text-warning"></i>
                    </div>
                    <h3 class="mb-1">5</h3>
                    <small class="text-muted">Pending Payments</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow-sm p-3">
                    <div class="mb-2">
                        <i class="fas fa-chart-line fa-2x text-info"></i>
                    </div>
                    <h3 class="mb-1">72%</h3>
                    <small class="text-muted">Collection Rate</small>
                </div>
            </div>
        </div>

        <!-- Billing Form -->
        <div class="card shadow-sm p-4 mb-4">
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Select Guest</label>
                    <select class="form-select" name="guest_id" required>
                        <option value="">-- Select Guest --</option>
                        <?php
                        $sql = "SELECT guest_id, first_name, last_name FROM guests";
                        $result = $conn->query($sql);

                        if ($result && $result->num_rows > 0):
                            while ($row = $result->fetch_assoc()):
                        ?>
                                <option value="<?php echo $row['guest_id']; ?>">
                                    <?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>
                                </option>
                            <?php endwhile;
                        else: ?>
                            <option value="">No guests found</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">For the Month of</label>
                    <input type="month" class="form-control" name="bill_month" required>
                </div>

                <div class="mb-4">
                    <h5>Additional Charges</h5>
                    <div class="service-item row mb-2">
                        <div class="col-md-6">
                            <input type="text" class="form-control"
                                name="services[0][description]"
                                placeholder="Description">
                        </div>
                        <div class="col-md-4">
                            <input type="number" class="form-control"
                                name="services[0][amount]"
                                placeholder="Amount" step="0.01" min="0">
                        </div>
                    </div>
                </div>

                <button type="submit" name="pay" class="btn btn-primary">Pay Bill</button>
            </form>

        </div>



        <div class="room-content ">
            <div class="card shadow-sm p-4">
                <table id="myTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Guest Name</th>
                            <th>Room #</th>
                            <th>Room Type</th>
                            <th>Billing Month</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include 'connect.php';

                        $sql = "SELECT 
                    g.guest_id,
                    CONCAT(g.first_name, ' ', g.last_name) AS full_name,
                    r.room_id,
                    rt.type AS room_type,
                    t.bill_month,
                    SUM(t.amount) AS total_due,
                    t.is_paid
                FROM guests g
                LEFT JOIN rooms r ON g.guest_id = r.guest_id
                LEFT JOIN room_types rt ON r.room_type_id = rt.room_type_id
                LEFT JOIN transactions t ON g.guest_id = t.guest_id
                GROUP BY g.guest_id, t.bill_month, t.is_paid
                ORDER BY g.guest_id, t.bill_month DESC";

                        $result = $conn->query($sql);

                        while ($row = $result->fetch_assoc()) {
                            $status = $row['is_paid'] ? 'paid' : 'unpaid';
                            $badge = $row['is_paid'] ? 'success' : 'danger';

                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['room_id'] ?? '—') . "</td>";
                            echo "<td>" . htmlspecialchars($row['room_type'] ?? '—') . "</td>";
                            echo "<td>" . htmlspecialchars($row['bill_month'] ?? '—') . "</td>";
                            echo "<td>₱" . number_format($row['total_due'] ?? 0, 2) . "</td>";
                            echo "<td><span class='badge bg-$badge'>$status</span></td>";
                            echo "<td>";
                            if (!$row['is_paid']) {
                                echo "<form method='POST' action='pay_bill.php'>
                        <input type='hidden' name='guest_id' value='{$row['guest_id']}'>
                        <input type='hidden' name='bill_month' value='{$row['bill_month']}'>
                        <button type='submit' class='btn btn-sm btn-primary'>Mark as Paid</button>
                      </form>";
                            } else {
                                echo "<button class='btn btn-sm btn-secondary' disabled>Paid</button>";
                            }
                            echo "</td>";
                            echo "</tr>";
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

</body>

</html>