<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    // 1. Include database configuration
    require_once '../confiq.php'; 

// 2. Handle Search Filter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if (!empty($search)) {
    // Search query matching guest name using safe prepared statements
    $query = "SELECT b.*, r.room_name 
              FROM bookingss  
              JOIN rooms ON b.room_id = r.id 
              WHERE b.guest_name LIKE ? 
              ORDER BY b.id DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute(["%$search%"]);
} else {
    // Fetch all bookings if search is empty
    $query = "SELECT b.*, r.room_name 
              FROM bookingss b 
              JOIN rooms r ON b.room_id = r.id 
              ORDER BY b.id DESC";
    $stmt = $pdo->query($query);
}

$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlueWave Admin – Bookings</title>
    <link rel="stylesheet" href="admin_style.css">
    <link rel="stylesheet" href="new_admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Jost:wght@400;500;600&display=swap" rel="stylesheet">
    
    <style>
        /* ==========================================================================
           BlueWave Admin Bookings Combined Grid & Table Styling
           ========================================================================== */
           :root {
    --gold: #c9a84c;
    --text-main: #f4f7f9;
}

.admin-logo {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-main);
    margin-bottom: 30px;
    padding-left: 10px;
    letter-spacing: 0.5px;
}
.admin-logo span {
    color: var(--gold);
}

        .main-content {
            padding: 30px;
            background-color: #f4f6fb;
        }

        .page-header-actions {
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 30px;
        }

        .page-header-actions h1 {
            font-family: 'Playfair Display', serif; 
            margin: 0;
            color: #0a1c3b; /* BlueWave Navy */
        }

        /* Search Form Bar */
        .search-form {
            display: flex; 
            gap: 10px;
        }

        .search-input {
            padding: 10px 15px; 
            width: 300px; 
            border: 1px solid #ddd; 
            border-radius: 4px; 
            font-family: 'Jost', sans-serif;
            outline: none;
            transition: border-color 0.2s;
        }

        .search-input:focus {
            border-color: #c9a84c; /* BlueWave Gold */
        }

        .btn-clear {
            padding: 10px 15px; 
            background: #884b4b; 
            color: #333; 
            text-decoration: none; 
            border-radius: 4px; 
            font-size: 0.9rem;
            font-family: 'Jost', sans-serif;
            display: flex;
            align-items: center;
        }

        /* Data Table Container Elements */
        .table-container {
            background: #ffffff; 
            border-radius: 4px; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.05); 
            overflow: hidden;
        }

        .bookings-table {
            width: 100%; 
            border-collapse: collapse; 
            text-align: left; 
            font-family: 'Jost', sans-serif;
        }

        .bookings-table thead {
            background: #060f22; /* Deep Navy Base */
            color: #ffffff;
        }

        .bookings-table th {
            padding: 15px;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .bookings-table tbody {
            color: #4a5a75;
        }

        .bookings-table tr {
            border-bottom: 1px solid #eee;
            transition: background-color 0.2s;
        }

        .bookings-table tr:hover {
            background-color: #fcfdfe; /* Interactive scanning feedback */
        }

        .bookings-table td {
            padding: 15px;
            vertical-align: middle;
        }

        /* Row Internal Structural Data Formatting */
        .booking-id {
            font-weight: 500;
        }

        .guest-name {
            font-weight: 600; 
            color: #0a1c3b; /* BlueWave Navy Accent */
        }

        .guest-meta {
            font-size: 0.8rem; 
            color: #a8b4cc;
            margin-top: 1px;
        }

        .room-type {
            font-weight: 500;
        }

        .stay-dates {
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .no-data {
            padding: 40px !important; 
            text-align: center; 
            color: #a8b4cc;
            font-size: 1.1rem;
        }

        /* Contextual Dynamic Badges */
        .status-badge {
            padding: 4px 12px; 
            border-radius: 20px; 
            font-size: 0.75rem; 
            font-weight: 700;
            text-transform: uppercase;
            display: inline-block;
        }

        .status-badge.pending {
            background: #fff3cd; 
            color: #856404;
        }

        .status-badge.confirmed,
        .status-badge.paid {
            background: #e1f5fe; 
            color: #039be5;
        }

        .status-badge.cancelled {
            background: #f8d7da; 
            color: #721c24;
        }

        /* Action Hyperlink Handlers */
        .btn-manage {
            color: #0a1c3b; 
            text-decoration: none; 
            font-weight: 600; 
            font-size: 0.9rem;
            transition: color 0.2s;
        }

        .btn-manage:hover {
            color: #c9a84c; /* Gold highlight shift */
        }

       @media (max-width: 768px) {
    .sidebar { 
        width: 70px; 
        padding: 25px 10px; 
    }
    .sidebar-nav span, .admin-logo, .sidebar-footer span { 
        display: none; 
    }
    .sidebar-nav a, .sidebar-footer a {
        justify-content: center;
        font-size: 1.3rem;
    }
    .main-content { 
        margin-left: 70px; 
        width: calc(100% - 70px); 
        padding: 20px;
    }
    .metrics-grid {
        grid-template-columns: 1fr;
    }
    .top-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    .search-bar {
        width: 100%;
    }
}
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <aside class="sidebar">
    <div class="admin-logo">BlueWave <span>Admin</span></div>
    <ul class="sidebar-nav">
        <li><a href="dashboard.php">📊<span>Dashboard</span></a></li>
        <li><a href="view_bookings.php" class="active">📅<span>Bookings</span></a></li>
        <li><a href="view_rooms.php">🛏️<span>Rooms</span></a></li>
        <li><a href="view_users.php">👥<span>Users</span></a></li>
        <li><a href="manage_complaints.php">📋<span>Complaints</span></a></li>
        <li><a href="add-room.php">➕<span>Add rooms</span></a></li>
    </ul>
    <div class="sidebar-footer">
        <a href="logout.php">🚪<span>Log Out</span></a>
    </div>
</aside>

        <main class="main-content">
            
            <div class="page-header-actions">
                <h1>Guest Bookings</h1>
                
                <form method="GET" action="view_bookings.php" class="search-form">
                    <input type="text" name="search" class="search-input" 
                           placeholder="Search by Guest Name..." 
                           value="<?= htmlspecialchars($search) ?>">
                    <?php if(!empty($search)): ?>
                        <a href="view_bookings.php" class="btn-clear">Clear button</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="table-container">
                <table class="bookings-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Guest Details</th>
                            <th>Room Type</th>
                            <th>Dates (Stay)</th>
                            <th>Guests</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($bookings)): ?>
                            <tr>
                                <td colspan="7" class="no-data">
                                    No reservations found.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($bookings as $row): ?>
                                <tr>
                                    <td class="booking-id">#BW-<?= $row['id'] ?></td>
                                    <td>
                                        <div class="guest-name"><?= htmlspecialchars($row['guest_name']) ?></div>
                                        <div class="guest-meta"><?= htmlspecialchars($row['guest_email']) ?></div>
                                        <div class="guest-meta"><?= htmlspecialchars($row['guest_phone']) ?></div>
                                    </td>
                                    <td class="room-type"><?= htmlspecialchars($row['room_name']) ?></td>
                                    <td class="stay-dates">
                                        <div><strong>In:</strong> <?= date('M d, Y', strtotime($row['check_in'])) ?></div>
                                        <div><strong>Out:</strong> <?= date('M d, Y', strtotime($row['check_out'])) ?></div>
                                    </td>
                                    <td class="guest-count"><?= htmlspecialchars($row['guests']) ?></td>
                                    <td>
                                        <?php 
                                            // Normalize status string for CSS classing
                                            $statusAttr = strtolower(trim($row['status'])); 
                                        ?>
                                        <span class="status-badge <?= $statusAttr ?>">
                                            <?= htmlspecialchars(strtoupper($row['status'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="manage_booking.php?id=<?= $row['id'] ?>" class="btn-manage">Manage</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>