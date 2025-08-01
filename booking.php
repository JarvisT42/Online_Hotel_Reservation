<?php
include 'connect.php';

// Process form submission


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $checkin = $conn->real_escape_string($_POST['checkin']);
    $guests = (int)$_POST['guests'];
    $checkin_time = $conn->real_escape_string($_POST['checkin_time']); // New field
    $status = 'pending';
    // Update SQL query to include checkin_time
    $stmt = $conn->prepare("INSERT INTO bookings (first_name, last_name, email, phone, booking_date,  no_of_guest, booking_time, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssiss", $first_name, $last_name, $email, $phone, $checkin,  $guests, $checkin_time, $status);

    if ($stmt->execute()) {
        $booking_id = $conn->insert_id;
        header("Location: booking.php?book_success=1&booking_id=$booking_id");
        exit();
    } else {
        $error = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch booking details if success
$booking = [];
$is_success = false;
if (isset($_GET['book_success']) && $_GET['book_success'] == 1 && isset($_GET['booking_id'])) {
    $is_success = true;
    $booking_id = (int)$_GET['booking_id'];
    $result = $conn->query("SELECT * FROM bookings WHERE booking_id = $booking_id");
    if ($result && $result->num_rows > 0) {
        $booking = $result->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHIOJI APARTELLE - Book Your Stay</title>
    <!-- Bootstrap CSS -->
    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/fontawesome/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
	<link rel="stylesheet" href="css/style.css">
</head>

<body>
    <!-- Navigation -->
    <?php include 'navbar.php'; ?>

    <!-- Booking Container -->
    <div class="container booking-container">
        <div class="booking-header">
            <h2>Book Your Stay</h2>
            <p>Select your dates, provide your details, and secure your reservation at SHIOJI APARTELLE</p>
        </div>

        <!-- Step Indicator -->
        <div class="step-indicator">
            <div class="step <?= $is_success ? 'completed' : 'active' ?>">
                <div class="step-number">1</div>
                <div class="step-label">Select Dates</div>
            </div>
            <div class="step <?= $is_success ? 'completed' : '' ?>">
                <div class="step-number">2</div>
                <div class="step-label">Guest Information</div>
            </div>
            <div class="step <?= $is_success ? 'completed' : '' ?>">
                <div class="step-number">3</div>
                <div class="step-label">Confirmation</div>
            </div>
            <div class="step <?= $is_success ? 'active' : '' ?>">
                <div class="step-number">4</div>
                <div class="step-label">Success</div>
            </div>
        </div>

        <?php if (!$is_success): ?>
            <!-- Step 1: Date Selection -->
            <div id="step1" class="booking-step">
                <div class="booking-section">
                    <h3 class="section-title">Select Your Dates</h3>
                    <div class="calendar-container">
                        <div id="calendar"></div>
                        <div id="no-rooms-message" class="no-rooms-message">
                            <i class="fas fa-ban me-2"></i> No rooms available
                        </div>
                    </div>
                    <div id="selectedDates" class="selected-dates" style="display:none;">
                        <div class="date-info">
                            <span class="date-label">Check-In:</span>
                            <span id="checkInDate" class="date-value"></span>
                        </div>

                        <!-- ADDED TIME SELECTION -->

                        <div class="time-info">

                            <span class="time-label">Check-in Time:</span>


                            <div class="time-options">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="checkinTime" id="morningTime" value="morning" checked>
                                    <label class="form-check-label" for="morningTime">
                                        Morning (8AM-12PM)
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="checkinTime" id="afternoonTime" value="afternoon">
                                    <label class="form-check-label" for="afternoonTime">
                                        Afternoon (1PM-5PM)
                                    </label>
                                </div>
                            </div>

                        </div>


                        <button id="nextStep1" class="btn btn-primary mt-3">Continue to Guest Information</button>
                    </div>
                </div>
            </div>

            <!-- Step 2: Guest Information -->
            <div id="step2" class="booking-step completed-step">
                <div class="booking-section">
                    <h3 class="section-title">Guest Information</h3>
                    <form id="bookingForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="firstName" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lastName" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" required>
                            </div>




                            <div class="col-md-6 mb-3">
                                <label for="guests" class="form-label">Number of Guests</label>
                                <select class="form-select" id="guests" required>
                                    <option value="1" selected>1 Guest</option>
                                    <option value="2">2 Guests</option>
                                </select>
                            </div>

                         

                        </div>

                        <div class="booking-summary">
                            <h5 class="mb-3">Booking Summary</h5>
                          
                            <div class="summary-item">
                                <span>Check-In:</span>
                                <span id="summaryCheckIn"></span>
                            </div>
                            <div class="summary-item">
                                <span>Check-in Time:</span>
                                <span id="summaryCheckInTime">Morning (8AM-12PM)</span>
                            </div>
                            <div class="summary-item">
                                <span>Guests:</span>
                                <span id="summaryGuests">1</span>
                            </div>
                        </div>

                        <div class="button-group">
                            <button type="button" id="backToStep1" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Calendar
                            </button>
                            <button type="submit" class="btn btn-primary">Next: Review Booking</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Step 3: Confirmation -->
            <div id="step3" class="booking-step completed-step">
                <div class="booking-section">
                    <h3 class="section-title">Confirm Your Booking</h3>
                    <form id="bookingConfirmationForm" method="POST" action="">
                        <div class="confirmation">
                            <p>Please review your booking details before confirming your reservation.</p>

                            <div class="booking-details">
                                <div class="detail-item">
                                    <span class="detail-label">Name:</span>
                                    <span class="detail-value" id="confirmationName"></span>
                                    <input type="hidden" id="hiddenFirstName" name="first_name" />
                                    <input type="hidden" id="hiddenLastName" name="last_name" />


                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Email:</span>
                                    <span class="detail-value" id="confirmationEmail"></span>
                                    <input type="hidden" name="email" id="hiddenEmail" />
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Phone:</span>
                                    <span class="detail-value" id="confirmationPhone"></span>
                                    <input type="hidden" name="phone" id="hiddenPhone" />
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Check-In:</span>
                                    <span class="detail-value" id="confirmationDates"></span>
                                    <input type="hidden" name="checkin" id="hiddenDates" />
                                </div>
                                <!-- ADDED TIME CONFIRMATION -->
                                <div class="detail-item">
                                    <span class="detail-label">Check-in Time:</span>
                                    <span class="detail-value" id="confirmationTime"></span>
                                    <input type="hidden" name="checkin_time" id="hiddenTime" />
                                </div>
                            
                                <div class="detail-item">
                                    <span class="detail-label">Guests:</span>
                                    <span class="detail-value" id="confirmationGuests"></span>
                                    <input type="hidden" name="guests" id="hiddenGuests" />
                                </div>
                            </div>

                            <div class="button-group">
                                <button type="button" id="editBookingBtn" class="btn btn-outline-primary">Edit Details</button>
                                <button type="button" id="confirmBookingBtn" class="btn btn-primary">Confirm Booking</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <!-- Step 4: Success -->
        <?php if ($is_success && !empty($booking)): ?>
            <div id="step4" class="booking-step">
                <div class="confirmation">
                    <div class="confirmation-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h2>Booking Confirmed!</h2>
                    <p>Thank you for choosing SHIOJI APARTELLE. Your reservation has been successfully booked. We've sent a confirmation to your email.</p>

                    <div class="booking-details">
                        <div class="detail-item">
                            <span class="detail-label">Booking ID:</span>
                            <span class="detail-value">SHIOJI-<?= $booking['booking_id'] ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Name:</span>
                            <span class="detail-value"><?= htmlspecialchars($booking['first_name']) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Email:</span>
                            <span class="detail-value"><?= htmlspecialchars($booking['email']) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Phone:</span>
                            <span class="detail-value"><?= htmlspecialchars($booking['phone']) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Check-In:</span>
                            <span class="detail-value"><?= date('F j, Y', strtotime($booking['booking_date'])) ?></span>
                        </div>
                        <!-- ADDED TIME DISPLAY -->
                        <div class="detail-item">
                            <span class="detail-label">Check-in Time:</span>
                            <span class="detail-value">
                                <?= $booking['booking_time'] === 'morning' ? 'Morning (8AM-12PM)' : 'Afternoon (1PM-5PM)' ?>
                            </span>
                        </div>
                  
                        <div class="detail-item">
                            <span class="detail-label">Guests:</span>
                            <span class="detail-value"><?= $booking['no_of_guest'] ?></span>
                        </div>
                    </div>

                    <button id="newBookingBtn" class="btn btn-primary mt-4">Make Another Booking</button>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="node_modules/fullcalendar/index.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (!$is_success): ?>
                // Initialize calendar only if not in success state
                let calendar;
                const available = 1;
                const calendarEl = document.getElementById('calendar');
                const noRoomsMessage = document.getElementById('no-rooms-message');

                if (available === 0) {
                    noRoomsMessage.style.display = 'block';
                    calendarEl.style.opacity = '0.5';
                    calendarEl.style.pointerEvents = 'none';
                } else {
                    calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        selectable: true,
                        selectAllow: function(selectInfo) {
                            const today = new Date();
                            today.setHours(0, 0, 0, 0);
                            return selectInfo.start >= today;
                        },
                        dayCellDidMount: function(arg) {
                            const today = new Date();
                            today.setHours(0, 0, 0, 0);
                            const cellDate = new Date(arg.date);
                            cellDate.setHours(0, 0, 0, 0);
                            if (cellDate < today) {
                                arg.el.style.backgroundColor = '#F2F2F2';
                                arg.el.style.cursor = 'not-allowed';
                            }
                        },
                        select: function(info) {
                            const startDate = new Date(info.startStr);
                            const options = {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            };
                            const formattedStart = startDate.toLocaleDateString('en-US', options);

                            document.getElementById('checkInDate').textContent = formattedStart;
                            document.getElementById('selectedDates').style.display = 'flex';
                            document.getElementById('summaryCheckIn').textContent = formattedStart;

                            window.bookingData = {
                                startDate: startDate,
                                formattedStart: formattedStart
                            };
                        }
                    });
                    calendar.render();
                }

                // Update time selection display
                function updateTimeDisplay() {
                    const timeValue = document.querySelector('input[name="checkinTime"]:checked').value;
                    const timeDisplay = timeValue === 'morning' ? 'Morning (8AM-12PM)' : 'Afternoon (1PM-5PM)';
                    document.getElementById('summaryCheckInTime').textContent = timeDisplay;
                }

                // Listen to time selection changes
                document.querySelectorAll('input[name="checkinTime"]').forEach(radio => {
                    radio.addEventListener('change', updateTimeDisplay);
                });

                // Step navigation
                document.getElementById('nextStep1').addEventListener('click', function() {
                    if (!window.bookingData) {
                        alert('Please select your dates first');
                        return;
                    }

                    document.getElementById('step1').classList.add('completed-step');
                    document.getElementById('step2').classList.remove('completed-step');
                    document.querySelector('.step:nth-child(1)').classList.remove('active');
                    document.querySelector('.step:nth-child(2)').classList.add('active');
                    document.querySelector('.step:nth-child(1) .step-number').classList.add('completed');

                    // Initialize time display
                    updateTimeDisplay();
                });

                document.getElementById('backToStep1').addEventListener('click', function() {
                    document.getElementById('step2').classList.add('completed-step');
                    document.getElementById('step1').classList.remove('completed-step');
                    document.querySelector('.step:nth-child(2)').classList.remove('active');
                    document.querySelector('.step:nth-child(1)').classList.add('active');
                });

                document.getElementById('bookingForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    const firstName = document.getElementById('firstName').value;
                    const lastName = document.getElementById('lastName').value;
                    const email = document.getElementById('email').value;
                    const phone = document.getElementById('phone').value;
                    const guests = document.getElementById('guests').value;
                    const timeValue = document.querySelector('input[name="checkinTime"]:checked').value;
                    const timeDisplay = timeValue === 'morning' ? 'Morning (8AM-12PM)' : 'Afternoon (1PM-5PM)';

                    // FIXED: Removed references to non-existent elements
                    document.getElementById('confirmationName').textContent = firstName + ' ' + lastName;
                    document.getElementById('confirmationEmail').textContent = email;
                    document.getElementById('confirmationPhone').textContent = phone;
                    document.getElementById('confirmationDates').textContent = window.bookingData.formattedStart;
                    document.getElementById('confirmationGuests').textContent = guests + ' guests';
                    document.getElementById('confirmationTime').textContent = timeDisplay;

                    // Show step 3 and update navigation
                    document.getElementById('step2').classList.add('completed-step');
                    document.getElementById('step3').classList.remove('completed-step');

                    // FIXED: Corrected step navigation indices
                    document.querySelector('.step:nth-child(2)').classList.remove('active');
                    document.querySelector('.step:nth-child(3)').classList.add('active');
                    document.querySelector('.step:nth-child(2) .step-number').classList.add('completed');
                });

                document.getElementById('editBookingBtn').addEventListener('click', function() {
                    document.getElementById('step3').classList.add('completed-step');
                    document.getElementById('step2').classList.remove('completed-step');
                    document.querySelector('.step:nth-child(3)').classList.remove('active');
                    document.querySelector('.step:nth-child(2)').classList.add('active');
                });

                document.getElementById('confirmBookingBtn').addEventListener('click', function() {
                    // Set hidden input values
                    document.getElementById('hiddenFirstName').value = document.getElementById('firstName').value;
                    document.getElementById('hiddenLastName').value = document.getElementById('lastName').value;
                    document.getElementById('hiddenEmail').value = document.getElementById('email').value;
                    document.getElementById('hiddenPhone').value = document.getElementById('phone').value;
                    document.getElementById('hiddenDates').value = window.bookingData.startDate.toISOString().split('T')[0];
                    document.getElementById('hiddenGuests').value = document.getElementById('guests').value;
                    document.getElementById('hiddenTime').value = document.querySelector('input[name="checkinTime"]:checked').value;

                    // Submit the form
                    document.getElementById('bookingConfirmationForm').submit();
                });

            <?php endif; ?>

            // New booking button (always available)
            const newBookingBtn = document.getElementById('newBookingBtn');
            if (newBookingBtn) {
                newBookingBtn.addEventListener('click', function() {
                    window.location.href = 'booking.php';
                });
            }

            // Header scroll effect
            window.addEventListener('scroll', function() {
                const navbar = document.querySelector('.navbar');
                if (window.scrollY > 50) {
                    navbar.style.padding = '10px 0';
                    navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.98)';
                } else {
                    navbar.style.padding = '15px 0';
                    navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
                }
            });
        });
    </script>
</body>

</html>