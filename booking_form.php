<?php

require_once 'confiq.php';

// Guard: room_id required
if(!isset($_GET['room_id'])){
    header('Location: booking.php');
    exit;
}

$room_id = intval($_GET['room_id']);

// Fetch the room
$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ? AND status = 'available'");
$stmt->execute([$room_id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$room){
    header('Location: booking.php');
    exit;
}




$success = false;
$errors  = [];
$nights  = 0;
$total   = 0;

// Handle form submission
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $guest_name  = htmlspecialchars(trim($_POST['guest_name']  ?? ''));
    $guest_email = htmlspecialchars(trim($_POST['guest_email'] ?? ''));
    $guest_phone = htmlspecialchars(trim($_POST['guest_phone'] ?? ''));
    $check_in    = htmlspecialchars(trim($_POST['check_in']    ?? ''));
    $check_out   = htmlspecialchars(trim($_POST['check_out']   ?? ''));
    $guests      = max(1, intval($_POST['guests'] ?? 1));
    $today       = date('Y-m-d');

    // Validate
    if(empty($guest_name))
        $errors[] = "Full name is required.";

    if(empty($guest_email) || !filter_var($guest_email, FILTER_VALIDATE_EMAIL))
        $errors[] = "A valid email address is required.";

    if(empty($guest_phone))
        $errors[] = "Phone number is required.";

    if(empty($check_in))
        $errors[] = "Check-in date is required.";
    elseif($check_in < $today)
        $errors[] = "Check-in date cannot be in the past.";

    if(empty($check_out))
        $errors[] = "Check-out date is required.";
    elseif(!empty($check_in) && $check_out <= $check_in)
        $errors[] = "Check-out must be after check-in.";

    if(empty($errors)){
        $nights = (new DateTime($check_in))->diff(new DateTime($check_out))->days;
        $total  = $nights * floatval($room['price']);

        // Save booking
        $ins = $pdo->prepare("
            INSERT INTO bookingss (room_id, guest_name, guest_email, guest_phone, check_in, check_out, guests, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')
        ");
        $ins->execute([$room_id, $guest_name, $guest_email, $guest_phone, $check_in, $check_out, $guests]);
        $success = true;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Booking – BlueWave Hotel</title>
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
            background: var(--off-white);
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

        /* PAGE HEADER */
        .page-header {
            background:
                linear-gradient(rgba(10,28,59,0.75), rgba(10,28,59,0.75)),
                url('https://images.unsplash.com/photo-1631049307264-da0ec9d70304?q=80&w=2070')
                center/cover;
            height: 240px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
            gap: 10px;
        }

        .page-header span {
            color: var(--gold);
            font-size: 0.85rem;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .page-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
        }

        /* BREADCRUMB */
        .breadcrumb {
            max-width: 1100px;
            margin: 25px auto 0;
            padding: 0 20px;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .breadcrumb a {
            color: var(--gold);
            text-decoration: none;
        }

        /* LAYOUT */
        .booking-layout {
            max-width: 1100px;
            margin: 30px auto 80px;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 35px;
            align-items: start;
        }

        /* FORM CARD */
        .form-card {
            background: white;
            padding: 40px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.07);
        }

        .form-card h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.7rem;
            color: var(--navy);
            margin-bottom: 5px;
        }

        .form-card .subtitle {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 28px;
            padding-bottom: 22px;
            border-bottom: 1px solid #eef0f5;
        }

        .section-label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--gold);
            margin: 25px 0 15px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 7px;
            margin-bottom: 18px;
        }

        .form-group label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--navy);
        }

        .form-group input {
            padding: 13px 15px;
            border: 1.5px solid #dbe2ea;
            outline: none;
            font-family: 'Jost', sans-serif;
            font-size: 0.95rem;
            transition: border-color 0.2s;
            color: var(--navy);
        }

        .form-group input:focus {
            border-color: var(--gold);
        }

        .form-group input::placeholder {
            color: var(--text-muted);
        }

        .btn-confirm {
            width: 100%;
            padding: 16px;
            background: var(--navy);
            color: white;
            border: none;
            font-family: 'Jost', sans-serif;
            font-size: 0.9rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 8px;
        }

        .btn-confirm:hover {
            background: var(--gold);
            color: var(--navy);
        }

        /* ERRORS */
        .error-box {
            background: #fff0f0;
            border-left: 4px solid #c00;
            padding: 14px 18px;
            margin-bottom: 22px;
            color: #900;
        }

        .error-box ul {
            margin: 6px 0 0 16px;
            font-size: 0.88rem;
            line-height: 1.8;
        }

        /* SUCCESS */
        .success-box {
            text-align: center;
            padding: 50px 20px;
        }

        .success-icon {
            width: 70px;
            height: 70px;
            background: var(--gold);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 22px;
            font-size: 2rem;
            color: var(--navy);
        }

        .success-box h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: var(--navy);
            margin-bottom: 12px;
        }

        .success-box p {
            color: var(--text-muted);
            line-height: 1.8;
            margin-bottom: 30px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-home {
            display: inline-block;
            padding: 14px 35px;
            background: var(--navy);
            color: white;
            text-decoration: none;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-size: 0.85rem;
            transition: 0.3s;
        }

        .btn-home:hover {
            background: var(--gold);
            color: var(--navy);
        }

        /* SUMMARY CARD */
        .summary-card {
            background: var(--navy);
            color: white;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            position: sticky;
            top: 90px;
        }

        .room-thumb {
            width: 100%;
            height: 170px;
            background-size: cover;
            background-position: center;
            margin-bottom: 22px;
        }

        .gold-badge {
            display: inline-block;
            background: var(--gold);
            color: var(--navy);
            font-size: 0.68rem;
            font-weight: 700;
            padding: 4px 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
        }

        .summary-card h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            color: var(--gold);
            margin-bottom: 18px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 11px 0;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            font-size: 0.88rem;
        }

        .summary-row .lbl {
            color: var(--text-muted);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .summary-row .val {
            font-weight: 600;
            color: white;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 18px;
            padding-top: 18px;
            border-top: 1px solid var(--gold);
        }

        .total-row .lbl {
            color: var(--gold);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
        }

        .total-row .amount {
            font-family: 'Playfair Display', serif;
            font-size: 1.7rem;
            color: var(--gold);
        }

        .total-note {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 8px;
            text-align: right;
        }

        @media(max-width: 900px){
            .booking-layout {
                grid-template-columns: 1fr;
            }
            .summary-card {
                position: static;
                order: -1;
            }
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
            .form-card { padding: 25px; }
            .form-row { grid-template-columns: 1fr; }
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

<div class="page-header">
    <span>BlueWave Hotel</span>
    <h1>Complete Your Booking</h1>
</div>

<div class="breadcrumb">
    <a href="booking.php">Available Rooms</a> &rsaquo; Booking Details
</div>

<div class="booking-layout">

    <!-- LEFT: FORM -->
    <div class="form-card">

        <?php if($success): ?>

            <div class="success-box">
                <div class="success-icon">✓</div>
                <h2>Booking Received!</h2>
                <p>
                    Thank you! Your reservation for <strong><?= htmlspecialchars($room['room_name']) ?></strong>
                    has been submitted. Our team will reach out to confirm your stay.
                </p>
                <a href="booking.php" class="btn-home">Browse More Rooms</a>
            </div>

        <?php else: ?>

            <h2>Your Details</h2>
            <p class="subtitle">Fill in your information and preferred dates to reserve this suite.</p>

            <?php if(!empty($errors)): ?>
                <div class="error-box">
                    <strong>Please fix the following:</strong>
                    <ul>
                        <?php foreach($errors as $e): ?>
                            <li><?= $e ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <input type="hidden" name="room_id" value="<?= $room_id ?>">

                <!-- DATES -->
                <p class="section-label">Stay Dates</p>

                <div class="form-row">
                    <div class="form-group">
                        <label>Check-In</label>
                        <input
                            type="date"
                            name="check_in"
                            id="check_in"
                            value="<?= htmlspecialchars($_POST['check_in'] ?? '') ?>"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Check-Out</label>
                        <input
                            type="date"
                            name="check_out"
                            id="check_out"
                            value="<?= htmlspecialchars($_POST['check_out'] ?? '') ?>"
                            required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Number of Guests</label>
                    <input
                        type="number"
                        name="guests"
                        min="1"
                        value="<?= htmlspecialchars($_POST['guests'] ?? '1') ?>"
                        required>
                </div>

                <!-- GUEST INFO -->
                <p class="section-label">Guest Information</p>

                <div class="form-group">
                    <label>Full Name</label>
                    <input
                        type="text"
                        name="guest_name"
                        placeholder="e.g. John Doe"
                        value="<?= htmlspecialchars($_POST['guest_name'] ?? '') ?>"
                        required>
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input
                        type="email"
                        name="guest_email"
                        placeholder="e.g. john@email.com"
                        value="<?= htmlspecialchars($_POST['guest_email'] ?? '') ?>"
                        required>
                </div>

                <div class="form-group">
                    <label>Phone Number</label>
                    <input
                        type="tel"
                        name="guest_phone"
                        placeholder="e.g. +234 800 000 0000"
                        value="<?= htmlspecialchars($_POST['guest_phone'] ?? '') ?>"
                        required>
                </div>

                <button type="submit" class="btn-confirm">
                    Confirm Reservation
                </button>

            </form>

        <?php endif; ?>

    </div>

    <!-- RIGHT: ROOM SUMMARY -->
    <div class="summary-card">

        <div class="room-thumb"
             style="background-image: url('<?= htmlspecialchars(str_replace('../', '', $room['image'])) ?>');">
        </div>

        <span class="gold-badge">Your Selection</span>

        <h3><?= htmlspecialchars($room['room_name']) ?></h3>

        <div class="summary-row">
            <span class="lbl">Description</span>
            <span class="val" style="font-size:0.8rem; text-align:right; max-width:55%;">
                <?= htmlspecialchars($room['description']) ?>
            </span>
        </div>

        <div class="summary-row">
            <span class="lbl">Rate per Night</span>
            <span class="val">$<?= number_format($room['price'], 2) ?></span>
        </div>

        <div class="total-row">
            <span class="lbl">Total</span>
            <span class="amount" id="totalDisplay">—</span>
        </div>
        <p class="total-note" id="nightsNote">Select your dates to see total</p>

    </div>

</div>

<script>
    // Min dates
    const today = new Date().toISOString().split('T')[0];
    const checkIn  = document.getElementById('check_in');
    const checkOut = document.getElementById('check_out');

    checkIn.min  = today;
    checkOut.min = today;

    const pricePerNight = <?= floatval($room['price']) ?>;

    function updateTotal(){
        const inVal  = checkIn.value;
        const outVal = checkOut.value;

        if(inVal && outVal && outVal > inVal){
            const d1     = new Date(inVal);
            const d2     = new Date(outVal);
            const nights = Math.round((d2 - d1) / (1000 * 60 * 60 * 24));
            const total  = nights * pricePerNight;

            document.getElementById('totalDisplay').textContent =
                '$' + total.toLocaleString('en-US', {minimumFractionDigits: 2});
            document.getElementById('nightsNote').textContent =
                nights + ' night' + (nights > 1 ? 's' : '') + ' × $' + pricePerNight.toFixed(2);
        } else {
            document.getElementById('totalDisplay').textContent = '—';
            document.getElementById('nightsNote').textContent   = 'Select your dates to see total';
        }
    }

    checkIn.addEventListener('change', function(){
        checkOut.min = this.value;
        if(checkOut.value && checkOut.value <= this.value) checkOut.value = '';
        updateTotal();
    });

    checkOut.addEventListener('change', updateTotal);

    // Run on load if dates were pre-filled (after validation error)
    updateTotal();

    // Hamburger
    document.getElementById('hamburger').addEventListener('click', function(){
        document.getElementById('navLinks').classList.toggle('open');
        
    });

</script>

</body>
</html>