<?php
require_once 'confiq.php';
session_start();



// ── Handle form submission ─────────────────────────────────
$success_msg = '';
$error_msg   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject     = trim($_POST['subject']   ?? '');
    $category    = trim($_POST['category']  ?? '');
    $description = trim($_POST['description'] ?? '');

    $allowed_categories = ['technical', 'billing', 'service', 'other'];

    if ($subject === '' || $description === '' || !in_array($category, $allowed_categories)) {
        $error_msg = 'Please fill in all fields correctly.';
    } else {
        try {
            // We include 'pending' as the default status for new tickets
            $stmt = $pdo->prepare(
                "INSERT INTO complaints (user_id, subject, category, description, status)
                 VALUES (?, ?, ?, ?, 'pending')"
            );
            
            $stmt->execute([$subject, $category, $description]);
            $success_msg = 'Your complaint has been submitted successfully!';
            
            // Clear POST data so the form doesn't stay filled
            $_POST = array(); 
        } catch (PDOException $e) {
            $error_msg = 'Database error: ' . $e->getMessage();
        }
    }
}

// ── Fetch this user's previous complaints ──────────────────
$stmt = $pdo->prepare(
    "SELECT ticket_id, subject, DATE_FORMAT(created_at,'%b %d, %Y') AS date, status
     FROM complaints
     WHERE user_id = ?
     ORDER BY created_at DESC"
);

$complaints = $stmt->fetchAll();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlueWave | User Complaints</title>
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

        /* --- HERO --- */
        .hero {
            background: linear-gradient(rgba(10, 28, 59, 0.85), rgba(10, 28, 59, 0.85)),
                        url('https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&q=80&w=1200');
            background-size: cover;
            background-position: center;
            height: 200px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: var(--white);
            margin-top: 72px;
        }
        .hero h1 { margin: 0; font-size: 2.2rem; color: var(--accent); }

        /* --- LAYOUT --- */
        .container {
            max-width: 1200px;
            margin: -40px auto 50px;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1.6fr 1fr;
            gap: 25px;
        }

        .card {
            background: var(--white);
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .card h3 { color: var(--primary); margin-top: 0; border-bottom: 2px solid var(--accent); display: inline-block; padding-bottom: 5px; margin-bottom: 20px; }

        /* --- TABLE --- */
        .custom-table { width: 100%; border-collapse: collapse; }
        .custom-table th { text-align: left; padding: 12px; color: #777; font-size: 0.9rem; border-bottom: 2px solid #eee; }
        .custom-table td { padding: 15px 12px; border-bottom: 1px solid #eee; font-size: 0.95rem; }
        .no-complaints { color: #999; font-size: 0.95rem; text-align: center; padding: 20px 0; }

        .status { padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; }
        .pending   { background: #fff3cd; color: #856404; }
        .in_review { background: #cce5ff; color: #004085; }
        .resolved  { background: #d4edda; color: #155724; }

        /* --- FORM --- */
        input, select, textarea {
            width: 100%; padding: 12px; margin-bottom: 15px;
            border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box;
        }
        .btn-submit {
            background: var(--primary); color: var(--accent);
            border: 2px solid var(--accent); padding: 12px;
            font-weight: bold; border-radius: 6px; cursor: pointer;
            width: 100%; transition: 0.3s;
        }
        .btn-submit:hover { background: var(--accent); color: var(--primary); }

        /* --- ALERTS --- */
        .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 16px; font-size: 0.9rem; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error   { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        /* --- FOOTER --- */
        footer {
            background: var(--primary); color: var(--white);
            text-align: center; padding: 25px; border-top: 4px solid var(--accent);
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
        <li><a href="contact.php">Contact</a></li>
        <li><a href="user_complain.php" class="active">Complaints</a></li>
    </ul>
    <a href="booking.php" class="nav-cta">Book Now</a>
</nav>

<section class="hero">
    <h1>Complaint Desk</h1>
    <p>Tell us what's wrong and we'll fix it immediately.</p>
</section>

<div class="container">

    <!-- ── Previous Complaints ── -->
    <div class="card">
        <h3>My Previous Complaints</h3>
        <?php if (empty($complaints)): ?>
            <p class="no-complaints">No complaints submitted yet.</p>
        <?php else: ?>
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Ticket ID</th>
                    <th>Subject</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($complaints as $c): ?>
                <tr>
                    <td>#<?= htmlspecialchars($c['ticket_id']) ?></td>
                    <td><?= htmlspecialchars($c['subject']) ?></td>
                    <td><?= htmlspecialchars($c['date']) ?></td>
                    <td>
                        <span class="status <?= htmlspecialchars($c['status']) ?>">
                            <?= ucfirst(str_replace('_', ' ', $c['status'])) ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

    <!-- ── New Complaint Form ── -->
    <div class="card">
        <h3>New Support Ticket</h3>

        <?php if ($success_msg): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success_msg) ?></div>
        <?php endif; ?>
        <?php if ($error_msg): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error_msg) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text" name="subject" placeholder="Subject / Title"
                   value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>" required>

            <select name="category" required>
                <option value="" disabled <?= empty($_POST['category']) ? 'selected' : '' ?>>Select Category</option>
                <option value="technical" <?= ($_POST['category'] ?? '') === 'technical' ? 'selected' : '' ?>>Technical Support</option>
                <option value="billing"   <?= ($_POST['category'] ?? '') === 'billing'   ? 'selected' : '' ?>>Billing &amp; Payment</option>
                <option value="service"   <?= ($_POST['category'] ?? '') === 'service'   ? 'selected' : '' ?>>Service Complaints</option>
                <option value="other"     <?= ($_POST['category'] ?? '') === 'other'     ? 'selected' : '' ?>>Other</option>
            </select>

            <textarea name="description" rows="6" placeholder="Describe your issue in detail..." required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>

            <button type="submit" class="btn-submit">Submit Complaint</button>
        </form>
    </div>

</div>




</body>
</html>