<?php
// register.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'confiq.php';

$success = '';
$error   = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $full_name    = trim($_POST['full_name'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $phone_number = trim($_POST['phone_number'] ?? '');
    $password     = $_POST['password'] ?? '';
    $confirm_pass = $_POST['confirm_password'] ?? '';
    
    
   
    
    if (empty($full_name) || empty($email) || empty($phone_number) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($password !== $confirm_pass) {
        $error = "Passwords do not match.";
    } elseif ($phone_number && !preg_match('/^\+?\d{10,11}$/', $phone_number)) {
        $error = "Please enter a valid phone number (10-11 digits).";
    } else {
        
        $stmt = $pdo->prepare("SELECT id FROM people WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $error = "An account with this email already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $insert = $pdo->prepare("
                INSERT INTO people (full_name, email, phone_number, password)
                VALUES (?, ?, ?, ?)
            ");
            $insert->execute([$full_name, $email, $phone_number, $hashed_password]);
            $success = "Account created successfully: $full_name! You can now sign in.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register – BlueWave Hotel</title>
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

        .image-side {
            background: linear-gradient(rgba(10, 28, 59, 0.4), rgba(10, 28, 59, 0.4)),
                        url('https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=2070') center/cover;
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

        .footer-link {
            margin-top: 25px;
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
    <a href="register.php" class="nav-logo">Blue<span>Wave</span></a>
    
    
</header>

<div class="main-container">
    <div class="image-side"></div>

    <div class="form-side">
        <div class="form-box">
            <h2>Create Account</h2>
            <p class="tagline">Join BlueWave and enjoy a seamless stay.</p>

            <?php if ($error): ?>
                <div class="alert alert-error">⚠ <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">✓ <?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST" action="register.php">

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name" placeholder="John Doe"
                           value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="email@example.com"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="tel" name="phone_number" placeholder="+234 800 000 0000"
                           value="<?= htmlspecialchars($_POST['phone_number'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Minimum 6 characters" required>
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" placeholder="Repeat your password" required>
                </div>

                <button type="submit" class="btn-submit">Create Account</button>
            </form>

            <p class="footer-link">Already have an account? <a href="login.php">Sign in here</a></p>
        </div>
    </div>
</div>

</body>
</html>