<?php
include '../connect.php';
if (isset($_GET['guest_id'])) {
    $guest_id = intval($_GET['guest_id']);

    $stmt = $conn->prepare("SELECT id, description, amount, date, paid FROM additional_charge WHERE guest_id = ? ORDER BY date DESC");
    $stmt->bind_param("i", $guest_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $charges = [];
    while ($row = $result->fetch_assoc()) {
        $charges[] = [
            'id' => $row['id'],
            'description' => htmlspecialchars($row['description']),
            'amount' => $row['amount'],
            'date' => date('M d, Y', strtotime($row['date'])),
            'paid' => $row['paid']   // Return the integer value
        ];
    }

    echo json_encode($charges);
    exit;
}

echo json_encode([]);
