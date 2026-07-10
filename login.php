<?php
// login.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'confiq.php';

session_start();

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Both fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        $stmt = $pdo->prepare("SELECT id, full_name, password FROM people WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $success = "Welcome back, " . htmlspecialchars($user['full_name']) . "! Redirecting…";
            header("Refresh: 2; url=homepage.php");
        } else {
            $error = "Incorrect email or password.";
        }
    }
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In – BlueWave Hotel</title>
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

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Jost', sans-serif;
            background-color: var(--white);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* HEADER */
        header {
            padding: 25px 8%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: transparent;
            position: absolute;
            width: 100%;
            z-index: 10;
        }

        .nav-logo { font-family: 'Playfair Display', serif; font-size: 1.8rem; color: var(--navy); text-decoration: none; font-weight: 700; }
        .nav-logo span { color: var(--gold); }
        .back-home { text-decoration: none; color: var(--text-body); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; transition: 0.3s; }
        .back-home:hover { color: var(--gold); }

        /* SPLIT LAYOUT */
        .main-container {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            flex-grow: 1;
            min-height: 100vh;
        }

        /* Image on the left — different photo from register for visual variety */
        .image-side {
            background: linear-gradient(rgba(10, 28, 59, 0.45), rgba(10, 28, 59, 0.45)),
                        url('https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?q=80&w=2070') center/cover;
            position: relative;
            overflow: hidden;
        }

        /* Decorative quote overlay on the image */
        .image-quote {
            position: absolute;
            bottom: 60px;
            left: 50px;
            right: 50px;
            color: var(--white);
        }

        .image-quote blockquote {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-style: italic;
            line-height: 1.5;
            margin-bottom: 14px;
            opacity: 0.9;
        }

        .image-quote cite {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--gold);
            font-style: normal;
            font-weight: 600;
        }

        .form-side {
            background: var(--off-white);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 100px 60px 60px;
        }

        .form-box {
            width: 100%;
            max-width: 380px;
            animation: fadeIn 0.8s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .form-box h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: var(--navy);
            margin-bottom: 8px;
        }

        .form-box .tagline {
            font-size: 0.9rem;
            color: var(--text-body);
            margin-bottom: 28px;
        }

        /* ALERTS */
        .alert {
            padding: 12px 15px;
            border-radius: 4px;
            font-size: 0.85rem;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .alert-error   { background: #fdecea; color: #b71c1c; border-left: 3px solid #e53935; }
        .alert-success { background: #e8f5e9; color: #1b5e20; border-left: 3px solid #43a047; }

        /* FORM */
        .form-group { margin-bottom: 20px; }

        .form-group label {
            display: block;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 10px;
            color: var(--navy);
            letter-spacing: 1px;
        }

        .form-group input {
            width: 100%;
            padding: 15px;
            border: 1px solid #d0daf0;
            border-radius: 4px;
            outline: none;
            background: var(--white);
            font-family: 'Jost', sans-serif;
            font-size: 0.95rem;
            color: var(--navy);
            transition: 0.3s;
        }

        .form-group input:focus { border-color: var(--gold); }
        .form-group input::placeholder { color: #b0bad0; }

        /* Forgot password link */
        .label-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .label-row label { margin-bottom: 0; }

        .forgot-link {
            font-size: 0.75rem;
            color: var(--text-body);
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
        }

        .forgot-link:hover { color: var(--gold); }

        .btn-submit {
            width: 100%;
            padding: 16px;
            background: var(--navy-deep);
            color: var(--white);
            border: none;
            font-family: 'Jost', sans-serif;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 6px;
        }

        .btn-submit:hover { background: var(--gold); color: var(--navy-deep); }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 24px 0;
            color: #b0bad0;
            font-size: 0.75rem;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #d0daf0;
        }

        .footer-link {
            margin-top: 20px;
            text-align: center;
            font-size: 0.9rem;
            color: var(--text-body);
        }

        .footer-link a {
            color: var(--navy);
            font-weight: 600;
            text-decoration: none;
            border-bottom: 1px solid var(--gold);
        }

        @media (max-width: 1000px) {
            .image-side { display: none; }
            .main-container { grid-template-columns: 1fr; }
            .nav-logo { color: var(--navy); }
            .form-side { padding: 100px 30px 40px; }
        }
    </style>
</head>
<body>

<header>
    <a href="index.php" class="nav-logo">Blue<span>Wave</span></a>
    <a href="index.php" class="back-home">← Back to Home</a>
</header>

<div class="main-container">

    <div class="image-side">
        <div class="image-quote">
            <blockquote>"Where every arrival feels like coming home."</blockquote>
            <cite>BlueWave Hotel</cite>
        </div>
    </div>

    <div class="form-side">
        <div class="form-box">
            <h2>Welcome Back</h2>
            <p class="tagline">Sign in to manage your reservations.</p>

            <?php if ($error): ?>
                <div class="alert alert-error">⚠ <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">✓ <?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST" action="login.php">

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="email@example.com"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <div class="label-row">
                        <label>Password</label>
                        <a href="reset-password.php" class="forgot-link">Forgot password?</a>
                    </div>
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>

                <button type="submit" class="btn-submit">Sign In</button>
            </form>

            <div class="divider">or</div>

            <p class="footer-link">Don't have an account? <a href="register.php">Create one here</a></p>
        </div>
    </div>

</div>

</body>
</html>