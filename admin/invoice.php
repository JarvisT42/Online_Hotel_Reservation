<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['admin_logged_in'])) {
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
            <h4>Transactions</h4>
        </div>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo $_SESSION['success_message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <!-- Print Button Section -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-sm p-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0">Monthly Report</h5>
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="button" class="btn btn-primary" id="printTransactions">
                                <i class="fas fa-print"></i> Print Monthly Report
                            </button>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="report_month" class="form-label">Select Month</label>
                                <input type="month" class="form-control" id="report_month" name="report_month"
                                    value="<?php echo date('Y-m'); ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="room-content">
            <div class="card shadow-sm p-4">
                <table id="myTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Transaction #</th>
                            <th>Guest Name</th>
                            <th>Billing Month</th>
                            <th>Room Price</th>
                            <th>Total Amount</th>
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
                            t.room_charge,
                            t.total_amount,
                            t.transaction_date
                        FROM transactions t
                        LEFT JOIN guests g ON g.guest_id = t.guest_id
                        LEFT JOIN rooms r ON r.room_id = t.room_id
                        LEFT JOIN room_types rt ON t.room_type_id = rt.room_type_id
                        ORDER BY t.transaction_date DESC";

                        $result = $conn->query($sql);

                        while ($row = $result->fetch_assoc()) {
                            $billMonth = DateTime::createFromFormat('Y-m', $row['bill_month']);

                            // Get additional charges for this transaction
                            $additional_sql = "SELECT 
                                ac.description, 
                                ac.amount,
                                ac.date
                            FROM additional_charge ac 
                            WHERE ac.transaction_id = ?";

                            $stmt = $conn->prepare($additional_sql);
                            $stmt->bind_param("i", $row['transaction_id']);
                            $stmt->execute();
                            $additional_result = $stmt->get_result();

                            $additional_charges = [];
                            $total_additional_amount = 0;
                            while ($additional = $additional_result->fetch_assoc()) {
                                $additional_charges[] = $additional;
                                $total_additional_amount += $additional['amount'];
                            }

                            echo "<tr>";
                            echo "<td class='text-start'>" . htmlspecialchars($row['transaction_id'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['full_name'] ?? '') . "</td>";
                            echo "<td>" . ($billMonth ? $billMonth->format('F Y') : htmlspecialchars($row['bill_month'] ?? '')) . "</td>";
                            echo "<td>₱" . number_format($row['room_price'] ?? 0, 2) . " /day</td>";
                            echo "<td>₱" . number_format($row['total_amount'] ?? 0, 2) . "</td>";
                            // FIXED: Use empty() check instead of null coalescing for htmlspecialchars
                            echo "<td>" . (!empty($row['transaction_date']) ? htmlspecialchars($row['transaction_date']) : '—') . "</td>";

                            echo '<td><button 
                                class="btn btn-success btn-sm view-transaction" 
                                data-bs-toggle="modal" 
                                data-bs-target="#addRoomModal"
                                data-transaction-id="' . htmlspecialchars($row['transaction_id'] ?? '') . '"
                                data-room-id="' . htmlspecialchars($row['room_id'] ?? '') . '"
                                data-room-type="' . htmlspecialchars($row['room_type'] ?? 'N/A') . '"
                                data-room-price="' . htmlspecialchars($row['room_price'] ?? '0.00') . '"
                                data-bill-month="' . htmlspecialchars($row['bill_month'] ?? '') . '"
                                data-description="' . htmlspecialchars($row['description'] ?? '') . '"
                                data-room-charge="' . htmlspecialchars($row['room_charge'] ?? '0.00') . '"
                                data-total-amount="' . htmlspecialchars($row['total_amount'] ?? '0.00') . '"
                                data-created="' . htmlspecialchars($row['transaction_date'] ?? '') . '"
                                data-additional-charges=\'' . json_encode($additional_charges) . '\'
                            >View</button></td>';
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Transaction Details Modal -->
        <div class="modal fade" id="addRoomModal" tabindex="-1" aria-labelledby="addRoomModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addRoomModalLabel">Transaction Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modal-body-content">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Transaction ID:</strong> <span id="modal-transaction-id"></span></p>
                                <p><strong>Room ID:</strong> <span id="modal-room-id"></span></p>
                                <p><strong>Room Type:</strong> <span id="modal-room-type"></span></p>
                                <p><strong>Room Price:</strong> ₱<span id="modal-room-price">0.00</span>/day</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Billing Month:</strong> <span id="modal-bill-month"></span></p>
                                <p><strong>Created At:</strong> <span id="modal-created-at"></span></p>
                                <p><strong>Room Charge:</strong> ₱<span id="modal-room-charge">0.00</span></p>
                            </div>
                        </div>

                        <hr>

                        <!-- Additional Charges from additional_charge table -->
                        <div class="mb-3">
                            <h6>Incident/Additional Charges</h6>
                            <div id="modal-additional-charges">
                                <!-- Additional charges will be populated here -->
                            </div>
                        </div>

                        <hr>

                        <!-- Total Amount -->
                        <div class="mb-3">
                            <h5><strong>Total Amount:</strong> ₱<span id="modal-total-amount">0.00</span></h5>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
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
                const roomCharge = parseFloat($(this).data('room-charge')) || 0;
                const total = parseFloat($(this).data('total-amount')) || 0;
                const additionalCharges = $(this).data('additional-charges') || [];

                // Set basic information
                $('#modal-transaction-id').text($(this).data('transaction-id'));
                $('#modal-room-id').text($(this).data('room-id'));
                $('#modal-room-type').text($(this).data('room-type'));
                $('#modal-room-price').text(roomPrice.toFixed(2));
                $('#modal-bill-month').text($(this).data('bill-month'));
                $('#modal-room-charge').text(roomCharge.toFixed(2));
                $('#modal-total-amount').text(total.toFixed(2));
                $('#modal-created-at').text($(this).data('created'));

                // Populate additional charges from additional_charge table
                const additionalContainer = $('#modal-additional-charges');
                additionalContainer.empty();

                if (additionalCharges.length > 0) {
                    let additionalHtml = '<div class="table-responsive"><table class="table table-sm table-bordered">';
                    additionalHtml += '<thead><tr><th>Description</th><th>Date</th><th>Amount</th></tr></thead><tbody>';

                    let additionalTotal = 0;
                    additionalCharges.forEach(charge => {
                        additionalHtml += `<tr>
                            <td>${charge.description}</td>
                            <td>${charge.date}</td>
                            <td>₱${parseFloat(charge.amount).toFixed(2)}</td>
                        </tr>`;
                        additionalTotal += parseFloat(charge.amount);
                    });

                    additionalHtml += `</tbody></table>`;
                    additionalHtml += `<p><strong>Total Additional Charges: ₱${additionalTotal.toFixed(2)}</strong></p>`;
                    additionalHtml += '</div>';

                    additionalContainer.html(additionalHtml);
                } else {
                    additionalContainer.html('<p class="text-muted">No additional charges</p>');
                }
            });

            // Print functionality
            $('#printTransactions').on('click', function() {
                const reportMonth = $('#report_month').val();

                // Create a new window for printing
                const printWindow = window.open('', '_blank', 'width=1000,height=600');

                // Get the selected month for display
                const monthDisplay = reportMonth ? new Date(reportMonth + '-01').toLocaleDateString('en-US', {
                    month: 'long',
                    year: 'numeric'
                }) : 'All Months';

                // Generate print content
                printWindow.document.write(`
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Monthly Transactions Report - ${monthDisplay}</title>
                        <style>
                            body { 
                                font-family: Arial, sans-serif; 
                                margin: 20px; 
                                color: #333;
                            }
                            .report-header { 
                                text-align: center; 
                                margin-bottom: 30px; 
                                border-bottom: 2px solid #333; 
                                padding-bottom: 15px;
                            }
                            .report-title { 
                                font-size: 24px; 
                                font-weight: bold; 
                                margin-bottom: 5px;
                            }
                            .report-period { 
                                font-size: 18px; 
                                margin-bottom: 10px;
                            }
                            .summary-section {
                                margin-bottom: 20px;
                                padding: 15px;
                                background-color: #f8f9fa;
                                border-radius: 5px;
                            }
                            table { 
                                width: 100%; 
                                border-collapse: collapse; 
                                margin-bottom: 20px;
                            }
                            th, td { 
                                border: 1px solid #ddd; 
                                padding: 8px; 
                                text-align: left;
                            }
                            th { 
                                background-color: #f2f2f2; 
                                font-weight: bold;
                            }
                            .total-row { 
                                font-weight: bold; 
                                background-color: #e9ecef;
                            }
                            .no-print { display: none; }
                            @media print {
                                body { margin: 0; padding: 20px; }
                                .no-print { display: none; }
                            }
                        </style>
                    </head>
                    <body>
                        <div class="report-header">
                            <div class="report-title">Monthly Transactions Report</div>
                            <div class="report-period">${monthDisplay}</div>
                            <div class="report-date">Generated on: ${new Date().toLocaleDateString()}</div>
                        </div>
                        
                        <div class="no-print" style="margin-bottom: 20px; text-align: center;">
                            <button onclick="window.print()" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">Print Report</button>
                            <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">Close</button>
                        </div>
                        
                        <div id="report-content">
                            <!-- Report content will be populated by JavaScript -->
                        </div>
                        
                        <script>
                            // Function to generate and display report content
                            function generateReport() {
                                const reportMonth = '${reportMonth}';
                                const reportContent = document.getElementById('report-content');
                                
                                // Filter transactions based on selected month
                                const table = window.opener.document.getElementById('myTable');
                                const rows = table.querySelectorAll('tbody tr');
                                let filteredRows = Array.from(rows);
                                
                                if (reportMonth) {
                                    filteredRows = filteredRows.filter(row => {
                                        const billMonthCell = row.cells[2].textContent;
                                        const selectedMonth = new Date(reportMonth + '-01').toLocaleDateString('en-US', { 
                                            month: 'long', 
                                            year: 'numeric' 
                                        });
                                        return billMonthCell.includes(selectedMonth);
                                    });
                                }
                                
                                // Calculate total of Total Amount column
                                let totalAmountSum = 0;
                                
                                filteredRows.forEach(row => {
                                    const totalAmountText = row.cells[4].textContent.replace('₱', '').replace(',', '').trim();
                                    totalAmountSum += parseFloat(totalAmountText) || 0;
                                });
                                
                                // Generate summary section
                                let html = \`<div class="summary-section">
                                    <h3>Summary</h3>
                                    <p><strong>Total Transactions:</strong> \${filteredRows.length}</p>
                                    <p><strong>Total Amount:</strong> ₱\${totalAmountSum.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</p>
                                </div>\`;
                                
                                // Generate detailed table
                                if (filteredRows.length > 0) {
                                    html += \`<h3>Transaction Details</h3>
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Transaction #</th>
                                                <th>Guest Name</th>
                                                <th>Billing Month</th>
                                                <th>Room Price</th>
                                                <th>Total Amount</th>
                                                <th>Created At</th>
                                            </tr>
                                        </thead>
                                        <tbody>\`;
                                    
                                    filteredRows.forEach(row => {
                                        html += \`<tr>
                                            <td>\${row.cells[0].textContent}</td>
                                            <td>\${row.cells[1].textContent}</td>
                                            <td>\${row.cells[2].textContent}</td>
                                            <td>\${row.cells[3].textContent}</td>
                                            <td>\${row.cells[4].textContent}</td>
                                            <td>\${row.cells[5].textContent}</td>
                                        </tr>\`;
                                    });
                                    
                                    html += \`</tbody></table>\`;
                                } else {
                                    html += '<p>No transactions found for the selected month.</p>';
                                }
                                
                                reportContent.innerHTML = html;
                            }
                            
                            // Generate report when window loads
                            window.onload = generateReport;
                        <\/script>
                    </body>
                    </html>
                `);

                printWindow.document.close();
            });

            $('.alert').delay(5000).fadeOut(400);
        });
    </script>
</body>

</html>