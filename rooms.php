<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Rooms – BlueWave Hotel</title>
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
            --text-body:  #4a5a75;
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
        .nav-links { display: flex; gap: 36px; list-style: none; }
        .nav-links a {
            font-size: 0.85rem;
            font-weight: 500;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.25s;
        }
        .nav-links a:hover,
        .nav-links a.active { color: var(--white); }
        .nav-links a.active { border-bottom: 2px solid var(--gold); padding-bottom: 2px; }
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

        /* ── PAGE HERO ── */
        .page-hero {
            position: relative;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            background:
                linear-gradient(160deg, rgba(6,15,34,0.93) 0%, rgba(10,28,59,0.82) 100%),
                url('https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?q=80&w=2070') center/cover no-repeat;
            margin-top: 72px;
        }
        .page-hero::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 80px;
            background: linear-gradient(to top, var(--off-white), transparent);
        }
        .page-hero::before {
            content: '';
            position: absolute;
            inset: 30px;
            border: 1px solid rgba(255,255,255,0.07);
            pointer-events: none;
        }
        .page-hero-content {
            position: relative;
            z-index: 1;
            animation: fadeUp 0.9s ease both;
        }
        .page-eyebrow {
            display: inline-block;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 14px;
        }
        .page-hero-content h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 5vw, 3.2rem);
            color: var(--white);
            font-weight: 700;
        }
        .page-hero-content p {
            margin-top: 12px;
            font-size: 1rem;
            color: rgba(255,255,255,0.6);
            font-weight: 300;
            letter-spacing: 0.5px;
        }

        /* ── FILTER BAR ── */
        .filter-bar {
            background: var(--off-white);
            padding: 28px 60px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            border-bottom: 1px solid #e2e8f2;
        }
        .filter-label {
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--navy);
            margin-right: 8px;
        }
        .filter-btn {
            background: transparent;
            border: 1.5px solid #c8d4e8;
            color: var(--text-body);
            padding: 8px 20px;
            border-radius: 2px;
            font-family: 'Jost', sans-serif;
            font-size: 0.82rem;
            font-weight: 500;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.22s;
        }
        .filter-btn:hover,
        .filter-btn.active {
            background: var(--navy);
            border-color: var(--navy);
            color: var(--white);
        }

        /* ── ROOMS GRID ── */
        .rooms-section {
            background: var(--off-white);
            padding: 60px 60px 100px;
        }

        .rooms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 32px;
            max-width: 1240px;
            margin: 0 auto;
        }

        .room-card {
            background: var(--white);
            border-radius: 4px;
            overflow: hidden;
            border: 1px solid #e2e8f2;
            transition: transform 0.3s, box-shadow 0.3s;
            animation: fadeUp 0.6s ease both;
        }
        .room-card:nth-child(2) { animation-delay: 0.1s; }
        .room-card:nth-child(3) { animation-delay: 0.2s; }
        .room-card:nth-child(4) { animation-delay: 0.3s; }
        .room-card:nth-child(5) { animation-delay: 0.4s; }
        .room-card:nth-child(6) { animation-delay: 0.5s; }

        .room-card:hover {
            transform: translateY(-7px);
            box-shadow: 0 28px 60px rgba(10,28,59,0.13);
        }

        .room-img-wrap {
            position: relative;
            overflow: hidden;
        }
        .room-img-wrap img {
            width: 100%;
            height: 240px;
            object-fit: cover;
            display: block;
            transition: transform 0.5s ease;
        }
        .room-card:hover .room-img-wrap img {
            transform: scale(1.05);
        }

        .room-badge {
            position: absolute;
            top: 16px; left: 16px;
            background: var(--navy);
            color: var(--white);
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 5px 12px;
            border-radius: 2px;
        }
        .room-badge.available { background: #1a7a4a; }
        .room-badge.limited   { background: #b05a1a; }

        .room-body { padding: 28px; }

        .room-type {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 8px;
        }

        .room-body h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            color: var(--navy);
            margin-bottom: 10px;
        }

        .room-body p {
            font-size: 0.88rem;
            color: var(--text-body);
            line-height: 1.75;
            margin-bottom: 20px;
        }

        .room-amenities {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 22px;
        }
        .amenity-tag {
            font-size: 0.72rem;
            font-weight: 500;
            letter-spacing: 0.5px;
            color: var(--navy-light);
            background: var(--off-white);
            border: 1px solid #d0daf0;
            padding: 4px 10px;
            border-radius: 2px;
        }

        .room-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 18px;
            border-top: 1px solid #edf0f7;
        }

        .room-price {
            display: flex;
            flex-direction: column;
        }
        .room-price span {
            font-size: 0.7rem;
            color: var(--text-muted);
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .room-price strong {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            color: var(--navy);
        }
        .room-price strong em {
            font-family: 'Jost', sans-serif;
            font-size: 0.8rem;
            font-style: normal;
            color: var(--text-body);
            font-weight: 400;
        }

        .btn-book {
            background: var(--navy);
            color: var(--white);
            border: none;
            padding: 11px 26px;
            border-radius: 2px;
            font-family: 'Jost', sans-serif;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.25s, transform 0.2s;
            text-decoration: none !important;
        }
        .btn-book:hover {
            background: var(--navy-light);
            transform: translateY(-1px);
        }

        /* ── CTA BANNER ── */
        .cta-banner {
            background: var(--navy);
            padding: 80px 60px;
            text-align: center;
        }
        .cta-banner h2 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.6rem, 3.5vw, 2.4rem);
            color: var(--white);
            margin-bottom: 14px;
        }
        .cta-banner p {
            font-size: 1rem;
            color: var(--text-muted);
            font-weight: 300;
            margin-bottom: 36px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        .btn-gold {
            display: inline-block;
            background: var(--gold);
            color: var(--navy-deep);
            padding: 15px 42px;
            border: none;
            border-radius: 2px;
            font-family: 'Jost', sans-serif;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(201,168,76,0.35);
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
        footer strong { color: var(--white); font-family: 'Playfair Display', serif; }
        footer .gold { color: var(--gold); }

        /* ── ANIMATION ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            nav { padding: 0 24px; }
            .nav-links { display: none; }
            .filter-bar { padding: 20px 24px; }
            .rooms-section { padding: 40px 24px 80px; }
            .rooms-grid { grid-template-columns: 1fr; }
            .cta-banner { padding: 60px 24px; }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav>
    <a href="homepage.php" class="nav-logo">Blue<span>Wave</span></a>
    <ul class="nav-links">
        <li><a href="homepage.php">Home</a></li>
        <li><a href="rooms.php" class="active">Rooms</a></li>
        <li><a href="homepage.php">Services</a></li>
        <li><a href="homepage.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
         <li><a href="user_complain.php">Complaints</a></li>
    </ul>
    <a href="booking.php" class="nav-cta">Book Now</a>
</nav>

<!-- PAGE HERO -->
<section class="page-hero">
    <div class="page-hero-content">
        <span class="page-eyebrow">★ &nbsp; Comfort Meets Elegance &nbsp; ★</span>
        <h1>Our Luxury Rooms</h1>
        <p>Choose from our carefully curated selection of premium rooms and suites</p>
    </div>
</section>

<!-- FILTER BAR -->
<div class="filter-bar">
    <span class="filter-label">Filter:</span>
    <button class="filter-btn active" onclick="filterRooms('all', this)">All Rooms</button>
    <button class="filter-btn" onclick="filterRooms('standard', this)">Standard</button>
    <button class="filter-btn" onclick="filterRooms('deluxe', this)">Deluxe</button>
    <button class="filter-btn" onclick="filterRooms('suite', this)">Suite</button>
    <button class="filter-btn" onclick="filterRooms('family', this)">Family</button>
</div>

<!-- ROOMS GRID -->
<section class="rooms-section">
    <div class="rooms-grid" id="roomsGrid">

        <!-- Standard Room -->
        <div class="room-card" data-type="standard">
            <div class="room-img-wrap">
                <img src="https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?q=80&w=2070" alt="Standard Room">
                <span class="room-badge available">Available</span>
            </div>
            <div class="room-body">
                <div class="room-type">Standard</div>
                <h3>Classic Standard Room</h3>
                <p>A cozy and well-furnished room perfect for solo travelers or short business trips with all essential amenities.</p>
                <div class="room-amenities">
                    <span class="amenity-tag">🛏 Queen Bed</span>
                    <span class="amenity-tag">📶 Free Wi-Fi</span>
                    <span class="amenity-tag">❄️ Air Conditioning</span>
                    <span class="amenity-tag">📺 Smart TV</span>
                </div>
                <div class="room-footer">
                    <div class="room-price">
                        <span>From</span>
                        <strong>$80 <em>/ night</em></strong>
                    </div>
                    <a href="booking.php" class="btn-book">Book Now</a>
                </div>
            </div>
        </div>

        <!-- Deluxe Room -->
        <div class="room-card" data-type="deluxe">
            <div class="room-img-wrap">
                <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?q=80&w=2070" alt="Deluxe Room">
                <span class="room-badge available">Available</span>
            </div>
            <div class="room-body">
                <div class="room-type">Deluxe</div>
                <h3>Deluxe Room</h3>
                <p>Elegant deluxe room with modern interior, king-size bed and a beautiful view of the hotel surroundings.</p>
                <div class="room-amenities">
                    <span class="amenity-tag">🛏 King Bed</span>
                    <span class="amenity-tag">📶 Free Wi-Fi</span>
                    <span class="amenity-tag">🛁 Bathtub</span>
                    <span class="amenity-tag">🍽 Breakfast</span>
                </div>
                <div class="room-footer">
                    <div class="room-price">
                        <span>From</span>
                        <strong>$120 <em>/ night</em></strong>
                    </div>
                    <a href="booking.php" class="btn-book">Book Now</a>
                </div>
            </div>
        </div>

        <!-- Executive Suite -->
        <div class="room-card" data-type="suite">
            <div class="room-img-wrap">
                <img src="https://images.unsplash.com/photo-1566665797739-1674de7a421a?q=80&w=2074" alt="Executive Suite">
                <span class="room-badge limited">Limited</span>
            </div>
            <div class="room-body">
                <div class="room-type">Suite</div>
                <h3>Executive Suite</h3>
                <p>Premium executive suite designed for comfort, luxury and an unforgettable business or leisure experience.</p>
                <div class="room-amenities">
                    <span class="amenity-tag">🛏 King Bed</span>
                    <span class="amenity-tag">🛋 Living Area</span>
                    <span class="amenity-tag">🍾 Mini Bar</span>
                    <span class="amenity-tag">🧖 Spa Access</span>
                </div>
                <div class="room-footer">
                    <div class="room-price">
                        <span>From</span>
                        <strong>$200 <em>/ night</em></strong>
                    </div>
                    <a href="booking.php" class="btn-book">Book Now</a>
                </div>
            </div>
        </div>

        <!-- Family Room -->
        <div class="room-card" data-type="family">
            <div class="room-img-wrap">
                <img src="https://images.unsplash.com/photo-1590490360182-c33d57733427?q=80&w=2074" alt="Family Room">
                <span class="room-badge available">Available</span>
            </div>
            <div class="room-body">
                <div class="room-type">Family</div>
                <h3>Family Room</h3>
                <p>Spacious family room with multiple beds, perfect for vacations and unforgettable family relaxation moments.</p>
                <div class="room-amenities">
                    <span class="amenity-tag">🛏 2 Queen Beds</span>
                    <span class="amenity-tag">📶 Free Wi-Fi</span>
                    <span class="amenity-tag">🧸 Kids Corner</span>
                    <span class="amenity-tag">🍳 Kitchenette</span>
                </div>
                <div class="room-footer">
                    <div class="room-price">
                        <span>From</span>
                        <strong>$180 <em>/ night</em></strong>
                    </div>
                    <a href="booking.php" class="btn-book">Book Now</a>
                </div>
            </div>
        </div>

        <!-- Presidential Suite -->
        <div class="room-card" data-type="suite">
            <div class="room-img-wrap">
                <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?q=80&w=2070" alt="Presidential Suite">
                <span class="room-badge limited">Limited</span>
            </div>
            <div class="room-body">
                <div class="room-type">Suite</div>
                <h3>Presidential Suite</h3>
                <p>The pinnacle of luxury. A breathtaking suite with panoramic views, private dining and butler service.</p>
                <div class="room-amenities">
                    <span class="amenity-tag">🛏 Super King</span>
                    <span class="amenity-tag">🌅 Panoramic View</span>
                    <span class="amenity-tag">🫅 Butler Service</span>
                    <span class="amenity-tag">🍾 Mini Bar</span>
                </div>
                <div class="room-footer">
                    <div class="room-price">
                        <span>From</span>
                        <strong>$450 <em>/ night</em></strong>
                    </div>
                    <a href="booking.php" class="btn-book">Book Now</a>
                </div>
            </div>
        </div>

        <!-- Deluxe Twin -->
        <div class="room-card" data-type="deluxe">
            <div class="room-img-wrap">
                <img src="https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?q=80&w=2071" alt="Deluxe Twin Room">
                <span class="room-badge available">Available</span>
            </div>
            <div class="room-body">
                <div class="room-type">Deluxe</div>
                <h3>Deluxe Twin Room</h3>
                <p>Beautifully designed twin room ideal for colleagues or friends travelling together in style and comfort.</p>
                <div class="room-amenities">
                    <span class="amenity-tag">🛏 2 Single Beds</span>
                    <span class="amenity-tag">📶 Free Wi-Fi</span>
                    <span class="amenity-tag">❄️ Air Conditioning</span>
                    <span class="amenity-tag">🍽 Breakfast</span>
                </div>
                <div class="room-footer">
                    <div class="room-price">
                        <span>From</span>
                        <strong>$140 <em>/ night</em></strong>
                    </div>
                    <a href="booking.php" class="btn-book">Book Now</a>
                </div>
            </div>
        </div>

        <!-- Honeymoon Suite -->
        <div class="room-card" data-type="suite">
            <div class="room-img-wrap">
                <img src="https://images.unsplash.com/photo-1602002418082-a4443e081dd1?q=80&w=2074" alt="Honeymoon Suite">
                <span class="room-badge limited">Limited</span>
            </div>
            <div class="room-body">
                <div class="room-type">Suite</div>
                <h3>Honeymoon Suite</h3>
                <p>A romantic haven crafted for couples — rose petal turndown, jacuzzi, and private balcony with garden views.</p>
                <div class="room-amenities">
                    <span class="amenity-tag">🛏 King Bed</span>
                    <span class="amenity-tag">🛁 Jacuzzi</span>
                    <span class="amenity-tag">🌹 Romantic Setup</span>
                    <span class="amenity-tag">🍾 Champagne</span>
                </div>
                <div class="room-footer">
                    <div class="room-price">
                        <span>From</span>
                        <strong>$280 <em>/ night</em></strong>
                    </div>
                    <a href="booking.php" class="btn-book">Book Now</a>
                </div>
            </div>
        </div>

        <!-- Superior Room -->
        <div class="room-card" data-type="standard">
            <div class="room-img-wrap">
                <img src="https://images.unsplash.com/photo-1578683010236-d716f9a3f461?q=80&w=2070" alt="Superior Room">
                <span class="room-badge available">Available</span>
            </div>
            <div class="room-body">
                <div class="room-type">Standard</div>
                <h3>Superior Room</h3>
                <p>A step above standard, this room features upgraded furnishings, a work desk and premium bedding for added comfort.</p>
                <div class="room-amenities">
                    <span class="amenity-tag">🛏 Queen Bed</span>
                    <span class="amenity-tag">💼 Work Desk</span>
                    <span class="amenity-tag">📶 Free Wi-Fi</span>
                    <span class="amenity-tag">☕ Coffee Maker</span>
                </div>
                <div class="room-footer">
                    <div class="room-price">
                        <span>From</span>
                        <strong>$100 <em>/ night</em></strong>
                    </div>
                    <a href="booking.php" class="btn-book">Book Now</a>
                </div>
            </div>
        </div>

        <!-- Penthouse Suite -->
        <div class="room-card" data-type="suite">
            <div class="room-img-wrap">
                <img src="https://images.unsplash.com/photo-1560185007-cde436f6a4d0?q=80&w=2070" alt="Penthouse Suite">
                <span class="room-badge limited">Limited</span>
            </div>
            <div class="room-body">
                <div class="room-type">Suite</div>
                <h3>Penthouse Suite</h3>
                <p>Perched at the top floor with floor-to-ceiling windows, a private terrace and an exclusive lounge area.</p>
                <div class="room-amenities">
                    <span class="amenity-tag">🛏 Super King</span>
                    <span class="amenity-tag">🌆 City View</span>
                    <span class="amenity-tag">🏊 Pool Access</span>
                    <span class="amenity-tag">🫅 Butler Service</span>
                </div>
                <div class="room-footer">
                    <div class="room-price">
                        <span>From</span>
                        <strong>$550 <em>/ night</em></strong>
                    </div>
                    <a href="booking.php" class="btn-book">Book Now</a>
                </div>
            </div>
        </div>

        <!-- Junior Suite -->
        <div class="room-card" data-type="suite">
            <div class="room-img-wrap">
                <img src="https://images.unsplash.com/photo-1611892440504-42a792e24d32?q=80&w=2070" alt="Junior Suite">
                <span class="room-badge available">Available</span>
            </div>
            <div class="room-body">
                <div class="room-type">Suite</div>
                <h3>Junior Suite</h3>
                <p>A spacious suite with a separate sitting area, perfect for guests who want extra space without full suite pricing.</p>
                <div class="room-amenities">
                    <span class="amenity-tag">🛏 King Bed</span>
                    <span class="amenity-tag">🛋 Sitting Area</span>
                    <span class="amenity-tag">🧖 Spa Access</span>
                    <span class="amenity-tag">🍽 Breakfast</span>
                </div>
                <div class="room-footer">
                    <div class="room-price">
                        <span>From</span>
                        <strong>$160 <em>/ night</em></strong>
                    </div>
                    <a href="booking.php" class="btn-book">Book Now</a>
                </div>
            </div>
        </div>

        <!-- Garden View Room -->
        <div class="room-card" data-type="deluxe">
            <div class="room-img-wrap">
                <img src="https://images.unsplash.com/photo-1540518614846-7eded433c457?q=80&w=2057" alt="Garden View Room">
                <span class="room-badge available">Available</span>
            </div>
            <div class="room-body">
                <div class="room-type">Deluxe</div>
                <h3>Garden View Room</h3>
                <p>Wake up to serene garden views in this beautifully decorated room with natural light and calming ambiance.</p>
                <div class="room-amenities">
                    <span class="amenity-tag">🛏 King Bed</span>
                    <span class="amenity-tag">🌿 Garden View</span>
                    <span class="amenity-tag">🚿 Rain Shower</span>
                    <span class="amenity-tag">📶 Free Wi-Fi</span>
                </div>
                <div class="room-footer">
                    <div class="room-price">
                        <span>From</span>
                        <strong>$130 <em>/ night</em></strong>
                    </div>
                    <a href="booking.php" class="btn-book">Book Now</a>
                </div>
            </div>
        </div>

        <!-- Family Suite -->
        <div class="room-card" data-type="family">
            <div class="room-img-wrap">
                <img src="https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?q=80&w=2070" alt="Family Suite">
                <span class="room-badge available">Available</span>
            </div>
            <div class="room-body">
                <div class="room-type">Family</div>
                <h3>Family Suite</h3>
                <p>A luxurious suite designed for families with a separate kids bedroom, living area and all the space you need.</p>
                <div class="room-amenities">
                    <span class="amenity-tag">🛏 2 Bedrooms</span>
                    <span class="amenity-tag">🧸 Kids Room</span>
                    <span class="amenity-tag">🍳 Kitchenette</span>
                    <span class="amenity-tag">🏊 Pool Access</span>
                </div>
                <div class="room-footer">
                    <div class="room-price">
                        <span>From</span>
                        <strong>$250 <em>/ night</em></strong>
                    </div>
                    <a href="booking.php" class="btn-book">Book Now</a>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- CTA BANNER -->
<section class="cta-banner">
    <h2>Ready For An Unforgettable Stay?</h2>
    <p>Book your preferred room today and enjoy world-class hospitality at BlueWave Hotel.</p>
    <a href="booking.php" class="btn-gold">Make A Reservation</a>
</section>

<!-- FOOTER -->
<footer>
    <p>&copy; 2025 <strong>BlueWave Hotel</strong>. All rights reserved. &nbsp;|&nbsp; Made with <span class="gold">♥</span></p>
</footer>

<script>
    function filterRooms(type, btn) {
        // Update active button
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        // Filter cards
        const cards = document.querySelectorAll('.room-card');
        cards.forEach(card => {
            if (type === 'all' || card.dataset.type === type) {
                card.style.display = 'block';
                card.style.animation = 'none';
                card.offsetHeight; // reflow
                card.style.animation = 'fadeUp 0.5s ease both';
            } else {
                card.style.display = 'none';
            }
        });
    }
</script>

</body>
</html>
