<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    // Not logged in, redirect to login page
    header("Location: ../login.php");
    exit;
}
?>

<?php
include 'connect.php';
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_room_price'])) {
    include 'connect.php'; // DB connection

    $room_type = trim($_POST['room_type']);
    $room_price = floatval($_POST['room_price']);

    if ($room_type === '' || $room_price <= 0) {
        $_SESSION['error_message'] = "Invalid room type or price.";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Insert new room type and price
    $insertStmt = $conn->prepare("INSERT INTO room_types (type, price) VALUES (?, ?)");
    $insertStmt->bind_param("sd", $room_type, $room_price);

    if ($insertStmt->execute()) {
        $_SESSION['success_message'] = "New room type added successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to add room type: " . $conn->error;
    }

    $insertStmt->close();
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_room_type'])) {
    $room_type = trim($_POST['room_type']);

    $room_price = trim($_POST['room_price']);

    // Insert into room_types
    $stmt = $conn->prepare("INSERT INTO room_types (type, price) VALUES ( ?, ?)");
    $stmt->bind_param("sd", $room_type, $room_price);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Room type added successfully.";
        header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
        exit;
    } else {
        $error = "Failed to add room type: " . $conn->error;
    }

    $stmt->close();
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
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Room List</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoomModal">
                <i class="fas fa-plus me-1"></i> Add Room Type
            </button>
        </div>
        <!-- Add Room Modal -->



        <!-- Modal -->
        <div class="modal fade" id="addRoomModal" tabindex="-1" aria-labelledby="addRoomModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="" method="POST" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addRoomModalLabel">Add New Room Type</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php if (!empty($error)) : ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <div class="mb-3">
                            <label for="room_type" class="form-label">Room Type Name</label>
                            <input type="text" name="room_type" id="room_type" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="room_price" class="form-label">Room Price</label>
                            <input type="text" name="room_price" id="room_price" class="form-control" required>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="add_room_type" class="btn btn-primary">Add Room</button>
                    </div>
                </form>

            </div>
        </div>

        <!-- Keep Modal Open if Error -->

        <script>

        </script>



        <!-- Dashboard Content -->
        <div class="room-content">
            <div class="card shadow-sm p-4">
                <table id="myTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Room Type</th>
                            <th>Price</th>
                            <th>Date Created</th>


                        </tr>
                    </thead>
                    <tbody>
                        <?php


                        // Query only from room_types
                        $sql = "SELECT room_type_id, type, price, created_at FROM room_types";
                        $result = $conn->query($sql);

                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";

                            echo "<td>" . htmlspecialchars($row['type']) . "</td>";
                            echo "<td>₱" . number_format($row['price'], 2) . "  
    <a href='#' 
       data-bs-toggle='modal' 
       data-bs-target='#editRoomTypeModal' 
       data-id='" . htmlspecialchars($row['room_type_id'], ENT_QUOTES) . "' 
       data-type='" . htmlspecialchars($row['type'], ENT_QUOTES) . "' 
       data-price='" . htmlspecialchars($row['price'], ENT_QUOTES) . "' 
       class='btn btn-sm btn-warning ms-2 edit-room-btn'>
       Edit
    </a>
</td>";


                            echo "<td>" . htmlspecialchars(date('F j, Y h:i A', strtotime($row['created_at']))) . "</td>";


                            //        echo "<td>
                            //     <a data-bs-toggle="modal" data-bs-target="#editRoomTypeModal"?id=" . $row['room_type_id'] . "' class='btn btn-sm btn-warning'>Edit</a>
                            //     <a href='delete_room_type.php?id=" . $row['room_type_id'] . "' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure you want to delete this room type?');\">Delete</a>
                            // </td>";
                            echo "</tr>";
                        }
                        ?>

                    </tbody>






                </table>
            </div>
        </div>


        <div class="modal fade" id="editRoomTypeModal" tabindex="-1" aria-labelledby="editRoomTypeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="" method="POST" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Room Type</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="room_type_id" id="editRoomTypeId">

                        <div class="mb-3">
                            <label for="editRoomType" class="form-label">Room Type</label>
                            <input type="text" name="room_type" id="editRoomType" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="editRoomPrice" class="form-label">Room Price</label>
                            <input type="number" name="room_price" id="editRoomPrice" class="form-control" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" name="update_room_price" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>

            </div>
        </div>


        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const editButtons = document.querySelectorAll("[data-bs-toggle='modal'][data-bs-target='#editRoomTypeModal']");

                editButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const roomTypeId = this.getAttribute('data-id');
                        document.getElementById('editRoomTypeId').value = roomTypeId;

                        // OPTIONAL: You can also set the room type name if you're passing it too
                        // document.getElementById('editRoomType').value = this.getAttribute('data-room-name');
                    });
                });
            });
        </script>


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
        document.addEventListener("DOMContentLoaded", function() {
            const editButtons = document.querySelectorAll(".edit-room-btn");

            editButtons.forEach(button => {
                button.addEventListener("click", function() {
                    const roomTypeId = this.getAttribute("data-id");
                    const roomType = this.getAttribute("data-type");
                    const roomPrice = this.getAttribute("data-price");

                    // Fill in the modal fields
                    document.getElementById("editRoomTypeId").value = roomTypeId;
                    document.getElementById("editRoomType").value = roomType;
                    document.getElementById("editRoomPrice").value = roomPrice;
                });
            });
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


    <!-- Bootstrap JS -->

</body>

</html>