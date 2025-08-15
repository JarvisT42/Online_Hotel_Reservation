<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>House Rules</title>
    <link rel="stylesheet" href="assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/fontawesome/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
    <link rel="stylesheet" href="css/style.css">
</head>
<style>
    /* Add spacing so the first card doesn't overlap the header */
    main.container {
        margin-top: 120px;
        /* padding-top: 200px; */
        /* adjust if your navbar height is different */
    }

    /* House Rules Cards */
    .card {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        padding: 20px 25px;
        margin-bottom: 30px;
        border: none;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
    }

    /* Section Title */
    .section-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.2rem;
        font-weight: 600;
        margin-bottom: 40px;
        text-align: center;
    }

    /* Headings inside cards */
    .card h2 {
        font-size: 1.4rem;
        font-weight: 600;
        margin-bottom: 15px;
        color: var(--primary-color, #333);
    }

    /* Ordered lists inside cards */
    .card ol {
        padding-left: 20px;
    }

    .card ol li {
        margin-bottom: 8px;
        line-height: 1.6;
    }
</style>

<body>

    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand logo" href="index.php">
                <h1>SHIOJI <span>APARTELLE</span></h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#gallery">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#features">Services</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="index.php">About</a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#contact">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary ms-lg-3 mt-2 mt-lg-0" href="login.php">Login</a>
                    </li>
                </ul>

            </div>
        </div>
    </nav>


    <!-- Page Content -->
    <main class="container" style="padding: 40px 20px;">
        <h1 class="section-title">House Rules</h1>

        <section class="card">
            <h2>1. General Conduct</h2>
            <ol>
                <li>Respect fellow tenants, staff, and visitors at all times.</li>
                <li>No loud noises or disruptive behavior, especially between <b>10:00 PM and 7:00 AM</b>.</li>
                <li>No illegal activities (drug use, gambling, theft, etc.) on the premises.</li>
                <li>Guests must follow all house rules; tenants are responsible for their guests’ actions.</li>
            </ol>
        </section>

        <section class="card">
            <h2>2. Rent & Payments</h2>
            <ol>
                <li>Rent must be paid on time.</li>
                <li>Security deposits are refundable upon move-out, provided there is no damage.</li>
                <li>Any additional fees (utilities, internet, etc.) must be settled as agreed.</li>
            </ol>
        </section>

        <section class="card">
            <h2>3. Visitors & Guests</h2>
            <ol>
                <li>Visitors are allowed between <b>8:00 AM and 10:00 PM</b> only.</li>
                <li>No overnight guests unless pre-approved by management.</li>
                <li>Guests should not create disturbances or violate house rules.</li>
            </ol>
        </section>

        <section class="card">
            <h2>4. Cleanliness & Maintenance</h2>
            <ol>
                <li>Keep personal and common areas clean and tidy.</li>
                <li>Dispose of garbage properly and follow waste segregation guidelines.</li>
                <li>Report damages or maintenance issues immediately.</li>
                <li>Do not tamper with or misuse appliances and fixtures.</li>
            </ol>
        </section>

        <section class="card">
            <h2>5. Room & Property Care</h2>
            <ol>
                <li>No modifications, painting, or drilling without permission.</li>
                <li>Use furniture and appliances responsibly; any damages must be reported.</li>
                <li>No illegal subletting or changing of assigned rooms.</li>
            </ol>
        </section>

        <section class="card">
            <h2>6. Kitchen & Cooking</h2>
            <ol>
                <li>Label and store personal food properly.</li>
                <li>Clean up immediately after using kitchen facilities.</li>
                <li>Turn off appliances when not in use.</li>
                <li>No cooking in bedrooms for fire safety reasons.</li>
            </ol>
        </section>

        <section class="card">
            <h2>7. Safety & Security</h2>
            <ol>
                <li>Lock doors and windows when leaving the apartment.</li>
                <li>No weapons, hazardous materials, or explosives allowed.</li>
                <li>No unauthorized duplication of keys.</li>
                <li>Emergency exits and fire safety equipment must not be tampered with.</li>
            </ol>
        </section>

        <section class="card">
            <h2>8. Prohibited Activities</h2>
            <ol>
                <li>No smoking inside the premises (use designated areas if allowed).</li>
                <li>No pets unless permitted in the lease agreement.</li>
                <li>No excessive drinking or parties that cause disturbances.</li>
                <li>No business operations without prior approval.</li>
            </ol>
        </section>

        <section class="card">
            <h2>9. Moving Out</h2>
            <ol>
                <li>Provide at least __ days’ notice before vacating.</li>
                <li>Return the unit in good condition; cleaning fees may apply.</li>
                <li>Remove all personal belongings; unclaimed items may be disposed of.</li>
            </ol>
        </section>

        <section class="card">
            <h2>10. Violation of Rules</h2>
            <ol>
                <li><b>First violation</b> – Verbal warning</li>
                <li><b>Second violation</b> – Written warning</li>
                <li><b>Repeated or serious offenses</b> may result in eviction without refund.</li>
            </ol>
        </section>
    </main>

    <!-- Footer -->
    <?php include 'footer.php'; ?>


</body>

</html>