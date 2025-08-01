<!DOCTYPE html>
<html lang="en">
<?php include 'head.php'; ?>
<link href="../node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet">

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
                    <h4 class="mb-0">Dashboard</h4>
                    <div class="ms-3 text-muted d-none d-md-block">
                        <i class="fas fa-calendar me-1"></i>
                        <span id="currentDate">June 6, 2023</span>
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

        <!-- Dashboard Content -->
        <div class="room-content">
            <div class="card shadow-sm p-4">
                <table id="myTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Room#</th>
                            <th>Guess Name</th>
                            <th>Status</th>


                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include 'connect.php'; // Your DB connection

                        $sql = "SELECT rooms.room_id, rooms.status, guest.firstname
                        FROM rooms 
                        LEFT JOIN guest ON rooms.guest_id = guest.guest_id";
                        $result = $conn->query($sql);

                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['room_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['firstname'] ?? 'No guest') . "</td>";
                            echo "<td><span class='status-badge " .
                                ($row['status'] === 'occupied' ? 'status-confirmed' : 'status-available') . "'>" .
                                htmlspecialchars($row['status']) .
                                "</span></td>";
                            echo "<td><button class='btn btn-sm btn-outline-primary btn-action' data-room-id='" .
                                $row['room_id'] . "'>Toggle</button></td>";
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