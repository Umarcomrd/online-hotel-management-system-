<?php
// admin/login.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../confiq.php';


$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Both fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        $stmt = $pdo->prepare("SELECT id, full_name, password FROM officials WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password'])) {
            session_regenerate_id(true); // Prevent session fixation
            $_SESSION['admin_id']   = $admin['id'];
            $_SESSION['admin_name'] = $admin['full_name'];
            header('Location: dashboard.php');
            exit;
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
    <title>Admin Login – BlueWave Hotel</title>
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
            background-color: var(--off-white);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            animation: fadeIn 0.7s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Logo */
        .logo-area {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-area a {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: var(--navy);
            text-decoration: none;
            font-weight: 700;
        }

        .logo-area a span { color: var(--gold); }

        .logo-area .admin-tag {
            display: inline-block;
            margin-top: 8px;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: var(--gold);
            font-weight: 700;
            border: 1px solid var(--gold);
            padding: 4px 14px;
            border-radius: 2px;
        }

        /* Card */
        .card {
            background: var(--white);
            border-radius: 6px;
            box-shadow: 0 4px 30px rgba(10,28,59,.1);
            overflow: hidden;
        }

        .card-top {
            background: var(--navy-deep);
            padding: 28px 36px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .shield-icon {
            width: 46px;
            height: 46px;
            background: rgba(201,168,76,.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .shield-icon svg { width: 22px; height: 22px; fill: var(--gold); }

        .card-top h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            color: var(--white);
            margin-bottom: 3px;
        }

        .card-top p {
            font-size: 0.8rem;
            color: rgba(255,255,255,.55);
        }

        .card-body { padding: 32px 36px; }

        /* Alert */
        .alert {
            padding: 12px 15px;
            border-radius: 4px;
            font-size: 0.85rem;
            margin-bottom: 22px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .alert svg { width: 17px; height: 17px; fill: #e53935; flex-shrink: 0; }
        .alert-error { background: #fdecea; color: #b71c1c; border-left: 3px solid #e53935; }

        /* Form */
        .form-group { margin-bottom: 20px; }

        .form-group label {
            display: block;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--navy);
            margin-bottom: 9px;
        }

        .label-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 9px;
        }

        .label-row label { margin-bottom: 0; }

        .forgot-link {
            font-size: 0.75rem;
            color: var(--text-body);
            text-decoration: none;
            font-weight: 500;
            transition: color .2s;
        }

        .forgot-link:hover { color: var(--gold); }

        .input-wrap { position: relative; }

        .input-wrap input {
            width: 100%;
            padding: 13px 42px 13px 15px;
            border: 1px solid #d0daf0;
            border-radius: 4px;
            outline: none;
            background: var(--white);
            font-family: 'Jost', sans-serif;
            font-size: 0.95rem;
            color: var(--navy);
            transition: border-color .25s, box-shadow .25s;
        }

        .input-wrap input:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(201,168,76,.12);
        }

        .input-wrap input::placeholder { color: #b0bad0; }

        .toggle-pw {
            position: absolute;
            right: 13px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-body);
            display: flex;
            align-items: center;
            transition: color .2s;
        }

        .toggle-pw:hover { color: var(--gold); }
        .toggle-pw svg { width: 18px; height: 18px; }

        /* Plain input (no toggle) */
        .form-group input.plain {
            width: 100%;
            padding: 13px 15px;
            border: 1px solid #d0daf0;
            border-radius: 4px;
            outline: none;
            background: var(--white);
            font-family: 'Jost', sans-serif;
            font-size: 0.95rem;
            color: var(--navy);
            transition: border-color .25s, box-shadow .25s;
        }

        .form-group input.plain:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(201,168,76,.12);
        }

        .form-group input.plain::placeholder { color: #b0bad0; }

        .btn-submit {
            width: 100%;
            padding: 15px;
            background: var(--navy-deep);
            color: var(--white);
            border: none;
            font-family: 'Jost', sans-serif;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            border-radius: 3px;
            transition: .25s;
            margin-top: 6px;
        }

        .btn-submit:hover { background: var(--gold); color: var(--navy-deep); }

        /* Back link */
        .back-row {
            margin-top: 22px;
            text-align: center;
            font-size: 0.82rem;
            color: var(--text-body);
        }

        .back-row a {
            color: var(--navy);
            font-weight: 600;
            text-decoration: none;
            border-bottom: 1px solid var(--gold);
            transition: color .2s;
        }

        .back-row a:hover { color: var(--gold); }

        /* Security notice */
        .security-note {
            margin-top: 24px;
            text-align: center;
            font-size: 0.75rem;
            color: #a0aac0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .security-note svg { width: 13px; height: 13px; fill: #a0aac0; }
    </style>
</head>
<body>

<div class="login-wrapper">

    <div class="logo-area">
        <a href="../index.php">Blue<span>Wave</span></a><br>
        <span class="admin-tag">Admin Access</span>
    </div>

    <div class="card">
        <div class="card-top">
            <div class="shield-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-1 14l-3-3 1.41-1.41L11 12.17l4.59-4.58L17 9l-6 6z"/>
                </svg>
            </div>
            <div>
                <h2>Admin Sign In</h2>
                <p>Restricted area — authorised personnel only</p>
            </div>
        </div>

        <div class="card-body">

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php">

                <div class="form-group">
                    <label>Email Address</label>
                    <input class="plain" type="email" name="email" placeholder="admin@bluewave.com"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required autofocus>
                </div>

                <div class="form-group">
                    <div class="label-row">
                        <label>Password</label>
                    </div>
                    <div class="input-wrap">
                        <input type="password" name="password" id="password" placeholder="Enter your password" required>
                        <button type="button" class="toggle-pw" onclick="togglePw()" aria-label="Toggle password">
                            <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zm0 12.5a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Sign In to Dashboard</button>

            </form>

            <div class="back-row">
                <a href="../admin/register.php">← register here</a>
            </div>

        </div>
    </div>

    <div class="security-note">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M18 8h-1V6A5 5 0 0 0 7 6v2H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V10a2 2 0 0 0-2-2zM12 17a2 2 0 1 1 0-4 2 2 0 0 1 0 4zM9 8V6a3 3 0 0 1 6 0v2H9z"/></svg>
        Secured connection &nbsp;·&nbsp; BlueWave Hotel <?= date('Y') ?>
    </div>

</div>

<script>
    function togglePw() {
        const input = document.getElementById('password');
        const icon  = document.getElementById('eye-icon');
        const show  = input.type === 'password';
        input.type  = show ? 'text' : 'password';
        icon.innerHTML = show
            ? '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-5 0-9.27-3.11-11-7.5a10.47 10.47 0 0 1 2.34-3.79M9.53 9.53A3 3 0 0 0 12 15a3 3 0 0 0 2.47-5.47M19.73 19.73 4.27 4.27" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>'
            : '<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zm0 12.5a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/>';
    }
</script>

</body>
</html>