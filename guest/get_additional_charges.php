<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['guest_logged_in'])) {
    exit('Unauthorized access');
}

include '../connect.php';

$transactionId = $_GET['transaction_id'] ?? null;

if (!$transactionId) {
    echo '<div class="alert alert-danger">Invalid transaction ID</div>';
    exit;
}

// Get transaction basic info
$transaction_sql = "SELECT t.bill_month, t.total_amount, r.room_id
                   FROM transactions t
                   LEFT JOIN rooms r ON t.room_id = r.room_id
                   WHERE t.transaction_id = ?";
$stmt = $conn->prepare($transaction_sql);
$stmt->bind_param("i", $transactionId);
$stmt->execute();
$transaction_result = $stmt->get_result();
$transaction = $transaction_result->fetch_assoc();
$stmt->close();

// Get additional charges
$charges_sql = "SELECT description, amount, date, paid 
                FROM additional_charge 
                WHERE transaction_id = ? 
                ORDER BY date DESC";
$stmt2 = $conn->prepare($charges_sql);
$stmt2->bind_param("i", $transactionId);
$stmt2->execute();
$charges_result = $stmt2->get_result();

if ($charges_result->num_rows > 0) {
    echo '<h6>Transaction: ' . htmlspecialchars($transaction['bill_month']) . ' - Room ' . htmlspecialchars($transaction['room_id']) . '</h6>';
    echo '<p>Total Amount: <strong>₱' . number_format($transaction['total_amount'], 2) . '</strong></p>';
    echo '<hr>';

    echo '<div class="table-responsive">';
    echo '<table class="table table-striped">';
    echo '<thead class="table-dark">';
    echo '<tr>';
    echo '<th>Date</th>';
    echo '<th>Description</th>';
    echo '<th>Amount</th>';
    echo '<th>Status</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    $total = 0;
    while ($charge = $charges_result->fetch_assoc()) {
        $status = $charge['paid']
            ? '<span class="badge bg-success">Paid</span>'
            : '<span class="badge bg-danger">Unpaid</span>';

        echo '<tr>';
        echo '<td>' . date("M d, Y", strtotime($charge['date'])) . '</td>';
        echo '<td>' . htmlspecialchars($charge['description']) . '</td>';
        echo '<td>₱' . number_format($charge['amount'], 2) . '</td>';
        echo '<td>' . $status . '</td>';
        echo '</tr>';

        $total += $charge['amount'];
    }

    echo '<tr class="table-warning">';
    echo '<td colspan="2"><strong>Total Additional Charges</strong></td>';
    echo '<td><strong>₱' . number_format($total, 2) . '</strong></td>';
    echo '<td></td>';
    echo '</tr>';

    echo '</tbody>';
    echo '</table>';
    echo '</div>';
} else {
    echo '<div class="alert alert-info">No additional charges found for this transaction.</div>';
}

$stmt2->close();
