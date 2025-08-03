<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit;
}

include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['pay'])) {
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
            <h4>Transactions</h4>
        </div>


        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo $_SESSION['success_message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>


        <!-- Summary Boxes with Icons -->

        <!-- Billing Form -->



        <div class="room-content">
            <div class="card shadow-sm p-4">
                <?php
                include 'connect.php';
                ?>

                <table id="myTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Transaction #</th>
                            <th>Guest Name</th>

                            <th>Billing Month</th>
                            <th>Description</th>
                            <th>Amount</th>

                            <th>Created At</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT 
    t.transaction_id,
    CONCAT(g.first_name, ' ', g.last_name) AS full_name,
    r.room_id,
    rt.type AS room_type,
    rt.price AS room_price,
    t.bill_month,
    t.description,
    t.amount,
    t.created_at
FROM transactions t
LEFT JOIN guests g ON g.guest_id = t.guest_id
LEFT JOIN rooms r ON r.room_id = t.room_id
LEFT JOIN room_types rt ON t.room_type_id = rt.room_type_id
ORDER BY t.created_at DESC";


                        $result = $conn->query($sql);

                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['transaction_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['bill_month'] ?? '—') . "</td>";
                            echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                            echo "<td>₱" . number_format($row['amount'], 2) . "</td>";
                            echo "<td>" . htmlspecialchars($row['created_at'] ?? '—') . "</td>";

                            echo '<td><button 
        class="btn btn-success btn-sm view-transaction" 
        data-bs-toggle="modal" 
        data-bs-target="#addRoomModal"
        data-transaction-id="' . htmlspecialchars($row['transaction_id']) . '"
        data-room-id="' . htmlspecialchars($row['room_id']) . '"
        data-room-type="' . htmlspecialchars($row['room_type'] ?? 'N/A') . '"
data-room-price="' . htmlspecialchars($row['room_price'] ?? '0.00') . '"
        data-bill-month="' . htmlspecialchars($row['bill_month']) . '"
        data-description="' . htmlspecialchars($row['description']) . '"
        data-amount="' . htmlspecialchars($row['amount']) . '"
        data-created="' . htmlspecialchars($row['created_at']) . '"
    >View</button></td>';
                            echo "</tr>";
                        }

                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Reusable Modal -->
        <!-- Reusable Modal -->
        <div class="modal fade" id="addRoomModal" tabindex="-1" aria-labelledby="addRoomModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="" method="POST" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addRoomModalLabel">Transaction Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modal-body-content">
                        <p><strong>Transaction ID:</strong> <span id="modal-transaction-id"></span></p>
                        <p><strong>Room ID:</strong> <span id="modal-room-id"></span></p>
                        <p><strong>Room Type:</strong> <span id="modal-room-type"></span></p>
                        <p><strong>Room Price:</strong> ₱<span id="modal-room-price">0.00</span></p>
                        <p><strong>Billing Month:</strong> <span id="modal-bill-month"></span></p>
                        <p><strong>Additional Charges Description:</strong> <span id="modal-description"></span></p>
                        <p><strong>Additional Charges Amount:</strong> ₱<span id="modal-amount">0.00</span></p>
                        <p><strong>Total Amount (Room + Charges):</strong> ₱<span id="modal-total-amount">0.00</span></p>

                        <p><strong>Created At:</strong> <span id="modal-created-at"></span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
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

            $('.view-transaction').on('click', function() {
                const roomPrice = parseFloat($(this).data('room-price')) || 0;
                const amount = parseFloat($(this).data('amount')) || 0;
                const total = roomPrice + amount;

                $('#modal-transaction-id').text($(this).data('transaction-id'));
                $('#modal-room-id').text($(this).data('room-id'));
                $('#modal-room-type').text($(this).data('room-type'));
                $('#modal-room-price').text(roomPrice.toFixed(2));
                $('#modal-bill-month').text($(this).data('bill-month'));
                $('#modal-description').text($(this).data('description'));
                $('#modal-amount').text(amount.toFixed(2));
                $('#modal-total-amount').text(total.toFixed(2));
                $('#modal-created-at').text($(this).data('created'));
            });


            $('.alert').delay(5000).fadeOut(400);
        });
    </script>
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