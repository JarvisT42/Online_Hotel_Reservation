<?php
include 'connect.php';

if (isset($_POST['room_id'])) {
    $room_id = trim($_POST['room_id']);

    $stmt = $conn->prepare("SELECT room_id FROM rooms WHERE room_id = ?");
    $stmt->bind_param("s", $room_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "exists";
    } else {
        echo "available";
    }

    $stmt->close();
    $conn->close();
}
?>
