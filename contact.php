<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 

require_once  "confiq.php";




if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $full_name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    if(isset($_POST['btn'])){ 
         if(empty($full_name) || empty($email) || empty($subject) || empty($message)){
        echo "input are required";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error = "enter correct email";
    } elseif(isset($_POST['name']) || isset($_POST['email']) || isset($_POST['subject']) || isset($_POST['message'])){

    
            $insert = $pdo->prepare("
                INSERT INTO contact_messages (fullname, email, subject, message)
                VALUES (?, ?, ?, ?)
            ");
            $insert->execute([$full_name, $email, $subject, $message]);

            $success = "Dear {$full_name}! your message is succesfully send wait for reply.";
    }
    }

   
    
    
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlueWave | Portal</title>
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
            --primary: #0a1c3b;
            --accent: #c9a84c;
            --bg: #f4f7f6;
        }

        body { font-family: 'Jost', sans-serif; margin: 0; background: var(--bg); }

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
            margin: 0;
            padding: 0;
        }

        .nav-links li {
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
            text-decoration: none;
        }

        .nav-cta:hover { background: var(--gold); color: var(--navy-deep); }

        /* --- HERO SECTION --- */
        .hero {
            background: linear-gradient(rgba(10, 28, 59, 0.8), rgba(10, 28, 59, 0.8)), 
                        url('https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&q=80&w=1200');
            background-size: cover;
            background-position: center;
            height: 250px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: var(--white);
            margin-top: 72px;
        }

        .hero h1 { margin: 0; font-size: 2.5rem; color: var(--accent); }

        /* --- CONTENT WRAPPER --- */
        .container {
            max-width: 1100px;
            margin: -50px auto 50px; /* Pulls content up into the hero */
            padding: 0 20px;
        }

        /* --- SHARED CARD STYLING (From your screenshot) --- */
        .contact-box {
            background: var(--white);
            display: flex;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            min-height: 450px;
        }

        .info-side { background: var(--primary); color: var(--white); padding: 40px; flex: 1; }
        .form-side { padding: 40px; flex: 1.5; }

        .info-side h2 { color: var(--accent); margin-top: 0; }
        
        input, select, textarea {
            width: 100%; padding: 12px; margin-bottom: 20px;
            border: 1px solid #ddd; border-radius: 6px;
        }

        .btn-action {
            background: var(--primary); color: var(--accent);
            border: 2px solid var(--accent); padding: 12px 35px;
            font-weight: bold; border-radius: 6px; cursor: pointer;
            margin-bottom: 20px;
            transition: 0.3s;
        }

        .btn-action:hover { background: var(--accent); color: var(--primary); }

        .alert {
            padding: 12px 15px;
            border-radius: 4px;
            font-size: 0.85rem;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .alert-error   { 
            background: #fdecea; 
            color: #b71c1c; 
            border-left: 3px solid #e53935; 
        }
        .alert-success 
        { background: #e8f5e9; 
        color: #1b5e20; 
        border-left: 3px solid #43a047; }



        /* --- FOOTER --- */
        footer {
            background: var(--primary);
            color: var(--white);
            text-align: center;
            padding: 30px;
            margin-top: 50px;
            border-top: 4px solid var(--accent);
        }

        /* ── RESPONSIVE NAVBAR ── */
        @media (max-width: 768px) {
            nav { padding: 0 24px; }
            .nav-links { display: none; }
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav>
        <a href="homepage.php" class="nav-logo">Blue<span>Wave</span></a>
        <ul class="nav-links">
            <li><a href="homepage.php">Home</a></li>
            <li><a href="rooms.php">Rooms</a></li>
            <li><a href="homepage.php">Services</a></li>
            <li><a href="homepage.php">About</a></li>
            <li><a href="contact.php" class="active">Contact</a></li>
            <li><a href="user_complain.php">Complaints</a></li>
        </ul>
        <a href="booking.php" class="nav-cta">Book Now</a>
    </nav>

    <section class="hero">
        <h1>Support Center</h1>
        <p>How can the BlueWave team help you today?</p>
    </section>



    <div class="container">
        <div class="contact-box">
            <div class="info-side">
                <h2>Get In Touch</h2>
                <p>Have a question for <b>BlueWave</b>? Reach out and we'll get back to you within 24 hours.</p>
                <p><b>Email:</b> info@bluewave.com</p>
                <p><b>Location:</b> Gombe, Nigeria</p>
            </div>

        
            <div class="form-side">
                <form method="post">
                    <input type="text" placeholder="Full Name" name="name">
                    <input type="email" placeholder="Email Address" name="email">
                    <input type="text" placeholder="Subject" name="subject">
                    <textarea rows="5" placeholder="Your Message..." name="message"></textarea>
                    <button type="submit" class="btn-action" name="btn">Send Message</button>
               
                    
                </form>
            </div>
        </div>
    </div>

  
    

</body>
</html>