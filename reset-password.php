<?php
// reset-password.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'confiq.php';

session_start();

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {

        $error = "Email address is required.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please enter a valid email address.";

    } else {

        $stmt = $pdo->prepare("SELECT id, full_name FROM people WHERE email = ?");
        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {

            // Later you can add token/email reset system here

            $success = "Password reset instructions have been sent to your email.";

        } else {

            $error = "No account found with that email address.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password – BlueWave Hotel</title>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>

        :root {
            --navy:      #0a1c3b;
            --navy-deep: #060f22;
            --gold:      #c9a84c;
            --white:     #ffffff;
            --off-white: #f4f6fb;
            --text-body: #4a5a75;
        }

        *, *::before, *::after {
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:'Jost', sans-serif;
            background:var(--white);
            min-height:100vh;
        }

        header{
            padding:25px 8%;
            display:flex;
            justify-content:space-between;
            align-items:center;
            position:absolute;
            width:100%;
            z-index:10;
        }

        .nav-logo{
            font-family:'Playfair Display', serif;
            font-size:1.8rem;
            color:var(--navy);
            text-decoration:none;
            font-weight:700;
        }

        .nav-logo span{
            color:var(--gold);
        }

        .back-home{
            text-decoration:none;
            color:var(--text-body);
            font-size:0.8rem;
            text-transform:uppercase;
            letter-spacing:1px;
            font-weight:600;
        }

        .main-container{
            display:grid;
            grid-template-columns:1.2fr 1fr;
            min-height:100vh;
        }

        .image-side{
            background:
            linear-gradient(rgba(10,28,59,0.5), rgba(10,28,59,0.5)),
            url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?q=80&w=2070') center/cover;
            position:relative;
        }

        .image-quote{
            position:absolute;
            bottom:60px;
            left:50px;
            right:50px;
            color:white;
        }

        .image-quote blockquote{
            font-family:'Playfair Display', serif;
            font-size:1.5rem;
            line-height:1.6;
            margin-bottom:15px;
        }

        .image-quote cite{
            color:var(--gold);
            text-transform:uppercase;
            letter-spacing:2px;
            font-size:0.75rem;
        }

        .form-side{
            background:var(--off-white);
            display:flex;
            justify-content:center;
            align-items:center;
            padding:100px 60px;
        }

        .form-box{
            width:100%;
            max-width:380px;
        }

        .form-box h2{
            font-family:'Playfair Display', serif;
            font-size:2.4rem;
            color:var(--navy);
            margin-bottom:10px;
        }

        .tagline{
            color:var(--text-body);
            margin-bottom:30px;
            font-size:0.95rem;
        }

        .alert{
            padding:12px 15px;
            border-radius:4px;
            margin-bottom:20px;
            font-size:0.9rem;
        }

        .alert-error{
            background:#fdecea;
            color:#b71c1c;
            border-left:3px solid #e53935;
        }

        .alert-success{
            background:#e8f5e9;
            color:#1b5e20;
            border-left:3px solid #43a047;
        }

        .form-group{
            margin-bottom:20px;
        }

        .form-group label{
            display:block;
            margin-bottom:10px;
            font-size:0.75rem;
            text-transform:uppercase;
            font-weight:700;
            color:var(--navy);
            letter-spacing:1px;
        }

        .form-group input{
            width:100%;
            padding:15px;
            border:1px solid #d0daf0;
            border-radius:4px;
            outline:none;
            font-size:0.95rem;
        }

        .form-group input:focus{
            border-color:var(--gold);
        }

        .btn-submit{
            width:100%;
            padding:16px;
            background:var(--navy-deep);
            color:white;
            border:none;
            cursor:pointer;
            font-weight:700;
            letter-spacing:2px;
            text-transform:uppercase;
            transition:0.3s;
        }

        .btn-submit:hover{
            background:var(--gold);
            color:var(--navy-deep);
        }

        .footer-link{
            margin-top:25px;
            text-align:center;
            color:var(--text-body);
        }

        .footer-link a{
            color:var(--navy);
            text-decoration:none;
            font-weight:600;
        }

        @media(max-width:1000px){

            .image-side{
                display:none;
            }

            .main-container{
                grid-template-columns:1fr;
            }

            .form-side{
                padding:100px 30px 40px;
            }
        }

    </style>
</head>

<body>

<header>
    <a href="index.php" class="nav-logo">Blue<span>Wave</span></a>

    <a href="login.php" class="back-home">← Back to Login</a>
</header>

<div class="main-container">

    <div class="image-side">

        <div class="image-quote">

            <blockquote>
                "Luxury begins with comfort and peace of mind."
            </blockquote>

            <cite>BlueWave Hotel</cite>

        </div>

    </div>

    <div class="form-side">

        <div class="form-box">

            <h2>Reset Password</h2>

            <p class="tagline">
                Enter your email address to reset your password.
            </p>

            <?php if($error): ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if($success): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="form-group">

                    <label>Email Address</label>

                    <input
                        type="email"
                        name="email"
                        placeholder="email@example.com"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        required
                    >

                </div>

                <button type="submit" class="btn-submit">
                    Reset Password
                </button>

            </form>

            <p class="footer-link">
                Remember your password?
                <a href="login.php">Sign In</a>
            </p>

        </div>

    </div>

</div>

</body>
</html>