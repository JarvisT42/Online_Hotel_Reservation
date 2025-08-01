<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHIOJI APARTELLE - Rooms & Accommodations</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        :root {
            --primary: #2a5d8a;
            --secondary: #e9b44c;
            --accent: #d74e09;
            --light: #f8f9fa;
            --dark: #1a2a3a;
            --gray: #6c757d;
            --light-gray: #e9ecef;
            --transition: all 0.3s ease;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
            color: var(--dark);
            line-height: 1.6;
            overflow-x: hidden;
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header Styles */
        header {
            background-color: rgba(255, 255, 255, 0.98);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            width: 100%;
            z-index: 1000;
            padding: 15px 0;
        }
        
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            display: flex;
            align-items: center;
        }
        
        .logo h1 {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 700;
            color: var(--primary);
            letter-spacing: 1px;
        }
        
        .logo span {
            color: var(--accent);
        }
        
        nav ul {
            display: flex;
            list-style: none;
        }
        
        nav ul li {
            margin: 0 15px;
        }
        
        nav ul li a {
            text-decoration: none;
            color: var(--dark);
            font-weight: 500;
            font-size: 16px;
            transition: var(--transition);
            position: relative;
            padding: 5px 0;
        }
        
        nav ul li a:hover {
            color: var(--accent);
        }
        
        nav ul li a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent);
            transition: var(--transition);
        }
        
        nav ul li a:hover::after {
            width: 100%;
        }
        
        .book-now-btn {
            padding: 12px 25px;
            border-radius: 30px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            background: var(--accent);
            color: white;
            font-size: 16px;
            box-shadow: 0 4px 10px rgba(215, 78, 9, 0.3);
        }
        
        .book-now-btn:hover {
            background: #c04408;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(215, 78, 9, 0.4);
        }
        
        /* Hero Section */
        .hero {
            height: 60vh;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1582719508461-905c673771fd?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1925&q=80') center/cover no-repeat;
            display: flex;
            align-items: center;
            text-align: center;
            color: white;
            position: relative;
            margin-bottom: 80px;
        }
        
        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            padding-top: 60px;
        }
        
        .hero h2 {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }
        
        .hero p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        /* Section Title */
        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }
        
        .section-title h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 15px;
            position: relative;
            display: inline-block;
            padding-bottom: 15px;
        }
        
        .section-title h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--accent);
            border-radius: 2px;
        }
        
        .section-title p {
            color: var(--gray);
            max-width: 700px;
            margin: 0 auto;
            font-size: 1.1rem;
        }
        
        /* Rooms Section */
        .rooms {
            padding: 40px 0 80px;
        }
        
        .room-filters {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 40px;
        }
        
        .filter-btn {
            padding: 10px 25px;
            border: 2px solid var(--primary);
            background: transparent;
            border-radius: 30px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            color: var(--primary);
        }
        
        .filter-btn.active, .filter-btn:hover {
            background: var(--primary);
            color: white;
        }
        
        .rooms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 40px;
        }
        
        .room-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: var(--transition);
        }
        
        .room-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }
        
        .room-img {
            height: 250px;
            overflow: hidden;
            position: relative;
        }
        
        .room-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }
        
        .room-card:hover .room-img img {
            transform: scale(1.05);
        }
        
        .room-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: var(--accent);
            color: white;
            padding: 8px 15px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 14px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        
        .room-content {
            padding: 25px;
        }
        
        .room-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .room-title h3 {
            font-size: 1.6rem;
            color: var(--primary);
        }
        
        .room-price {
            font-weight: 700;
            font-size: 1.6rem;
            color: var(--accent);
        }
        
        .room-price span {
            font-size: 1rem;
            color: var(--gray);
            font-weight: 400;
        }
        
        .room-description {
            margin-bottom: 20px;
            color: var(--gray);
        }
        
        .room-features {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin: 25px 0;
            padding: 20px 0;
            border-top: 1px solid var(--light-gray);
            border-bottom: 1px solid var(--light-gray);
        }
        
        .feature {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .feature i {
            color: var(--accent);
            font-size: 1.2rem;
            width: 24px;
        }
        
        .room-actions {
            display: flex;
            justify-content: space-between;
            gap: 15px;
        }
        
        .details-btn, .book-btn {
            flex: 1;
            text-align: center;
            padding: 14px;
            border-radius: 8px;
            font-weight: 500;
            transition: var(--transition);
            text-decoration: none;
            cursor: pointer;
            border: none;
            font-size: 16px;
        }
        
        .details-btn {
            background: white;
            color: var(--primary);
            border: 2px solid var(--primary);
        }
        
        .details-btn:hover {
            background: var(--primary);
            color: white;
        }
        
        .book-btn {
            background: var(--accent);
            color: white;
            box-shadow: 0 4px 15px rgba(215, 78, 9, 0.3);
        }
        
        .book-btn:hover {
            background: #c04408;
            box-shadow: 0 6px 20px rgba(215, 78, 9, 0.4);
            transform: translateY(-3px);
        }
        
        /* Amenities Section */
        .amenities {
            background: var(--light);
            padding: 80px 0;
        }
        
        .amenities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }
        
        .amenity-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: var(--transition);
        }
        
        .amenity-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        
        .amenity-icon {
            font-size: 3rem;
            color: var(--accent);
            margin-bottom: 20px;
        }
        
        .amenity-card h3 {
            font-size: 1.4rem;
            margin-bottom: 15px;
            color: var(--primary);
        }
        
        /* Testimonials */
        .testimonials {
            padding: 80px 0;
            background: white;
        }
        
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .testimonial-card {
            background: var(--light);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            position: relative;
        }
        
        .testimonial-card::before {
            content: '"';
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 5rem;
            color: rgba(42, 93, 138, 0.1);
            font-family: 'Playfair Display', serif;
            line-height: 1;
        }
        
        .testimonial-text {
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
            font-style: italic;
            color: var(--dark);
        }
        
        .testimonial-author {
            display: flex;
            align-items: center;
        }
        
        .author-img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 15px;
        }
        
        .author-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .author-info h4 {
            color: var(--primary);
            margin-bottom: 5px;
        }
        
        .author-info p {
            color: var(--gray);
            font-size: 0.9rem;
        }
        
        /* Footer */
        footer {
            background: var(--dark);
            color: white;
            padding: 70px 0 20px;
        }
        
        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .footer-col h3 {
            font-size: 1.4rem;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 10px;
        }
        
        .footer-col h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 2px;
            background: var(--accent);
        }
        
        .footer-col p {
            margin-bottom: 20px;
            color: #aaa;
        }
        
        .social-links {
            display: flex;
            gap: 15px;
        }
        
        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transition: var(--transition);
        }
        
        .social-links a:hover {
            background: var(--accent);
            transform: translateY(-5px);
        }
        
        .footer-links {
            list-style: none;
        }
        
        .footer-links li {
            margin-bottom: 12px;
        }
        
        .footer-links a {
            color: #aaa;
            text-decoration: none;
            transition: var(--transition);
            display: flex;
            align-items: center;
        }
        
        .footer-links a:hover {
            color: var(--accent);
            transform: translateX(5px);
        }
        
        .footer-links a i {
            margin-right: 8px;
            color: var(--accent);
        }
        
        .contact-info {
            list-style: none;
        }
        
        .contact-info li {
            display: flex;
            margin-bottom: 15px;
            color: #aaa;
        }
        
        .contact-info i {
            color: var(--accent);
            margin-right: 15px;
            font-size: 1.2rem;
            min-width: 25px;
        }
        
        .copyright {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #aaa;
            font-size: 0.9rem;
        }
        
        /* Responsive Design */
        @media (max-width: 992px) {
            .hero h2 {
                font-size: 3rem;
            }
        }
        
        @media (max-width: 768px) {
            nav ul {
                display: none;
            }
            
            .hero {
                height: 50vh;
            }
            
            .hero h2 {
                font-size: 2.2rem;
            }
            
            .hero p {
                font-size: 1rem;
            }
            
            .section-title h2 {
                font-size: 2rem;
            }
            
            .rooms-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 576px) {
            .hero h2 {
                font-size: 1.8rem;
            }
            
            .room-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container header-container">
            <div class="logo">
                <h1>SHIOJI <span>APARTELLE</span></h1>
            </div>
            <nav>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#" class="active">Rooms</a></li>
                    <li><a href="#">Services</a></li>
                    <li><a href="#">Gallery</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </nav>
            <button class="book-now-btn">Book Now</button>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container hero-content">
            <h2>Luxurious Accommodations</h2>
            <p>Discover our thoughtfully designed rooms and suites, each crafted to provide the perfect blend of comfort, style, and functionality for your stay.</p>
        </div>
    </section>

    <!-- Rooms Section -->
    <section class="rooms">
        <div class="container">
            <div class="section-title">
                <h2>Our Rooms & Suites</h2>
                <p>Explore our diverse range of accommodations to find the perfect space for your stay</p>
            </div>
            
            <div class="room-filters">
                <button class="filter-btn active">All Rooms</button>
                <button class="filter-btn">Standard</button>
                <button class="filter-btn">Deluxe</button>
                <button class="filter-btn">Suites</button>
                <button class="filter-btn">Family</button>
            </div>
            
            <div class="rooms-grid">
                <!-- Room 1 -->
                <div class="room-card">
                    <div class="room-img">
                        <img src="https://images.unsplash.com/photo-1611892440504-42a792e24d32?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Standard Room">
                        <div class="room-badge">Popular</div>
                    </div>
                    <div class="room-content">
                        <div class="room-title">
                            <h3>Standard Room</h3>
                            <div class="room-price">$89<span>/night</span></div>
                        </div>
                        <p class="room-description">Perfect for solo travelers or couples, featuring a comfortable queen-sized bed and essential amenities.</p>
                        
                        <div class="room-features">
                            <div class="feature">
                                <i class="fas fa-user"></i>
                                <div>2 Guests</div>
                            </div>
                            <div class="feature">
                                <i class="fas fa-bed"></i>
                                <div>1 Bed</div>
                            </div>
                            <div class="feature">
                                <i class="fas fa-expand"></i>
                                <div>30m²</div>
                            </div>
                            <div class="feature">
                                <i class="fas fa-wifi"></i>
                                <div>Free WiFi</div>
                            </div>
                        </div>
                        
                        <div class="room-actions">
                            <button class="details-btn">View Details</button>
                            <button class="book-btn">Book Now</button>
                        </div>
                    </div>
                </div>
                
                <!-- Room 2 -->
                <div class="room-card">
                    <div class="room-img">
                        <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Deluxe Room">
                        <div class="room-badge">Best Seller</div>
                    </div>
                    <div class="room-content">
                        <div class="room-title">
                            <h3>Deluxe Room</h3>
                            <div class="room-price">$129<span>/night</span></div>
                        </div>
                        <p class="room-description">Spacious room with king-sized bed, sitting area, and premium amenities for a comfortable stay.</p>
                        
                        <div class="room-features">
                            <div class="feature">
                                <i class="fas fa-user"></i>
                                <div>3 Guests</div>
                            </div>
                            <div class="feature">
                                <i class="fas fa-bed"></i>
                                <div>1 Bed</div>
                            </div>
                            <div class="feature">
                                <i class="fas fa-expand"></i>
                                <div>45m²</div>
                            </div>
                            <div class="feature">
                                <i class="fas fa-tv"></i>
                                <div>Smart TV</div>
                            </div>
                        </div>
                        
                        <div class="room-actions">
                            <button class="details-btn">View Details</button>
                            <button class="book-btn">Book Now</button>
                        </div>
                    </div>
                </div>
                
                <!-- Room 3 -->
                <div class="room-card">
                    <div class="room-img">
                        <img src="https://images.unsplash.com/photo-1618773928121-c32242e63f39?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Family Suite">
                        <div class="room-badge">Family Choice</div>
                    </div>
                    <div class="room-content">
                        <div class="room-title">
                            <h3>Family Suite</h3>
                            <div class="room-price">$179<span>/night</span></div>
                        </div>
                        <p class="room-description">Two connecting rooms perfect for families with children, featuring separate sleeping and living areas.</p>
                        
                        <div class="room-features">
                            <div class="feature">
                                <i class="fas fa-user"></i>
                                <div>5 Guests</div>
                            </div>
                            <div class="feature">
                                <i class="fas fa-bed"></i>
                                <div>3 Beds</div>
                            </div>
                            <div class="feature">
                                <i class="fas fa-expand"></i>
                                <div>65m²</div>
                            </div>
                            <div class="feature">
                                <i class="fas fa-utensils"></i>
                                <div>Kitchenette</div>
                            </div>
                        </div>
                        
                        <div class="room-actions">
                            <button class="details-btn">View Details</button>
                            <button class="book-btn">Book Now</button>
                        </div>
                    </div>
                </div>
                
                <!-- Room 4 -->
                <div class="room-card">
                    <div class="room-img">
                        <img src="https://images.unsplash.com/photo-1592229505726-cf121663d0e1?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Executive Suite">
                        <div class="room-badge">Luxury</div>
                    </div>
                    <div class="room-content">
                        <div class="room-title">
                            <h3>Executive Suite</h3>
                            <div class="room-price">$229<span>/night</span></div>
                        </div>
                        <p class="room-description">Premium suite with separate living room, work area, and luxurious bathroom with spa features.</p>
                        
                        <div class="room-features">
                            <div class="feature">
                                <i class="fas fa-user"></i>
                                <div>4 Guests</div>
                            </div>
                            <div class="feature">
                                <i class="fas fa-bed"></i>
                                <div>2 Beds</div>
                            </div>
                            <div class="feature">
                                <i class="fas fa-expand"></i>
                                <div>75m²</div>
                            </div>
                            <div class="feature">
                                <i class="fas fa-hot-tub"></i>
                                <div>Jacuzzi</div>
                            </div>
                        </div>
                        
                        <div class="room-actions">
                            <button class="details-btn">View Details</button>
                            <button class="book-btn">Book Now</button>
                        </div>
                    </div>
                </div>
                
                <!-- Room 5 -->
                <div class="room-card">
                    <div class="room-img">
                        <img src="https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Premium Studio">
                        <div class="room-badge">New</div>
                    </div>
                    <div class="room-content">
                        <div class="room-title">
                            <h3>Premium Studio</h3>
                            <div class="room-price">$159<span>/night</span></div>
                        </div>
                        <p class="room-description">Modern studio with fully equipped kitchenette and comfortable living space for extended stays.</p>
                        
                        <div class="room-features">
                            <div class="feature">
                                <i class="fas fa-user"></i>
                                <div>3 Guests</div>
                            </div>
                            <div class="feature">
                                <i class="fas fa-bed"></i>
                                <div>1 Bed</div>
                            </div>
                            <div class="feature">
                                <i class="fas fa-expand"></i>
                                <div>50m²</div>
                            </div>
                            <div class="feature">
                                <i class="fas fa-utensils"></i>
                                <div>Full Kitchen</div>
                            </div>
                        </div>
                        
                        <div class="room-actions">
                            <button class="details-btn">View Details</button>
                            <button class="book-btn">Book Now</button>
                        </div>
                    </div>
                </div>
                
                <!-- Room 6 -->
                <div class="room-card">
                    <div class="room-img">
                        <img src="https://images.unsplash.com/photo-1616594039964-ae9021a400a0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2080&q=80" alt="Accessible Room">
                        <div class="room-badge">Accessible</div>
                    </div>
                    <div class="room-content">
                        <div class="room-title">
                            <h3>Accessible Room</h3>
                            <div class="room-price">$99<span>/night</span></div>
                        </div>
                        <p class="room-description">Specially designed room with accessibility features for guests with mobility challenges.</p>
                        
                        <div class="room-features">
                            <div class="feature">
                                <i class="fas fa-user"></i>
                                <div>2 Guests</div>
                            </div>
                            <div class="feature">
                                <i class="fas fa-bed"></i>
                                <div>1 Bed</div>
                            </div>
                            <div class="feature">
                                <i class="fas fa-expand"></i>
                                <div>40m²</div>
                            </div>
                            <div class="feature">
                                <i class="fas fa-wheelchair"></i>
                                <div>Accessible</div>
                            </div>
                        </div>
                        
                        <div class="room-actions">
                            <button class="details-btn">View Details</button>
                            <button class="book-btn">Book Now</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Amenities Section -->
    <section class="amenities">
        <div class="container">
            <div class="section-title">
                <h2>Property Amenities</h2>
                <p>Enjoy our premium facilities and services during your stay</p>
            </div>
            
            <div class="amenities-grid">
                <div class="amenity-card">
                    <div class="amenity-icon">
                        <i class="fas fa-swimming-pool"></i>
                    </div>
                    <h3>Swimming Pool</h3>
                    <p>Relax in our heated swimming pool with panoramic views</p>
                </div>
                
                <div class="amenity-card">
                    <div class="amenity-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <h3>Restaurant & Bar</h3>
                    <p>Enjoy delicious meals and cocktails at our on-site dining</p>
                </div>
                
                <div class="amenity-card">
                    <div class="amenity-icon">
                        <i class="fas fa-spa"></i>
                    </div>
                    <h3>Spa & Wellness</h3>
                    <p>Rejuvenate with our range of spa treatments and massages</p>
                </div>
                
                <div class="amenity-card">
                    <div class="amenity-icon">
                        <i class="fas fa-dumbbell"></i>
                    </div>
                    <h3>Fitness Center</h3>
                    <p>Stay active in our fully equipped 24/7 fitness facility</p>
                </div>
                
                <div class="amenity-card">
                    <div class="amenity-icon">
                        <i class="fas fa-parking"></i>
                    </div>
                    <h3>Free Parking</h3>
                    <p>Secure parking available for all guests at no extra charge</p>
                </div>
                
                <div class="amenity-card">
                    <div class="amenity-icon">
                        <i class="fas fa-wifi"></i>
                    </div>
                    <h3>High-Speed WiFi</h3>
                    <p>Complimentary high-speed internet throughout the property</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="testimonials">
        <div class="container">
            <div class="section-title">
                <h2>Guest Experiences</h2>
                <p>What our guests say about their stay at SHIOJI APARTELLE</p>
            </div>
            
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        <p>"The rooms are exceptionally clean and comfortable. The staff went above and beyond to make our stay memorable. Will definitely return!"</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-img">
                            <img src="https://randomuser.me/api/portraits/women/43.jpg" alt="Sarah Johnson">
                        </div>
                        <div class="author-info">
                            <h4>Sarah Johnson</h4>
                            <p>Business Traveler</p>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        <p>"Perfect location with easy access to everything. The breakfast was delicious with plenty of options. Highly recommend this apartelle!"</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-img">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Michael Chen">
                        </div>
                        <div class="author-info">
                            <h4>Michael Chen</h4>
                            <p>Family Vacation</p>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        <p>"Modern amenities combined with excellent service. The attention to detail throughout our stay was impressive. Five stars!"</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-img">
                            <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Jessica Williams">
                        </div>
                        <div class="author-info">
                            <h4>Jessica Williams</h4>
                            <p>Honeymoon</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h3>SHIOJI APARTELLE</h3>
                    <p>Experience luxury, comfort, and exceptional service at our premium apartelle. Your perfect getaway awaits.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="footer-col">
                    <h3>Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Home</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Rooms & Suites</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Services</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Gallery</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> About Us</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3>Contact Us</h3>
                    <ul class="contact-info">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span>123 Luxury Avenue, Central Business District, Metro City</span>
                        </li>
                        <li>
                            <i class="fas fa-phone"></i>
                            <span>+1 (555) 123-4567</span>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <span>info@shiojiapartelle.com</span>
                        </li>
                        <li>
                            <i class="fas fa-clock"></i>
                            <span>24/7 Reception</span>
                        </li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3>Newsletter</h3>
                    <p>Subscribe to our newsletter for special offers and updates.</p>
                    <form>
                        <div class="form-group">
                            <input type="email" placeholder="Your Email" style="width: 100%; margin-bottom: 15px; padding: 12px; border-radius: 8px; border: 1px solid #ddd;">
                        </div>
                        <button class="book-btn" style="background: var(--accent); width: 100%;">Subscribe</button>
                    </form>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2023 SHIOJI APARTELLE. All Rights Reserved. Designed with <i class="fas fa-heart" style="color: var(--accent);"></i></p>
            </div>
        </div>
    </footer>

    <script>
        // Room filter functionality
        const filterButtons = document.querySelectorAll('.filter-btn');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons
                filterButtons.forEach(btn => btn.classList.remove('active'));
                
                // Add active class to clicked button
                button.classList.add('active');
                
                // In a real implementation, this would filter the room cards
                // For this demo, we'll just show a message
                const roomType = button.textContent;
                if (roomType !== 'All Rooms') {
                    alert(`Filtering rooms by: ${roomType}`);
                } else {
                    alert('Showing all rooms');
                }
            });
        });
        
        // Book button functionality
        const bookButtons = document.querySelectorAll('.book-btn');
        
        bookButtons.forEach(button => {
            button.addEventListener('click', function() {
                const roomCard = this.closest('.room-card');
                const roomName = roomCard.querySelector('h3').textContent;
                
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Booking...';
                this.disabled = true;
                
                setTimeout(() => {
                    this.innerHTML = 'Booked!';
                    this.style.backgroundColor = '#28a745';
                    alert(`Successfully booked the ${roomName}!`);
                }, 1500);
            });
        });
        
        // View details button functionality
        const detailsButtons = document.querySelectorAll('.details-btn');
        
        detailsButtons.forEach(button => {
            button.addEventListener('click', function() {
                const roomCard = this.closest('.room-card');
                const roomName = roomCard.querySelector('h3').textContent;
                alert(`Showing details for: ${roomName}`);
            });
        });
        
        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.querySelector('header');
            if (window.scrollY > 50) {
                header.style.padding = '10px 0';
                header.style.backgroundColor = 'rgba(255, 255, 255, 0.98)';
            } else {
                header.style.padding = '15px 0';
                header.style.backgroundColor = 'rgba(255, 255, 255, 0.98)';
            }
        });
    </script>
</body>
</html>