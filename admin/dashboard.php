<?php
require_once '../confiq.php'; // this defines $pdo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Total rooms
$stmt = $pdo->query("SELECT COUNT(*) AS total_rooms FROM rooms");
$total_rooms = $stmt->fetch(PDO::FETCH_ASSOC)['total_rooms'];

// Total complaint

$stmt = $pdo->query("SELECT COUNT(*) AS total_complaints FROM complaints");
$total_complaints = $stmt->fetch(PDO::FETCH_ASSOC)['total_complaints'];

// Total users

$stmt = $pdo->query("SELECT COUNT(*) AS total_users FROM people");
$total_users = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];

// Total bookings

$stmt = $pdo->query("SELECT COUNT(*) AS total_bookings FROM bookingss");
$total_bookings = $stmt->fetch(PDO::FETCH_ASSOC)['total_bookings'];

// Fetch the 7 most recent bookings
  
$stmt = $pdo->query(" SELECT guest_name, room_id, status, created_at
    FROM bookingss
    ORDER BY created_at DESC
    LIMIT 7");
$recent_activity = $stmt->fetchAll(PDO::FETCH_ASSOC);





?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Jost:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="admin-wrapper">
    <aside class="sidebar">
        <h2>BlueWave Admin</h2>
        <ul class="sidebar-nav">
            <li><a href="dashboard.php" class="active">Dashboard</a></li>
            <li><a href="view_bookings.php">Bookings</a></li>
            <li><a href="view_rooms.php">Rooms</a></li>
            <li><a href="view_users.php">Users</a></li>
            <li><a href="manage_complaints.php">Complaints</a></li>
            <li><a href="add-room.php">Add rooms</a></li>
            <li style="margin-top: 50px;"><a href="login.php">Logout</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <h1 style="font-family: 'Playfair Display'; margin-bottom: 30px;">Executive Dashboard</h1>
        
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 40px;">
            <div style="background: white; padding: 20px; border-left: 4px solid var(--gold); box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <p style="color: #666; font-size: 0.8rem; text-transform: uppercase;">Total complaints</p>
                <h2 style="color: var(--navy-deep);"><?php echo $total_complaints;?></h2>
            </div>
            <div style="background: white; padding: 20px; border-left: 4px solid var(--gold);">
    <p style="color: #666; font-size: 0.8rem; text-transform: uppercase;">Total Rooms</p>
    <h2 style="color: var(--navy-deep);"><?php echo $total_rooms; ?></h2>
</div>
            <div style="background: white; padding: 20px; border-left: 4px solid var(--navy-mid); box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <p style="color: #666; font-size: 0.8rem; text-transform: uppercase;">Active Guests</p>
                <h2 style="color: var(--navy-deep);"><?php echo $total_users; ?></h2>
            </div>
             <div style="background: white; padding: 20px; border-left: 4px solid var(--gold); box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <p style="color: #666; font-size: 0.8rem; text-transform: uppercase;">Total Bookings</p>
                <h2 style="color: var(--navy-deep);"><?php echo $total_bookings; ?></h2>
            </div>
        </div>

        <div style="background: white; padding: 30px; border-radius: 8px;">
            <h3>Recent Activity</h3>
            
            



<table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
    <tr style="border-bottom: 1px solid #eee; text-align: left; color: #888; font-size: 0.8rem;">
        <th style="padding: 10px;">Guest</th>
        <th>Room</th>
        <th>Status</th>
        <th>Date</th>
    </tr>
    <?php foreach ($recent_activity as $row): ?>
    <tr>
        <td style="padding: 15px 10px;"><?php echo htmlspecialchars($row['guest_name']); ?></td>
        <td><?php echo htmlspecialchars($row['room_id']); ?></td>
        <td>
            <?php if ($row['status'] === 'Checked In'): ?>
                <span style="color: green;">Checked In</span>
            <?php elseif ($row['status'] === 'Checked Out'): ?>
                <span style="color: red;">Checked Out</span>
            <?php else: ?>
                <span style="color: orange;"><?php echo htmlspecialchars($row['status']); ?></span>
            <?php endif; ?>
        </td>
        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
    </tr>
    <?php endforeach; ?>
</table>
        </div>
    </main>
</div>
</body>
</html>


