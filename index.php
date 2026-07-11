<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlueWave Hotel</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy:       #0a1c3b;
            --navy-deep:  #060f22;
            --navy-mid:   #112d5e;
            --navy-light: #1a3f7a;
            --gold:       #c9a84c;
            --white:      #ffffff;
            --off-white:  #f4f6fb;
            --text-muted: #a8b4cc;
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Jost', sans-serif;
            background: var(--white);
            color: var(--navy);
            overflow-x: hidden;
        }

        /* ── NAVBAR ── */
        nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 60px;
            height: 72px;
            background: var(--navy-deep);
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .nav-logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--white);
            letter-spacing: 1px;
            text-decoration: none;
        }

        .nav-logo span { color: var(--gold); }

        .nav-links {
            display: flex;
            gap: 36px;
            list-style: none;
        }

        .nav-links a {
            font-size: 0.85rem;
            font-weight: 500;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.25s;
        }

        .nav-links a:hover { color: var(--white); }

        .nav-cta {
            background: transparent;
            border: 1px solid var(--gold);
            color: var(--gold);
            padding: 9px 24px;
            border-radius: 2px;
            font-family: 'Jost', sans-serif;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.25s, color 0.25s;
        }

        .nav-cta:hover { background: var(--gold); color: var(--navy-deep); }

        /* ── HERO ── */
        .hero {
            position: relative;
            height: 100vh;
            min-height: 640px;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                linear-gradient(160deg, rgba(6,15,34,0.92) 0%, rgba(10,28,59,0.80) 60%, rgba(17,45,94,0.70) 100%),
                url('https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80&w=2070') center/cover no-repeat;
            text-align: center;
            overflow: hidden;
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 120px;
            background: linear-gradient(to top, var(--white), transparent);
        }

        /* decorative lines */
        .hero::before {
            content: '';
            position: absolute;
            inset: 40px;
            border: 1px solid rgba(255,255,255,0.07);
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 720px;
            padding: 0 24px;
            animation: fadeUp 1s ease both;
        }

        .hero-eyebrow {
            display: inline-block;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 20px;
        }

        .hero-content h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.4rem, 6vw, 4.2rem);
            font-weight: 700;
            color: var(--white);
            line-height: 1.15;
            margin-bottom: 20px;
        }

        .hero-content p {
            font-size: 1.05rem;
            font-weight: 300;
            color: rgba(255,255,255,0.7);
            letter-spacing: 0.5px;
            margin-bottom: 40px;
            line-height: 1.7;
        }

        .btn-primary {
            display: inline-block;
            background: var(--gold);
            color: var(--navy-deep);
            padding: 15px 40px;
            border: none;
            border-radius: 2px;
            font-family: 'Jost', sans-serif;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            text-decoration: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(201,168,76,0.35);
        }

        /* ── WELCOME STRIP ── */
        .welcome {
            background: var(--off-white);
            text-align: center;
            padding: 80px 24px;
        }

        .welcome h2 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.6rem, 3.5vw, 2.4rem);
            color: var(--navy);
            margin-bottom: 18px;
        }

        .welcome p {
            max-width: 600px;
            margin: 0 auto;
            font-size: 1rem;
            line-height: 1.8;
            color: #4a5a75;
            font-weight: 300;
        }

        .divider {
            width: 48px;
            height: 3px;
            background: var(--gold);
            margin: 20px auto 0;
            border-radius: 2px;
        }

        /* ── ROOMS ── */
        .rooms-section {
            padding: 100px 60px;
            background: var(--white);
        }

        .section-header {
            text-align: center;
            margin-bottom: 56px;
        }

        .section-header h2 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.6rem, 3.5vw, 2.4rem);
            color: var(--navy);
            margin-bottom: 10px;
        }

        .room-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 28px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .room-card {
            border-radius: 4px;
            overflow: hidden;
            background: var(--white);
            border: 1px solid #e2e8f2;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .room-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 24px 56px rgba(10,28,59,0.12);
        }

        .room-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            display: block;
        }

        .room-content {
            padding: 28px;
        }

        .room-content h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.25rem;
            color: var(--navy);
            margin-bottom: 10px;
        }

        .room-content p {
            font-size: 0.9rem;
            color: #5a6b85;
            line-height: 1.7;
            margin-bottom: 18px;
        }

        .room-content h4 {
            font-size: 1.1rem;
            color: var(--navy-light);
            font-weight: 700;
            margin-bottom: 18px;
        }

        .btn-outline {
            display: inline-block;
            border: 1.5px solid var(--navy);
            color: var(--navy);
            padding: 10px 26px;
            border-radius: 2px;
            font-family: 'Jost', sans-serif;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            cursor: pointer;
            background: transparent;
            transition: background 0.25s, color 0.25s;
            text-decoration: none;
        }

        .btn-outline:hover {
            background: var(--navy);
            color: var(--white);
        }

        /* ── SERVICES ── */
        .services {
            background: var(--navy);
            padding: 100px 60px;
            text-align: center;
        }

        .services h2 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.6rem, 3.5vw, 2.4rem);
            color: var(--white);
            margin-bottom: 8px;
        }

        .services .divider { background: var(--gold); }

        .service-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
            max-width: 1100px;
            margin: 52px auto 0;
        }

        .service-box {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.10);
            border-radius: 4px;
            padding: 40px 32px;
            transition: background 0.3s, border-color 0.3s;
        }

        .service-box:hover {
            background: rgba(255,255,255,0.09);
            border-color: var(--gold);
        }

        .service-icon {
            font-size: 2rem;
            margin-bottom: 16px;
        }

        .service-box h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.15rem;
            color: var(--white);
            margin-bottom: 12px;
        }

        .service-box p {
            font-size: 0.9rem;
            color: var(--text-muted);
            line-height: 1.7;
        }

        /* ── ABOUT ── */
        .about-section {
            background: var(--off-white);
            padding: 100px 60px;
        }

        .about-inner {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }

        .about-inner h2 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.6rem, 3.5vw, 2.4rem);
            color: var(--navy);
            margin-bottom: 8px;
        }

        .about-inner p {
            font-size: 1rem;
            color: #4a5a75;
            line-height: 1.85;
            margin-top: 20px;
            font-weight: 300;
        }

        /* ── FOOTER ── */
        footer {
            background: var(--navy-deep);
            color: var(--text-muted);
            text-align: center;
            padding: 40px 24px;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        footer strong {
            color: var(--white);
            font-family: 'Playfair Display', serif;
        }

        footer .gold { color: var(--gold); }

        /* ── ANIMATION ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            nav { padding: 0 24px; }
            .nav-links { display: none; }
            .rooms-section, .services, .about-section { padding: 70px 24px; }
        }
        
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav>
    <a href="#" class="nav-logo">Blue<span>Wave</span></a>
    <ul class="nav-links">
        <li><a href="homepage.php">Home</a></li>
        <li><a href="rooms.php">Rooms</a></li>
        <li><a href="homepage.php">Services</a></li>
        <li><a href="#">About</a></li>
        <li><a href="contact.php">Contact</a></li>
         <li><a href="user_complain.php">Complaints</a></li>
    </ul>
    <a href="booking.php" class="nav-cta" style="text-decoration: none;">Book Now</a>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="hero-content">
        <span class="hero-eyebrow">★ &nbsp; Luxury Hospitality Since 2010 &nbsp; ★</span>
        <h1>Welcome To BlueWave Hotel</h1>
        <p>Experience Luxury, Comfort and Modern Hospitality</p>
        <a href="booking.php" class="btn-primary">Book A Room</a>
    </div>
</section>

<!-- WELCOME -->
<section class="welcome">
    <h2>Why Choose BlueWave Hotel?</h2>
    <p>
        BlueWave Hotel offers premium rooms, modern facilities,
        peaceful environment and world-class customer service
        designed for your comfort and relaxation.
    </p>
    <div class="divider"></div>
</section>

<!-- ROOMS -->
<section class="rooms-section">
    <div class="section-header">
        <h2>Our Luxury Rooms</h2>
        <div class="divider"></div>
    </div>

    <div class="room-container">

        <div class="room-card">
            <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?q=80&w=2070" alt="Deluxe Room">
            <div class="room-content">
                <h3>Deluxe Room</h3>
                <p>Elegant deluxe room with modern interior, king-size bed and beautiful environment.</p>
                <h4>$120 / Night</h4>
                <a href="booking.php" class="btn-outline">Book Now</a>
            </div>
        </div>

        <div class="room-card">
            <img src="https://images.unsplash.com/photo-1566665797739-1674de7a421a?q=80&w=2074" alt="Executive Suite">
            <div class="room-content">
                <h3>Executive Suite</h3>
                <p>Premium executive suite designed for comfort, luxury and unforgettable experience.</p>
                <h4>$200 / Night</h4>
                <a href="booking.php" class="btn-outline">Book Now</a>
            </div>
        </div>

        <div class="room-card">
            <img src="https://images.unsplash.com/photo-1590490360182-c33d57733427?q=80&w=2074" alt="Family Room">
            <div class="room-content">
                <h3>Family Room</h3>
                <p>Spacious family room perfect for vacations and family relaxation moments.</p>
                <h4>$180 / Night</h4>
                <a href="booking.php" class="btn-outline">Book Now</a>
            </div>
        </div>

    </div>
</section>

<!-- SERVICES -->
<section class="services">
    <h2>Our Services</h2>
    <div class="divider"></div>

    <div class="service-container">

        <div class="service-box">
            <div class="service-icon">🛏️</div>
            <h3>Luxury Rooms</h3>
            <p>Experience modern luxury rooms designed for comfort and relaxation.</p>
        </div>

        <div class="service-box">
            <div class="service-icon">📶</div>
            <h3>Free Wi-Fi</h3>
            <p>Enjoy fast and secure internet connection throughout the hotel environment.</p>
        </div>

        <div class="service-box">
            <div class="service-icon">🕐</div>
            <h3>24/7 Support</h3>
            <p>Our customer care team is always available to assist you anytime.</p>
        </div>

    </div>
</section>

<!-- ABOUT -->
<section class="about-section">
    <div class="about-inner">
        <h2>About BlueWave Hotel</h2>
        <div class="divider"></div>
        <p>
            BlueWave Hotel is a modern luxury hotel dedicated to providing excellent
            hospitality services, premium accommodation and peaceful relaxation
            experience for all guests.
        </p>
        <p>
            We combine comfort, elegance and world-class customer service to create
            unforgettable memories for families, travelers and business visitors.
        </p>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <p>&copy; 2025 <strong>BlueWave Hotel</strong>. All rights reserved. &nbsp;|&nbsp; Made with <span class="gold">♥</span></p>
</footer>

</body>
</html>
