<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    // Not logged in, redirect to login page
    header("Location: ../login.php");
    exit;
}
?>

<?php
include '../connect.php';




if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unarchive'])) {
    $room_id = $_POST['room_id'];

    $updateSql = "UPDATE rooms SET archive = 'no' WHERE room_id = ?";
    $stmt = $conn->prepare($updateSql);

    if ($stmt) {
        $stmt->bind_param("i", $room_id);
        if ($stmt->execute()) {
            // Redirect back with success
            header("Location: archive.php?msg=Room archived successfully");
            exit();
        } else {
            echo "Error executing query: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}



?>
<!DOCTYPE html>
<html lang="en">
<?php include 'head.php'; ?>


<style>
    .status-badge {
        padding: 4px 8px;
        border-radius: 8px;
        font-size: 12px;
        color: white;
    }

    .status-confirmed {
        background-color: #dc3545;
        /* Red */
    }

    .status-available {
        background-color: #28a745;
        /* Green */
    }
</style>


<body>
    <!-- Toggle Button for Mobile -->
    <button class="toggle-btn" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Overlay for mobile sidebar -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>


    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <div class="topbar-left">
                <div class="d-flex align-items-center">
                    <h4 class="mb-0">Rooms</h4>
                    <div class="ms-3 text-muted d-none d-md-block">
                        <i class="fas fa-bed me-1"></i>
                        <span id="currentDate">Available 9</span>
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

        <!-- Add Room Modal -->







        <!-- Dashboard Content -->
        <div class="room-content">
            <div class="card shadow-sm p-4">
                <table id="myTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Room#</th>
                            <th>Room Type</th>




                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include '../connect.php'; // DB connection

                        // Only get archived rooms
                        $sql = "SELECT rooms.room_id, room_types.type
        FROM rooms
        LEFT JOIN room_types ON rooms.room_type_id = room_types.room_type_id
        WHERE rooms.archive = 'yes'";

                        $result = $conn->query($sql);

                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>Room " . htmlspecialchars($row['room_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['type'] ?? 'N/A') . "</td>";

                            // Unarchive button
                            echo "<td>
            <form action='' method='POST' style='display:inline-block;' 
                  onsubmit=\"return confirm('Are you sure you want to Unarchive this room?');\">
                <input type='hidden' name='room_id' value='" . htmlspecialchars($row['room_id']) . "'>
                <button type='submit' name='unarchive' class='btn btn-sm btn-outline-primary btn-action'>
                    Unarchive
                </button>
            </form>
          </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>




                    save button
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
        $(document).on('click', '.btn-action', function() {
            const roomId = $(this).data('room-id');

            // Send AJAX request to update room status
            $.post('update_status.php', {
                room_id: roomId
            }, function(response) {
                location.reload(); // Reload to reflect updated data
            });
        });
    </script>




</body>

</html>