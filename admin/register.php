<?php
// admin/register.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../confiq.php';

$error   = '';
$success = '';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name    = trim($_POST['full_name']    ?? '');
    $email        = trim($_POST['email']        ?? '');
    $phone_number = trim($_POST['phone_number'] ?? '');
    $password     = $_POST['password']          ?? '';
    $confirm_pass = $_POST['confirm_password']  ?? '';
    $admin_code   = trim($_POST['admin_code']   ?? '');

    
    // Secret registration code — change this to whatever you want





    
    $valid_code = 'BLUEWAVE2024';
    

    if (empty($full_name) || empty($email) || empty($phone_number) || empty($password) || empty($admin_code)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($password !== $confirm_pass) {
        $error = "Passwords do not match.";

    } elseif ($admin_code !== $valid_code) {
        $error = "Invalid admin registration code.";
    } else {

        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM officials WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $error = "An admin account with this email already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            $insert = $pdo->prepare("
                INSERT INTO officials (full_name, email, phone_number, password, created_at)
                VALUES (?, ?, ?, ?, NOW())
            ");
            $insert->execute([$full_name, $email, $phone_number, $hashed_password]);

            $success = "Admin account created successfully for {$full_name}! You can now sign in.";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Register – BlueWave Hotel</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy:      #0a1c3b;
            --navy-deep: #060f22;
            --gold:      #c9a84c;
            --white:     #ffffff;
            --off-white: #f4f6fb;
            --text-body: #4a5a75;
            --border:    #d0daf0;
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

        .register-wrapper {
            width: 100%;
            max-width: 480px;
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

        .card-top-icon {
            width: 46px;
            height: 46px;
            background: rgba(201,168,76,.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .card-top-icon svg { width: 22px; height: 22px; fill: var(--gold); }

        .card-top h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            color: var(--white);
            margin-bottom: 3px;
        }

        .card-top p { font-size: 0.8rem; color: rgba(255,255,255,.55); }

        .card-body { padding: 32px 36px; }

        /* Alerts */
        .alert {
            padding: 12px 15px;
            border-radius: 4px;
            font-size: 0.85rem;
            margin-bottom: 22px;
            font-weight: 500;
            display: flex;
            align-items: flex-start;
            gap: 9px;
        }

        .alert svg { width: 17px; height: 17px; flex-shrink: 0; margin-top: 1px; }
        .alert-error   { background: #fdecea; color: #b71c1c; border-left: 3px solid #e53935; }
        .alert-success { background: #e8f5e9; color: #1b5e20; border-left: 3px solid #43a047; }
        .alert-error svg   { fill: #e53935; }
        .alert-success svg { fill: #43a047; }

        /* Form grid */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px 24px;
        }

        .form-grid .full { grid-column: 1 / -1; }

        .form-group { display: flex; flex-direction: column; }

        .form-group label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--navy);
            margin-bottom: 9px;
        }

        .form-group input {
            padding: 13px 15px;
            border: 1px solid var(--border);
            border-radius: 4px;
            outline: none;
            background: var(--white);
            font-family: 'Jost', sans-serif;
            font-size: 0.95rem;
            color: var(--navy);
            transition: border-color .25s, box-shadow .25s;
        }

        .form-group input:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(201,168,76,.12);
        }

        .form-group input::placeholder { color: #b0bad0; }

        /* Password toggle */
        .input-wrap { position: relative; }

        .input-wrap input {
            width: 100%;
            padding: 13px 42px 13px 15px;
            border: 1px solid var(--border);
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

        /* Admin code field — highlighted */
        .code-group {
            background: #fdf9ef;
            border: 1px solid rgba(201,168,76,.35);
            border-radius: 6px;
            padding: 16px;
            margin-top: 4px;
        }

        .code-group label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--gold);
            margin-bottom: 9px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .code-group label svg { width: 14px; height: 14px; fill: var(--gold); }

        .code-group input {
            width: 100%;
            padding: 13px 15px;
            border: 1px solid rgba(201,168,76,.4);
            border-radius: 4px;
            outline: none;
            background: var(--white);
            font-family: 'Jost', sans-serif;
            font-size: 0.95rem;
            color: var(--navy);
            letter-spacing: 3px;
            font-weight: 600;
            transition: border-color .25s, box-shadow .25s;
        }

        .code-group input:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(201,168,76,.12);
        }

        .code-group input::placeholder { letter-spacing: 1px; font-weight: 400; color: #b0bad0; }

        .code-hint {
            margin-top: 8px;
            font-size: 0.75rem;
            color: var(--text-body);
        }

        /* Strength meter */
        .strength-meter { display: flex; gap: 5px; margin-top: 10px; }
        .strength-bar { flex: 1; height: 3px; border-radius: 2px; background: var(--border); transition: background .3s; }
        .strength-label { font-size: 0.72rem; color: var(--text-body); margin-top: 6px; }

        /* Submit */
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
            margin-top: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit svg { width: 16px; height: 16px; fill: currentColor; }
        .btn-submit:hover { background: var(--gold); color: var(--navy-deep); }

        /* Footer */
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

        @media (max-width: 560px) {
            .form-grid { grid-template-columns: 1fr; }
            .form-grid .full { grid-column: 1; }
            .card-body { padding: 24px 20px; }
            .card-top  { padding: 22px 20px; }
        }
    </style>
</head>
<body>

<div class="register-wrapper">

    <div class="logo-area">
        <a href="../index.php">Blue<span>Wave</span></a><br>
        <span class="admin-tag">Admin Registration</span>
    </div>

    <div class="card">
        <div class="card-top">
            <div class="card-top-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-1 14l-3-3 1.41-1.41L11 12.17l4.59-4.58L17 9l-6 6z"/>
                </svg>
            </div>
            <div>
                <h2>Create Admin Account</h2>
                <p>Restricted — requires a valid admin registration code</p>
            </div>
        </div>

        <div class="card-body">

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M9 16.17 4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <?php if (!$success): ?>
            <form method="POST" action="register.php">
                <div class="form-grid">

                    <!-- Full Name -->
                    <div class="form-group full">
                        <label>Full Name</label>
                        <input type="text" name="full_name" placeholder="John Doe"
                               value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>" required>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" placeholder="admin@bluewave.com"
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    </div>

                    <!-- Phone -->
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="tel" name="phone_number" placeholder="+234 800 000 0000"
                               value="<?= htmlspecialchars($_POST['phone_number'] ?? '') ?>" required>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label>Password</label>
                        <div class="input-wrap">
                            <input type="password" name="password" id="password"
                                   placeholder="Min. 6 characters" required
                                   oninput="checkStrength(this.value)">
                            <button type="button" class="toggle-pw" onclick="togglePw('password','eye1')" aria-label="Toggle">
                                <svg id="eye1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zm0 12.5a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/>
                                </svg>
                            </button>
                        </div>
                        <div class="strength-meter">
                            <div class="strength-bar" id="bar1"></div>
                            <div class="strength-bar" id="bar2"></div>
                            <div class="strength-bar" id="bar3"></div>
                            <div class="strength-bar" id="bar4"></div>
                        </div>
                        <div class="strength-label" id="strength-label"></div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <div class="input-wrap">
                            <input type="password" name="confirm_password" id="confirm_password"
                                   placeholder="Repeat password" required>
                            <button type="button" class="toggle-pw" onclick="togglePw('confirm_password','eye2')" aria-label="Toggle">
                                <svg id="eye2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zm0 12.5a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Admin Code -->
                    <div class="form-group full">
                        <div class="code-group">
                            <label>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M18 8h-1V6A5 5 0 0 0 7 6v2H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V10a2 2 0 0 0-2-2zM12 17a2 2 0 1 1 0-4 2 2 0 0 1 0 4zM9 8V6a3 3 0 0 1 6 0v2H9z"/></svg>
                                Admin Registration Code
                            </label>
                            <input type="text" name="admin_code" placeholder="Enter secret code"
                                   value="<?= htmlspecialchars($_POST['admin_code'] ?? '') ?>" required autocomplete="off">
                            <p class="code-hint">⚠ This code is required to create an admin account. Contact your system administrator if you don't have it.</p>
                        </div>
                    </div>

                </div>

                <button type="submit" class="btn-submit">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M9 16.17 4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    Create Admin Account
                </button>
            </form>
            <?php endif; ?>

            <div class="back-row" style="margin-top: <?= $success ? '10px' : '22px' ?>;">
                Already have an account? <a href="login.php">Sign in here</a>
            </div>

        </div>
    </div>

    <div class="security-note">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M18 8h-1V6A5 5 0 0 0 7 6v2H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V10a2 2 0 0 0-2-2zM12 17a2 2 0 1 1 0-4 2 2 0 0 1 0 4zM9 8V6a3 3 0 0 1 6 0v2H9z"/></svg>
        Secured connection &nbsp;·&nbsp; BlueWave Hotel <?= date('Y') ?>
    </div>

</div>

<script>
    function togglePw(fieldId, iconId) {
        const input = document.getElementById(fieldId);
        const icon  = document.getElementById(iconId);
        const show  = input.type === 'password';
        input.type  = show ? 'text' : 'password';
        icon.innerHTML = show
            ? '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-5 0-9.27-3.11-11-7.5a10.47 10.47 0 0 1 2.34-3.79M9.53 9.53A3 3 0 0 0 12 15a3 3 0 0 0 2.47-5.47M19.73 19.73 4.27 4.27" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>'
            : '<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zm0 12.5a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/>';
    }

    function checkStrength(val) {
        const bars   = [1,2,3,4].map(i => document.getElementById('bar' + i));
        const label  = document.getElementById('strength-label');
        const colors = ['#e53935','#fb8c00','#fdd835','#43a047'];
        const labels = ['Weak','Fair','Good','Strong'];

        let score = 0;
        if (val.length >= 6)  score++;
        if (val.length >= 10) score++;
        if (/[A-Z]/.test(val) && /[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;
        score = Math.max(0, Math.min(score, 4));

        bars.forEach((b, i) => {
            b.style.background = i < score ? colors[score - 1] : '#d0daf0';
        });
        label.textContent = val.length ? (labels[score - 1] || '') : '';
        label.style.color = score ? colors[score - 1] : '';




        const product1 = {
            name: 'umar abubakar',
            age: 23,
            email: 'fashiondesign1176@gmail.com',
            product2 : {
                price: '$203'
            },
            main : function sales(){
                console.log('function inside an object');
            }
        }
        product1.age = 29;
        console.log(product1.age);
    }
    const a = 212;
    const b = 23;
    const pro = 'you little face of shit we are fucked';
    const lose = 'we just lose fucking';
    const count = 250000 + 250000;
    

if(a > b){
    console.log(`Adam ${pro}`);
    console.log(`${lose} $${count}`);
} else{
    console.log('lie');
};

function student(name, email, num){
  console.log(name);
  console.log(email);
  console.log(num);
}
student('umar', ' email@', 26537);


</script> 

</body>
</html>
