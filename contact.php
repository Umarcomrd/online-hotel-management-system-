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
    <style>
        :root {
            --primary: #0a1c3b;
            --accent: #c9a84c;
            --white: #ffffff;
            --bg: #f4f7f6;
        }

        body { font-family: 'Segoe UI', sans-serif; margin: 0; background: var(--bg); }

        /* --- HEADER (Like homepage.php) --- */
        .main-header {
            background: var(--primary);
            padding: 20px 8%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: var(--white);
            text-decoration: none;
            text-transform: uppercase;
        }

        .logo span { color: var(--accent); }

        .nav-links a {
            color: var(--white);
            text-decoration: none;
            margin-left: 30px;
            font-weight: 500;
            transition: 0.3s;
        }

        .nav-links a:hover, .nav-links a.active {
            color: var(--accent);
        }

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
    </style>
</head>
<body>

    <header class="main-header">
        <a href="homepage.php" class="logo">Blue<span>Wave</span></a>
        <nav class="nav-links">
            <a href="homepage.php">Home</a>
            <a href="booking.php">Bookings</a>
            <a href="user_complain.php">Complaints</a>
            <a href="contact.php" class="active">Contact</a>
            
            
        </nav>
    </header>

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