<?php

require_once 'confiq.php';


$available_rooms = [];
$searched = false;

if(isset($_GET['check_availability'])){
    $searched = true;
    $stmt = $pdo->query("SELECT * FROM rooms WHERE status = 'available'");
    $available_rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Stay – BlueWave Hotel</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>

        :root {
            --navy:       #0a1c3b;
            --navy-deep:  #060f22;
            --gold:       #c9a84c;
            --white:      #ffffff;
            --off-white:  #f4f6fb;
            --text-muted: #a8b4cc;
            --text-body:  #4a5a75;
        }


        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: 'Jost', sans-serif;
            background-color: var(--off-white);
            color: var(--text-body);
        }

        /* NAV */
        nav {
            background: var(--navy-deep);
            padding: 15px 8%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            color: var(--white);
            text-decoration: none;
            font-weight: 700;
        }

        .nav-logo span { color: var(--gold); }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 30px;
        }

        .nav-links li a {
            text-decoration: none;
            color: var(--white);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            transition: 0.3s;
        }

        .nav-links li a:hover { color: var(--gold); }

        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            background: none;
            border: none;
            padding: 5px;
        }

        .hamburger span {
            display: block;
            width: 25px;
            height: 2px;
            background: var(--white);
        }

        /* HERO */
        .booking-header {
            background:
                linear-gradient(rgba(10,28,59,0.72), rgba(10,28,59,0.72)),
                url('https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80&w=2070')
                center/cover;
            height: 380px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
            gap: 20px;
        }

        .booking-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
        }

        .booking-header p {
            color: rgba(255,255,255,0.7);
            font-size: 1rem;
            max-width: 500px;
        }

        .btn-check {
            margin-top: 10px;
            background: var(--gold);
            color: var(--navy-deep);
            border: none;
            padding: 16px 45px;
            font-family: 'Jost', sans-serif;
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-check:hover {
            background: var(--white);
            color: var(--navy-deep);
        }

        /* ROOMS SECTION */
        .rooms-section {
            max-width: 1200px;
            margin: 60px auto 80px;
            padding: 0 20px;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: var(--navy);
            margin-bottom: 8px;
        }

        .section-subtitle {
            color: var(--text-muted);
            margin-bottom: 35px;
            font-size: 0.95rem;
        }

        /* 3-column grid */
        .rooms-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 28px;
        }

        .room-card {
            background: white;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .room-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.13);
        }

        .room-img {
            width: 100%;
            height: 200px;
            background-size: cover;
            background-position: center;
        }

        .room-info {
            padding: 22px;
        }

        .room-info h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.25rem;
            color: var(--navy);
            margin-bottom: 10px;
        }

        .room-info p {
            font-size: 0.88rem;
            line-height: 1.7;
            color: var(--text-body);
            margin-bottom: 18px;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 3;
            line-clamp: 3;
            overflow: hidden;
        }

        .room-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #eef0f5;
            padding-top: 15px;
        }

        .price-tag {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--navy);
        }

        .price-tag span {
            font-size: 0.8rem;
            font-weight: 400;
            color: var(--text-muted);
        }

        .btn-book-room {
            padding: 10px 18px;
            background: var(--navy);
            color: white;
            border: none;
            cursor: pointer;
            font-family: 'Jost', sans-serif;
            font-weight: 600;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-decoration: none;
            display: inline-block;
            transition: 0.3s;
        }

        .btn-book-room:hover {
            background: var(--gold);
            color: var(--navy);
        }

        /* STATES */
        .state-message {
            grid-column: 1 / -1;
            text-align: center;
            padding: 70px 20px;
            color: var(--text-muted);
        }

        .state-message .icon { font-size: 3rem; margin-bottom: 15px; }

        .state-message h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            color: var(--navy);
            margin-bottom: 8px;
        }

        @media(max-width: 992px){
            .rooms-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media(max-width: 768px){
            .nav-links {
                display: none;
                flex-direction: column;
                position: absolute;
                top: 60px; left: 0;
                width: 100%;
                background: var(--navy-deep);
                padding: 20px 8%;
                gap: 15px;
                z-index: 999;
            }
            .nav-links.open { display: flex; }
            .hamburger { display: flex; }
            .booking-header h1 { font-size: 2rem; }
            .rooms-grid { grid-template-columns: 1fr; }
        }

    </style>
</head>
<body>

<nav>
    <a href="index.php" class="nav-logo">Blue<span>Wave</span></a>
    <ul class="nav-links" id="navLinks">
        <li><a href="homepage.php">Home</a></li>
        <li><a href="rooms.php">Rooms</a></li>
        <li><a href="homepage.php">Services</a></li>
        <li><a href="homepage.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="user_complain.php">Complaints</a></li>
    </ul>
    <button class="hamburger" id="hamburger" aria-label="Toggle menu">
        <span></span><span></span><span></span>
    </button>
</nav>

<header class="booking-header">
    <h1>Find Your Sanctuary</h1>
    <p>Browse our available rooms and pick the perfect suite for your stay.</p>
    <form method="GET" action="">
        <button type="submit" name="check_availability" value="1" class="btn-check">
            Check Availability
        </button>
    </form>
</header>

<section class="rooms-section">

    <div class="rooms-grid">

    <?php if(!$searched): ?>

        <div class="state-message">
            <div class="icon">🏨</div>
            <h3>Ready to find your room?</h3>
            <p>Click "Check Availability" above to browse all available rooms.</p>
        </div>

    <?php elseif(!empty($available_rooms)): ?>

        <?php foreach($available_rooms as $room): ?>

            <div class="room-card">

                <div class="room-img"
                     style="background-image: url('<?= htmlspecialchars(str_replace('../', '', $room['image'])) ?>');">
                </div>

                <div class="room-info">

                    <h3><?= htmlspecialchars($room['room_name']) ?></h3>

                    <p><?= htmlspecialchars($room['description']) ?></p>

                    <div class="room-footer">

                        <div class="price-tag">
                            $<?= number_format($room['price'], 2) ?>
                            <span>/ night</span>
                        </div>

                        <a href="booking_form.php?room_id=<?= $room['id'] ?>" class="btn-book-room">
                            Select Suite
                        </a>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    <?php else: ?>

        <div class="state-message">
            <div class="icon">😔</div>
            <h3>No Rooms Available</h3>
            <p>All rooms are currently booked or under maintenance. Please check back soon.</p>
        </div>

    <?php endif; ?>

    </div>

</section>

<script>
    document.getElementById('hamburger').addEventListener('click', function(){
        document.getElementById('navLinks').classList.toggle('open');
    });
</script>

</body>
</html>