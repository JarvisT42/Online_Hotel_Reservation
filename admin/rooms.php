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

if ($_SERVER["REQUEST_METHOD"] === "POST") {


    $room_number = trim($_POST['room_number']);
    $room_type_id = trim($_POST['room_type_id']); // Get the selected room type
    $status = trim($_POST['status']);

    // Check if room already exists
    $check = $conn->prepare("SELECT room_id FROM rooms WHERE room_id = ?");
    $check->bind_param("s", $room_number);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?exist=1");
    } else {
        // Insert into rooms table
        $stmt = $conn->prepare("INSERT INTO rooms (room_id, room_type_id, status) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $room_number, $room_type_id, $status);

        if ($stmt->execute()) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "<div style='color:red;'>Database error: " . htmlspecialchars($stmt->error) . "</div>";
        }

        $stmt->close();
    }

    $check->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['room_id'])) {
    $room_id = $_POST['room_id'];

    // 1. Check current status
    $checkSql = "SELECT status FROM rooms WHERE room_id = ?";
    $stmt = $conn->prepare($checkSql);

    if ($stmt) {
        $stmt->bind_param("s", $room_id);
        $stmt->execute();
        $stmt->bind_result($currentStatus);
        $stmt->fetch();
        $stmt->close();

        // 2. Determine the new status
        $newStatus = ($currentStatus === 'occupied') ? NULL : 'occupied';

        // 3. Update the status
        $updateSql = "UPDATE rooms SET status = ? WHERE room_id = ?";
        $stmt = $conn->prepare($updateSql);

        if ($stmt) {
            $stmt->bind_param("ss", $newStatus, $room_id);

            if ($stmt->execute()) {
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "Error executing update: " . $stmt->error;
            }
        } else {
            echo "Error preparing update statement: " . $conn->error;
        }
    } else {
        echo "Error preparing select statement: " . $conn->error;
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
                <div class="notification">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </div>
                <img src="https://randomuser.me/api/portraits/men/41.jpg" alt="Admin">
                <div>
                    <div class="fw-bold">John Doe</div>
                    <div class="text-muted small">Administrator</div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Room List</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoomModal">
                <i class="fas fa-plus me-1"></i> Add Room
            </button>
        </div>
        <!-- Add Room Modal -->
        <?php if (isset($_GET['exist']) && $_GET['exist'] == 1): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Room number already exists. <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>


        <!-- Modal -->
        <div class="modal fade" id="addRoomModal" tabindex="-1" aria-labelledby="addRoomModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="" method="POST" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addRoomModalLabel">Add New Room</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php if (!empty($error)) : ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <div class="mb-3">
                            <label for="room_type" class="form-label">Room #</label>
                            <input type="number" name="room_number" id="room_number" class="form-control" required>
                            <div id="room-id-feedback" class="form-text text-danger"></div>
                        </div>
                        <div class="mb-3">
                            <label for="room_type_id" class="form-label">Room Type</label>
                            <select name="room_type_id" id="room_type_id" class="form-select" required>
                                <option value="" disabled selected>Select a room type</option>
                                <?php

                                $sql = "SELECT room_type_id, type FROM room_types";
                                $result = $conn->query($sql);

                                if ($result && $result->num_rows > 0):
                                    while ($row = $result->fetch_assoc()):
                                ?>
                                        <option value="<?php echo $row['room_type_id']; ?>">
                                            <?php echo htmlspecialchars($row['type']); ?>
                                        </option>
                                    <?php
                                    endwhile;
                                else:
                                    ?>
                                    <option disabled>No room types available</option>
                                <?php endif; ?>
                            </select>
                        </div>




                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="available">Available</option>
                                <option value="occupied">Occupied</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Room</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Keep Modal Open if Error -->

        <script>
            let roomExists = false;

            document.getElementById('room_id').addEventListener('input', function() {
                const roomId = this.value;
                const feedback = document.getElementById('room-id-feedback');

                if (roomId !== "") {
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'check_room.php', true);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            if (xhr.responseText === 'exists') {
                                feedback.textContent = "Room number already exists.";
                                roomExists = true;
                            } else {
                                feedback.textContent = "";
                                roomExists = false;
                            }
                        }
                    };
                    xhr.send('room_id=' + encodeURIComponent(roomId));
                } else {
                    feedback.textContent = "";
                }
            });

            document.querySelector('#addRoomModal form').addEventListener('submit', function(e) {
                if (roomExists) {
                    e.preventDefault();
                    alert('Please choose a unique room number.');
                }
            });
        </script>



        <!-- Dashboard Content -->
        <div class="room-content">
            <div class="card shadow-sm p-4">
                <table id="myTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Room#</th>
                            <th>Guess Name</th>
                            <th>Room Type</th>

                            <th>Status</th>


                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include 'connect.php'; // Your DB connection

                        $sql = "SELECT rooms.room_id, rooms.status, guests.first_name, guests.last_name, room_types.type
                FROM rooms
                LEFT JOIN guests ON rooms.guest_id = guests.guest_id
                LEFT JOIN room_types ON rooms.room_type_id = room_types.room_type_id";

                        $result = $conn->query($sql);

                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>Room " . htmlspecialchars($row['room_id']) . "</td>";

                            $firstName = $row['first_name'] ?? '';
                            $lastName = $row['last_name'] ?? '';
                            $fullName = trim($firstName . ' ' . $lastName);

                            echo "<td>" . htmlspecialchars($fullName ?: 'No guest') . "</td>";

                            echo "<td>" . htmlspecialchars($row['type'] ?? 'N/A') . "</td>";

                            $status = $row['status'] === 'occupied' ? 'Occupied' : 'Available';
                            $badgeClass = $row['status'] === 'occupied' ? 'status-confirmed' : 'status-available';

                            // Button label and color
                            if ($row['status'] === 'occupied') {
                                $buttonLabel = 'Mark as Available';
                                $buttonClass = 'btn-outline-danger'; // Red
                            } else {
                                $buttonLabel = 'Mark as Occupied';
                                $buttonClass = 'btn-outline-success'; // Green
                            }

                            echo "<td><span class='status-badge $badgeClass'>" . htmlspecialchars($status) . "</span></td>";

                            echo "<td>
        <form action='' method='POST' onsubmit=\"return confirm('Are you sure you want to $buttonLabel?');\">
            <input type='hidden' name='room_id' value='" . htmlspecialchars($row['room_id']) . "'>
            <button type='submit' class='btn btn-sm $buttonClass btn-action'>$buttonLabel</button>
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


    <!-- Bootstrap JS -->

</body>

</html>