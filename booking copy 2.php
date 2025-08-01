<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHIOJI APARTELLE - Book Your Stay</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />

    <link rel="stylesheet" href="style.css">



</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand logo" href="#">
                <h1>SHIOJI <span>APARTELLE</span></h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Testimonials</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Booking Container -->
    <div class="container booking-container">
        <div class="booking-header">
            <h2>Book Your Stay</h2>
            <p>Select your dates, provide your details, and secure your reservation at SHIOJI APARTELLE</p>
        </div>

        <!-- Step Indicator -->
        <div class="step-indicator">
            <div class="step active">
                <div class="step-number">1</div>
                <div class="step-label">Select Dates</div>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <div class="step-label">Guest Information</div>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <div class="step-label">Confirmation</div>
            </div>
            <div class="step">
                <div class="step-number">4</div>
                <div class="step-label">Success</div>
            </div>
        </div>

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
                    <div class="date-info">
                        <span class="date-label">Nights:</span>
                        <span id="nightsCount" class="date-value"></span>
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
                                <option value="3">3 Guests</option>
                                <option value="4">4 Guests</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="roomType" class="form-label">Room Type</label>
                            <select class="form-select" id="roomType" required>
                                <option value="standard">Standard Room ($89/night)</option>
                                <option value="deluxe">Deluxe Room ($129/night)</option>
                                <option value="family">Family Suite ($159/night)</option>
                            </select>
                        </div>
                    </div>

                    <div class="booking-summary">
                        <h5 class="mb-3">Booking Summary</h5>
                        <div class="summary-item">
                            <span>Room Type:</span>
                            <span id="summaryRoomType">Standard Room</span>
                        </div>
                        <div class="summary-item">
                            <span>Check-In:</span>
                            <span id="summaryCheckIn"></span>
                        </div>
                        <div class="summary-item">
                            <span>Nights:</span>
                            <span id="summaryNights">0</span>
                        </div>
                        <div class="summary-item">
                            <span>Guests:</span>
                            <span id="summaryGuests">1</span>
                        </div>
                        <div class="summary-total">
                            <span>Total:</span>
                            <span id="summaryTotal">$0</span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-4">Next</button>
                </form>
            </div>
        </div>

        <!-- Step 3: Confirmation -->
        <div id="step3" class="booking-step completed-step">
            <div class="booking-section">
                <h3 class="section-title">Confirm Your Booking</h3>
                <div class="confirmation">
                    <p>Please review your booking details before confirming your reservation.</p>

                    <div class="booking-details">
                        <div class="detail-item">
                            <span class="detail-label">Booking ID:</span>
                            <span class="detail-value" id="confirmationId">SHIOJI-2023-00123</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Name:</span>
                            <span class="detail-value" id="confirmationName"></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Email:</span>
                            <span class="detail-value" id="confirmationEmail"></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Phone:</span>
                            <span class="detail-value" id="confirmationPhone"></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Check-In:</span>
                            <span class="detail-value" id="confirmationDates"></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Nights:</span>
                            <span class="detail-value" id="confirmationNights"></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Room Type:</span>
                            <span class="detail-value" id="confirmationRoom"></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Guests:</span>
                            <span class="detail-value" id="confirmationGuests"></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Total:</span>
                            <span class="detail-value" id="confirmationTotal"></span>
                        </div>
                    </div>

                    <div class="review-buttons">
                        <button id="editBookingBtn" class="btn btn-outline-primary">Edit Details</button>
                        <button id="confirmBookingBtn" class="btn btn-primary">Confirm Booking</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 4: Success -->
        <div id="step4" class="booking-step completed-step">
            <div class="confirmation">
                <div class="confirmation-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2>Booking Confirmed!</h2>
                <p>Thank you for choosing SHIOJI APARTELLE. Your reservation has been successfully booked. We've sent a confirmation to your email.</p>

                <div class="booking-details">
                    <div class="detail-item">
                        <span class="detail-label">Booking ID:</span>
                        <span class="detail-value" id="successId">SHIOJI-2023-00123</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Name:</span>
                        <span class="detail-value" id="successName"></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value" id="successEmail"></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Phone:</span>
                        <span class="detail-value" id="successPhone"></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Check-In:</span>
                        <span class="detail-value" id="successDates"></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Nights:</span>
                        <span class="detail-value" id="successNights"></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Room Type:</span>
                        <span class="detail-value" id="successRoom"></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Guests:</span>
                        <span class="detail-value" id="successGuests"></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Total:</span>
                        <span class="detail-value" id="successTotal"></span>
                    </div>
                </div>

                <button id="newBookingBtn" class="btn btn-primary mt-4">Make Another Booking</button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="pt-5">
        <div class="container">
            <div class="row g-4 mb-4">
                <div class="col-md-6 col-lg-3">
                    <div class="footer-col">
                        <h3 class="h4 mb-3">SHIOJI APARTELLE</h3>
                        <p class="mb-4">Experience luxury, comfort, and exceptional service at our premium apartelle. Your perfect getaway awaits.</p>
                        <div class="social-links d-flex gap-3">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="footer-col">
                        <h3 class="h4 mb-3">Quick Links</h3>
                        <ul class="footer-links list-unstyled">
                            <li class="mb-2"><a href="#" class="text-decoration-none d-flex align-items-center"><i class="fas fa-chevron-right me-2"></i> Home</a></li>
                            <li class="mb-2"><a href="#gallery" class="text-decoration-none d-flex align-items-center"><i class="fas fa-chevron-right me-2"></i> Gallery</a></li>
                            <li class="mb-2"><a href="#features" class="text-decoration-none d-flex align-items-center"><i class="fas fa-chevron-right me-2"></i> Services</a></li>
                            <li class="mb-2"><a href="#testimonials" class="text-decoration-none d-flex align-items-center"><i class="fas fa-chevron-right me-2"></i> Testimonials</a></li>
                            <li><a href="#contact" class="text-decoration-none d-flex align-items-center"><i class="fas fa-chevron-right me-2"></i> Contact</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="footer-col">
                        <h3 class="h4 mb-3">Contact Us</h3>
                        <ul class="contact-info-footer list-unstyled">
                            <li class="d-flex mb-3">
                                <i class="fas fa-map-marker-alt me-3 mt-1"></i>
                                <span>123 Luxury Avenue, Central Business District, Metro City</span>
                            </li>
                            <li class="d-flex mb-3">
                                <i class="fas fa-phone me-3 mt-1"></i>
                                <span>+1 (555) 123-4567</span>
                            </li>
                            <li class="d-flex mb-3">
                                <i class="fas fa-envelope me-3 mt-1"></i>
                                <span>info@shiojiapartelle.com</span>
                            </li>
                            <li class="d-flex">
                                <i class="fas fa-clock me-3 mt-1"></i>
                                <span>24/7 Reception</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="footer-col">
                        <h3 class="h4 mb-3">Newsletter</h3>
                        <p class="mb-4">Subscribe to our newsletter for special offers and updates.</p>
                        <form id="newsletterForm">
                            <div class="mb-3">
                                <input type="email" class="form-control form-control-lg" placeholder="Your Email" required>
                            </div>
                            <button class="book-btn btn btn-lg w-100 py-3">Subscribe</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="copyright text-center py-3">
                <p class="mb-0">&copy; 2023 SHIOJI APARTELLE. All Rights Reserved. Designed with <i class="fas fa-heart text-accent"></i></p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS & FullCalendar -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize calendar
            let calendar;
            const available = 1; // Set to 0 to test "No rooms available"
            const calendarEl = document.getElementById('calendar');
            const noRoomsMessage = document.getElementById('no-rooms-message');

            if (available === 0) {
                // Show "No rooms available" message
                noRoomsMessage.style.display = 'block';
                calendarEl.style.opacity = '0.5';
                calendarEl.style.pointerEvents = 'none';
            } else {
                // Initialize FullCalendar normally
                calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    selectable: true,
                    selectAllow: function(selectInfo) {
                        // Allow selection only if start date is today or later
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
                        const endDate = new Date(info.endStr);
                        endDate.setDate(endDate.getDate() - 1); // Adjust for exclusive end

                        const timeDiff = Math.abs(endDate.getTime() - startDate.getTime());
                        const nights = Math.ceil(timeDiff / (1000 * 3600 * 24));

                        const options = {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        };

                        const formattedStart = startDate.toLocaleDateString('en-US', options);

                        // Update UI
                        document.getElementById('checkInDate').textContent = formattedStart;
                        document.getElementById('nightsCount').textContent = nights + ' nights';
                        document.getElementById('selectedDates').style.display = 'flex';

                        // Update step 2 summary
                        document.getElementById('summaryCheckIn').textContent = formattedStart;
                        document.getElementById('summaryNights').textContent = nights + ' nights';

                        // Calculate initial total
                        calculateTotal();

                        // Save dates for later use
                        window.bookingData = {
                            startDate: startDate,
                            endDate: endDate,
                            nights: nights,
                            formattedStart: formattedStart
                        };
                    }
                });
                calendar.render();
            }

            // Step navigation
            document.getElementById('nextStep1').addEventListener('click', function() {
                // Validate that dates are selected
                if (!window.bookingData) {
                    alert('Please select your dates first');
                    return;
                }

                document.getElementById('step1').classList.add('completed-step');
                document.getElementById('step2').classList.remove('completed-step');

                // Update step indicator
                document.querySelector('.step:nth-child(1)').classList.remove('active');
                document.querySelector('.step:nth-child(2)').classList.add('active');
                document.querySelector('.step:nth-child(1) .step-number').classList.add('completed');
            });

            // Calculate booking total
            function calculateTotal() {
                if (!window.bookingData) return;

                const roomType = document.getElementById('roomType').value;
                const guests = parseInt(document.getElementById('guests').value);
                const nights = window.bookingData.nights;

                let pricePerNight = 89;
                if (roomType === 'deluxe') pricePerNight = 129;
                else if (roomType === 'family') pricePerNight = 159;

                const subtotal = pricePerNight * nights;
                const tax = subtotal * 0.1; // 10% tax
                const total = subtotal + tax;

                // Update summary
                document.getElementById('summaryTotal').textContent = '$' + total.toFixed(2);
                document.getElementById('summaryRoomType').textContent =
                    document.getElementById('roomType').options[document.getElementById('roomType').selectedIndex].text;
                document.getElementById('summaryGuests').textContent = guests + ' guests';

                return total.toFixed(2);
            }

            // Update total when inputs change
            document.getElementById('roomType').addEventListener('change', calculateTotal);
            document.getElementById('guests').addEventListener('change', calculateTotal);

            // Form submission
            document.getElementById('bookingForm').addEventListener('submit', function(e) {
                e.preventDefault();

                // Get form data
                const firstName = document.getElementById('firstName').value;
                const lastName = document.getElementById('lastName').value;
                const email = document.getElementById('email').value;
                const phone = document.getElementById('phone').value;
                const guests = document.getElementById('guests').value;
                const roomType = document.getElementById('roomType').options[document.getElementById('roomType').selectedIndex].text;
                const total = calculateTotal();

                // Update confirmation page
                document.getElementById('confirmationName').textContent = firstName + ' ' + lastName;
                document.getElementById('confirmationEmail').textContent = email;
                document.getElementById('confirmationPhone').textContent = phone;
                document.getElementById('confirmationDates').textContent = window.bookingData.formattedStart;
                document.getElementById('confirmationRoom').textContent = roomType;
                document.getElementById('confirmationGuests').textContent = guests + ' guests';
                document.getElementById('confirmationTotal').textContent = '$' + total;
                document.getElementById('confirmationNights').textContent = window.bookingData.nights + ' nights';

                // Generate random booking ID
                const bookingId = 'SHIOJI-' + new Date().getFullYear() + '-' + Math.floor(1000 + Math.random() * 9000);
                document.getElementById('confirmationId').textContent = bookingId;

                // Show confirmation
                document.getElementById('step2').classList.add('completed-step');
                document.getElementById('step3').classList.remove('completed-step');

                // Update step indicator
                document.querySelector('.step:nth-child(2)').classList.remove('active');
                document.querySelector('.step:nth-child(3)').classList.add('active');
                document.querySelector('.step:nth-child(2) .step-number').classList.add('completed');
            });

            // Confirm booking button
            document.getElementById('confirmBookingBtn').addEventListener('click', function() {
                // Get data from step 3
                const bookingId = document.getElementById('confirmationId').textContent;
                const name = document.getElementById('confirmationName').textContent;
                const email = document.getElementById('confirmationEmail').textContent;
                const phone = document.getElementById('confirmationPhone').textContent;
                const dates = document.getElementById('confirmationDates').textContent;
                const nights = document.getElementById('confirmationNights').textContent;
                const room = document.getElementById('confirmationRoom').textContent;
                const guests = document.getElementById('confirmationGuests').textContent;
                const total = document.getElementById('confirmationTotal').textContent;

                // Update success page
                document.getElementById('successId').textContent = bookingId;
                document.getElementById('successName').textContent = name;
                document.getElementById('successEmail').textContent = email;
                document.getElementById('successPhone').textContent = phone;
                document.getElementById('successDates').textContent = dates;
                document.getElementById('successNights').textContent = nights;
                document.getElementById('successRoom').textContent = room;
                document.getElementById('successGuests').textContent = guests;
                document.getElementById('successTotal').textContent = total;

                // Show success
                document.getElementById('step3').classList.add('completed-step');
                document.getElementById('step4').classList.remove('completed-step');

                // Update step indicator
                document.querySelector('.step:nth-child(3)').classList.remove('active');
                document.querySelector('.step:nth-child(4)').classList.add('active');
                document.querySelector('.step:nth-child(3) .step-number').classList.add('completed');
            });

            // Edit booking button
            document.getElementById('editBookingBtn').addEventListener('click', function() {
                document.getElementById('step3').classList.add('completed-step');
                document.getElementById('step2').classList.remove('completed-step');

                // Update step indicator
                document.querySelector('.step:nth-child(3)').classList.remove('active');
                document.querySelector('.step:nth-child(2)').classList.add('active');
            });

            // New booking button
            document.getElementById('newBookingBtn').addEventListener('click', function() {
                // Reset everything
                document.getElementById('step4').classList.add('completed-step');
                document.getElementById('step1').classList.remove('completed-step');
                document.getElementById('selectedDates').style.display = 'none';
                document.getElementById('bookingForm').reset();

                // Reset step indicator
                document.querySelector('.step:nth-child(1)').classList.add('active');
                document.querySelector('.step:nth-child(2)').classList.remove('active');
                document.querySelector('.step:nth-child(3)').classList.remove('active');
                document.querySelector('.step:nth-child(4)').classList.remove('active');
                document.querySelectorAll('.step-number').forEach(el => el.classList.remove('completed'));

                // Reset calendar if available
                if (calendar) {
                    calendar.unselect();
                }

                // Clear booking data
                window.bookingData = null;
            });

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