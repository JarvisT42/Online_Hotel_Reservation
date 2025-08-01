<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHIOJI APARTELLE - Luxury Stays</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/fontawesome/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

</head>

<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg fixed-top">
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
                        <a class="nav-link active" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#gallery">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#testimonials">Testimonials</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Notification -->
    <div class="notification p-3" id="notification">
        Your booking request has been received!
    </div>

    <!-- Hero Section -->
    <section class="hero d-flex align-items-center">
        <div class="container text-center text-white">
            <div class="hero-content">
                <h2 class="display-3 mb-4">Experience Comfort & Convenience</h2>
                <p class="lead mb-5">Enjoy the essentials of home in a simple, welcoming space at SHIOJI APARTELLE — your comfortable and affordable retreat.</p>
            </div>
        </div>
    </section>

    <!-- Booking Form -->
    <div class="container position-relative">
        <div class="booking-form p-4 p-md-5 mx-auto">
            <h3 class="form-title text-center mb-4">Book Your Stay</h3>
            <form>
                <div class="row g-3 mb-4">
                    <div class="col-md-6 col-lg-3">
                        <label for="checkin" class="form-label">Check-In</label>
                        <input type="date" class="form-control form-control-lg" id="checkin" required>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label for="checkout" class="form-label">Check-Out</label>
                        <input type="date" class="form-control form-control-lg" id="checkout" required>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label for="guests" class="form-label">Guests</label>
                        <select class="form-select form-select-lg" id="guests" required>
                            <option value="1">1 Guest</option>
                            <option value="2" selected>2 Guests</option>
                            <option value="3">3 Guests</option>
                            <option value="4">4 Guests</option>
                            <option value="5">5+ Guests</option>
                        </select>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label for="room-type" class="form-label">Room Type</label>
                        <select class="form-select form-select-lg" id="room-type" required>
                            <option value="standard">Standard Room</option>
                            <option value="deluxe">Deluxe Room</option>
                            <option value="family">Family Suite</option>
                            <option value="executive">Executive Suite</option>
                        </select>
                    </div>
                </div>
                <button class="book-btn btn btn-lg w-100 py-3" id="book-now">Check Availability</button>
            </form>
        </div>
    </div>

    <!-- Features Section -->
    <section class="features py-5" id="features" style="padding-top: 200px !important;">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 mb-3">Why Choose SHIOJI</h2>
                <p class="text-muted mx-auto" style="max-width: 700px;">Experience unparalleled comfort and service at our premium apartelle</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card h-100">
                        <div class="feature-icon">
                            <i class="fas fa-concierge-bell"></i>
                        </div>
                        <h3>24/7 Service</h3>
                        <p>Our dedicated staff is available around the clock to assist with all your needs.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card h-100">
                        <div class="feature-icon">
                            <i class="fas fa-wifi"></i>
                        </div>
                        <h3>Free WiFi</h3>
                        <p>Stay connected with high-speed internet available throughout the property.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card h-100">
                        <div class="feature-icon">
                            <i class="fas fa-parking"></i>
                        </div>
                        <h3>Free Parking</h3>
                        <p>Secure parking available for all guests at no additional charge.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card h-100">
                        <div class="feature-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h3>Breakfast Included</h3>
                        <p>Start your day with our complimentary breakfast buffet.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="gallery py-5" id="gallery">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 mb-3">Our Gallery</h2>
                <p class="text-muted mx-auto" style="max-width: 700px;">Explore the beauty and comfort of our apartelle through our visual journey</p>
            </div>

            <div class="d-flex justify-content-center flex-wrap gap-3 mb-4">
                <button class="filter-btn btn active" data-filter="all">All</button>
                <button class="filter-btn btn" data-filter="rooms">Rooms</button>
                <button class="filter-btn btn" data-filter="facilities">Facilities</button>
                <button class="filter-btn btn" data-filter="dining">Dining</button>
                <button class="filter-btn btn" data-filter="views">Views</button>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-4 gallery-item" data-category="rooms">
                    <img src="https://images.unsplash.com/photo-1611892440504-42a792e24d32?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Standard Room" class="gallery-img">
                    <div class="gallery-overlay">
                        <h3 class="h4 mb-2">Standard Room</h3>
                        <p class="mb-1">Comfortable accommodation with all essential amenities</p>
                        <span class="gallery-category badge">Rooms</span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 gallery-item" data-category="facilities">
                    <img src="https://images.unsplash.com/photo-1584622650111-993a426fbf0a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Swimming Pool" class="gallery-img">
                    <div class="gallery-overlay">
                        <h3 class="h4 mb-2">Swimming Pool</h3>
                        <p class="mb-1">Relax and refresh in our sparkling pool area</p>
                        <span class="gallery-category badge">Facilities</span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 gallery-item" data-category="dining">
                    <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Dining Area" class="gallery-img">
                    <div class="gallery-overlay">
                        <h3 class="h4 mb-2">Dining Area</h3>
                        <p class="mb-1">Enjoy delicious meals in our elegant dining space</p>
                        <span class="gallery-category badge">Dining</span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 gallery-item" data-category="rooms">
                    <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Deluxe Room" class="gallery-img">
                    <div class="gallery-overlay">
                        <h3 class="h4 mb-2">Deluxe Room</h3>
                        <p class="mb-1">Spacious accommodation with premium amenities</p>
                        <span class="gallery-category badge">Rooms</span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 gallery-item" data-category="facilities">
                    <img src="https://images.unsplash.com/photo-1566665797739-1674de7a421a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1974&q=80" alt="Lounge Area" class="gallery-img">
                    <div class="gallery-overlay">
                        <h3 class="h4 mb-2">Lounge Area</h3>
                        <p class="mb-1">Comfortable space to relax and socialize</p>
                        <span class="gallery-category badge">Facilities</span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 gallery-item" data-category="views">
                    <img src="https://images.unsplash.com/photo-1501785888041-af3ef285b470?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Scenic View" class="gallery-img">
                    <div class="gallery-overlay">
                        <h3 class="h4 mb-2">Scenic View</h3>
                        <p class="mb-1">Beautiful views from our premium rooms</p>
                        <span class="gallery-category badge">Views</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials py-5" id="testimonials">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 mb-3">Guest Reviews</h2>
                <p class="text-muted mx-auto" style="max-width: 700px;">What our valued guests say about their experience</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="testimonial-card h-100">
                        <div class="testimonial-text mb-4">
                            <p class="mb-0">The rooms are exceptionally clean and comfortable. The staff went above and beyond to make our stay memorable. Will definitely return!</p>
                        </div>
                        <div class="testimonial-author d-flex align-items-center">
                            <div class="author-img me-3">
                                <img src="https://randomuser.me/api/portraits/women/43.jpg" alt="Sarah Johnson" class="rounded-circle" width="60" height="60">
                            </div>
                            <div class="author-info">
                                <h4 class="h5 mb-0">Sarah Johnson</h4>
                                <p class="text-muted small mb-0">Business Traveler</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card h-100">
                        <div class="testimonial-text mb-4">
                            <p class="mb-0">Perfect location with easy access to everything. The breakfast was delicious with plenty of options. Highly recommend this apartelle!</p>
                        </div>
                        <div class="testimonial-author d-flex align-items-center">
                            <div class="author-img me-3">
                                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Michael Chen" class="rounded-circle" width="60" height="60">
                            </div>
                            <div class="author-info">
                                <h4 class="h5 mb-0">Michael Chen</h4>
                                <p class="text-muted small mb-0">Family Vacation</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card h-100">
                        <div class="testimonial-text mb-4">
                            <p class="mb-0">Modern amenities combined with excellent service. The attention to detail throughout our stay was impressive. Five stars!</p>
                        </div>
                        <div class="testimonial-author d-flex align-items-center">
                            <div class="author-img me-3">
                                <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Jessica Williams" class="rounded-circle" width="60" height="60">
                            </div>
                            <div class="author-info">
                                <h4 class="h5 mb-0">Jessica Williams</h4>
                                <p class="text-muted small mb-0">Honeymoon</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact py-5" id="contact">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 mb-3">Contact Us</h2>
                <p class="text-muted mx-auto" style="max-width: 700px;">Reach out to us for reservations, inquiries, or special requests</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="contact-info p-4 p-md-5 h-100">
                        <h2 class="h1 mb-4 position-relative pb-3">Get In Touch</h2>
                        <div class="contact-details mb-4">
                            <div class="contact-item d-flex mb-4">
                                <i class="fas fa-map-marker-alt me-3 mt-1 fs-4"></i>
                                <div>123 Luxury Avenue, Central Business District, Metro City</div>
                            </div>
                            <div class="contact-item d-flex mb-4">
                                <i class="fas fa-phone me-3 mt-1 fs-4"></i>
                                <div>+1 (555) 123-4567</div>
                            </div>
                            <div class="contact-item d-flex mb-4">
                                <i class="fas fa-envelope me-3 mt-1 fs-4"></i>
                                <div>info@shiojiapartelle.com</div>
                            </div>
                            <div class="contact-item d-flex">
                                <i class="fas fa-clock me-3 mt-1 fs-4"></i>
                                <div>24/7 Reception</div>
                            </div>
                        </div>
                        <div class="social-links d-flex gap-3">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="contact-form p-4 p-md-5 h-100">
                        <h2 class="h1 mb-4">Send a Message</h2>
                        <form id="contactForm">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control form-control-lg" id="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control form-control-lg" id="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control form-control-lg" id="phone">
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject</label>
                                <input type="text" class="form-control form-control-lg" id="subject" required>
                            </div>
                            <div class="mb-4">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control form-control-lg" id="message" rows="5" required></textarea>
                            </div>
                            <button type="submit" class="book-btn btn btn-lg w-100 py-3">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="map mt-5">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3931.3873274916336!2d125.2259257!3d6.1603803!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x32f776085db4bd15%3A0xbfe6e40fde05aaad!2sShioji%20Apartelle!5e0!3m2!1sen!2sph!4v1717320000000!5m2!1sen!2sph" allowfullscreen="" loading="lazy" title="Shioji Apartelle Location" class="w-100 h-100"></iframe>
            </div>
        </div>
    </section>

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

    <!-- Lightbox -->
    <div class="lightbox" id="lightbox">
        <span class="lightbox-close" id="lightbox-close">&times;</span>
        <img src="" alt="" class="lightbox-content" id="lightbox-img">
        <div class="lightbox-nav">
            <div class="lightbox-btn" id="lightbox-prev">
                <i class="fas fa-chevron-left"></i>
            </div>
            <div class="lightbox-btn" id="lightbox-next">
                <i class="fas fa-chevron-right"></i>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Set minimum date for check-in to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('checkin').min = today;
        document.getElementById('checkin').value = today;

        // Set initial checkout date to tomorrow
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        const tomorrowFormatted = tomorrow.toISOString().split('T')[0];
        document.getElementById('checkout').min = tomorrowFormatted;
        document.getElementById('checkout').value = tomorrowFormatted;

        // Update checkout date when checkin changes
        document.getElementById('checkin').addEventListener('change', function() {
            const checkinDate = new Date(this.value);
            checkinDate.setDate(checkinDate.getDate() + 1);
            const nextDay = checkinDate.toISOString().split('T')[0];
            document.getElementById('checkout').min = nextDay;
            document.getElementById('checkout').value = nextDay;
        });

        // Notification function
        function showNotification(message) {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.classList.add('show');

            setTimeout(() => {
                notification.classList.remove('show');
            }, 3000);
        }

        // Booking form button animation
        const bookBtn = document.getElementById('book-now');
        bookBtn.addEventListener('click', function(e) {
            e.preventDefault();

            // Basic form validation
            const checkin = document.getElementById('checkin').value;
            const checkout = document.getElementById('checkout').value;

            if (!checkin || !checkout) {
                showNotification('Please select both check-in and check-out dates');
                return;
            }

            this.innerHTML = 'Checking Availability...';
            this.disabled = true;

            // Simulate API request
            setTimeout(() => {
                this.innerHTML = 'Rooms Available!';
                this.style.backgroundColor = '#28a745';
                showNotification('Rooms available for your selected dates!');

                // Reset button after 3 seconds
                setTimeout(() => {
                    this.innerHTML = 'Check Availability';
                    this.disabled = false;
                    this.style.backgroundColor = '';
                }, 3000);
            }, 1500);
        });

        // Form submissions
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            showNotification('Your message has been sent successfully!');
            this.reset();
        });

        document.getElementById('newsletterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            showNotification('Thank you for subscribing to our newsletter!');
            this.reset();
        });

        // Gallery Filtering
        const filterBtns = document.querySelectorAll('.filter-btn');
        const galleryItems = document.querySelectorAll('.gallery-item');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Update active button
                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                const filter = btn.getAttribute('data-filter');

                // Filter items
                galleryItems.forEach(item => {
                    if (filter === 'all' || item.getAttribute('data-category') === filter) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });

        // Lightbox Functionality
        const lightbox = document.getElementById('lightbox');
        const lightboxImg = document.getElementById('lightbox-img');
        const lightboxClose = document.getElementById('lightbox-close');
        const lightboxPrev = document.getElementById('lightbox-prev');
        const lightboxNext = document.getElementById('lightbox-next');

        // Array of all gallery images
        const galleryImages = Array.from(document.querySelectorAll('.gallery-img'));
        let currentImageIndex = 0;

        // Open lightbox with clicked image
        galleryItems.forEach((item, index) => {
            item.addEventListener('click', () => {
                const imgSrc = item.querySelector('.gallery-img').src;
                lightboxImg.src = imgSrc;
                lightbox.classList.add('active');
                document.body.style.overflow = 'hidden'; // Prevent scrolling
                currentImageIndex = index;
            });
        });

        // Close lightbox
        lightboxClose.addEventListener('click', () => {
            lightbox.classList.remove('active');
            document.body.style.overflow = ''; // Restore scrolling
        });

        // Close when clicking outside the image
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox) {
                lightbox.classList.remove('active');
                document.body.style.overflow = ''; // Restore scrolling
            }
        });

        // Navigation between images
        lightboxPrev.addEventListener('click', (e) => {
            e.stopPropagation();
            currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
            lightboxImg.src = galleryImages[currentImageIndex].src;
        });

        lightboxNext.addEventListener('click', (e) => {
            e.stopPropagation();
            currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
            lightboxImg.src = galleryImages[currentImageIndex].src;
        });

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (lightbox.classList.contains('active')) {
                if (e.key === 'Escape') {
                    lightbox.classList.remove('active');
                    document.body.style.overflow = ''; // Restore scrolling
                } else if (e.key === 'ArrowLeft') {
                    currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
                    lightboxImg.src = galleryImages[currentImageIndex].src;
                } else if (e.key === 'ArrowRight') {
                    currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
                    lightboxImg.src = galleryImages[currentImageIndex].src;
                }
            }
        });
    </script>
</body>

</html>