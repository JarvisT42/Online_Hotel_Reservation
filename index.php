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
	<link rel="stylesheet" href="css/style.css">

</head>

<body>
	<!-- Header -->
	<?php include 'navbar.php'; ?>


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
		<div class="booking-form p-4 p-md-5 mx-auto text-center">
			<h3 class="form-title mb-4">Book Your Stay</h3>
			<a href="booking.php" class="btn btn-primary btn-lg w-100 py-3">Book Now</a>
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
				<p class="text-muted mx-auto" style="max-width: 700px;">
					Explore the beauty and comfort of our apartelle through our visual journey
				</p>
			</div>

			<!-- Filter Buttons -->
			<div class="d-flex justify-content-center flex-wrap gap-2 mb-4">
				<button class="filter-btn btn btn-outline-primary active" data-filter="all">All</button>
				<button class="filter-btn btn btn-outline-primary" data-filter="rooms">Rooms</button>
				<button class="filter-btn btn btn-outline-primary" data-filter="facilities">Facilities</button>
				<button class="filter-btn btn btn-outline-primary" data-filter="dining">Dining</button>
				<button class="filter-btn btn btn-outline-primary" data-filter="views">Views</button>
			</div>

			<!-- Gallery Grid -->
			<div class="row g-4">
				<!-- Repeatable gallery-item -->
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
						<img src="images/m.jpg" alt="Swimming Pool" class="gallery-img">
						<div class="gallery-overlay">
							<h3 class="h4 mb-2">Event Venue</h3>
							<!-- <p class="mb-1">Relax and refresh in our sparkling pool area</p> -->
							<span class="gallery-category badge">Facilities</span>
						</div>
					</div>

					<div class="col-md-6 col-lg-4 gallery-item" data-category="facilities">
						<img src="images/300370577_486410903491165_1322583682644210847_n.jpg" alt="Swimming Pool" class="gallery-img">
						<div class="gallery-overlay">
							<h3 class="h4 mb-2">Information</h3>
							<!-- <p class="mb-1">Relax and refresh in our sparkling pool area</p> -->
							<span class="gallery-category badge">Facilities</span>
						</div>
					</div>

					<div class="col-md-6 col-lg-4 gallery-item" data-category="dining">
						<img src="images/k.jpg" alt="Dining Area" class="gallery-img">
						<div class="gallery-overlay">
							<h3 class="h4 mb-2">Dining Area</h3>
							<p class="mb-1">Enjoy delicious meals in our elegant dining space</p>
							<span class="gallery-category badge">Dining</span>
						</div>
					</div>

					<div class="col-md-6 col-lg-4 gallery-item" data-category="dining">
						<img src="images/l.jpg" alt="Dining Area" class="gallery-img">
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
					<!-- <div class="col-md-6 col-lg-4 gallery-item" data-category="facilities">
						<img src="images/m.jpg" alt="Lounge Area" class="gallery-img">
						<div class="gallery-overlay">
							<h3 class="h4 mb-2">Lounge Area</h3>
							<p class="mb-1">Comfortable space to relax and socialize</p>
							<span class="gallery-category badge">Facilities</span>
						</div>
					</div> -->
					<div class="col-md-6 col-lg-4 gallery-item" data-category="views">
						<img src="images/img.webp" alt="Scenic View" class="gallery-img">
						<div class="gallery-overlay">
							<h3 class="h4 mb-2">Scenic View</h3>
							<p class="mb-1">Beautiful views from our premium rooms</p>
							<span class="gallery-category badge">Views</span>
						</div>
					</div>
				</div>

				<!-- Add other gallery items here following the same structure -->
			</div>
		</div>

		<!-- Modal -->
		<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-xl">
				<div class="modal-content bg-transparent border-0">
					<div class="modal-body p-0">
						<img src="" id="modalImage" class="img-fluid rounded w-100" style="max-height: 80vh; object-fit: contain;" alt="Popup Image">
					</div>
				</div>
			</div>
		</div>

		<!-- JS for Image Modal & Filtering -->
		<script>
			// Image Modal logic
			document.querySelectorAll('.gallery-img').forEach(img => {
				img.addEventListener('click', function() {
					document.getElementById('modalImage').src = this.src;
					const myModal = new bootstrap.Modal(document.getElementById('imageModal'));
					myModal.show();
				});
			});

			// Filter Logic
			const filterBtns = document.querySelectorAll('.filter-btn');
			const galleryItems = document.querySelectorAll('.gallery-item');

			filterBtns.forEach(btn => {
				btn.addEventListener('click', () => {
					const category = btn.dataset.filter;

					filterBtns.forEach(b => b.classList.remove('active'));
					btn.classList.add('active');

					galleryItems.forEach(item => {
						const itemCategory = item.dataset.category;
						if (category === 'all' || itemCategory === category) {
							item.style.display = 'block';
						} else {
							item.style.display = 'none';
						}
					});
				});
			});
		</script>

		<!-- CSS to unify image height and styling -->
		<style>
			.gallery-img {
				height: 300px;
				object-fit: cover;
				cursor: pointer;
				transition: transform 0.3s ease;
			}

			.gallery-img:hover {
				transform: scale(1.02);
			}

			.filter-btn.active {
				background-color: #0d6efd;
				color: white;
			}

			.gallery-item {
				transition: all 0.3s ease-in-out;
			}
		</style>
	</section>


	<!-- Testimonials Section -->
	<!-- <section class="testimonials py-5" id="testimonials">
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
	</section> -->

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
							<a href="https://www.facebook.com/shiojiapartelle"><i class="fab fa-facebook-f"></i></a>
							<!-- <a href="#"><i class="fab fa-twitter"></i></a>
							<a href="#"><i class="fab fa-instagram"></i></a>
							<a href="#"><i class="fab fa-linkedin-in"></i></a> -->
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
	<?php include 'footer.php'; ?>


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
	<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->

	<script src="assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
	<script>
		// Header scroll effect
	</script>
</body>

</html>