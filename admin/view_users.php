<?php

// admin/view_users.php
require_once '../confiq.php';

// Fetch all registered users from the 'people' table
try {
    $stmt = $pdo->query("SELECT * FROM people ORDER BY id DESC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching users: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - BlueWave Admin</title>

    <link rel="stylesheet" href="admin_style.css">
    <link rel="stylesheet" href="new_admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Jost:wght@400;500;600&display=swap" rel="stylesheet">
    <style>


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
        /* Responsive Grid Configuration for exact 3 columns */
        .directory-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        /* Adjustments for smaller desktop/tablet viewing */
        @media (max-width: 1024px) {
            .directory-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

      
       @media (min-width: 577px) and (max-width: 768px) {
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

@media (max-width: 576px) {
    .directory-grid {
        grid-template-columns: 1fr !important;
        gap: 15px !important;
    }
    .directory-grid > div {
        flex-direction: column !important;
        align-items: center !important;
        text-align: center !important;
        padding: 25px 15px !important;
    }
    .directory-grid > div > div:first-of-type {
        margin: 0 auto 10px !important;
    }
    .directory-grid > div > div:nth-of-type(2) {
        width: 100% !important;
        margin-bottom: 15px !important;
    }
    .directory-grid > div > a {
        margin: 0 auto !important;
        width: 100% !important;
        text-align: center !important;
    }
}
    </style>
</head>
<body>
     <aside class="sidebar">
    <div class="admin-logo">BlueWave <span>Admin</span></div>
    <ul class="sidebar-nav">
        <li><a href="dashboard.php">📊<span>Dashboard</span></a></li>
        <li><a href="view_bookings.php">📅<span>Bookings</span></a></li>
        <li><a href="view_rooms.php">🛏️<span>Rooms</span></a></li>
        <li><a href="view_users.php" class="active">👥<span>Users</span></a></li>
        <li><a href="manage_complaints.php">📋<span>Complaints</span></a></li>
        <li><a href="add-room.php">➕<span>Add rooms</span></a></li>
    </ul>
    <div class="sidebar-footer">
        <a href="logout.php">🚪<span>Log Out</span></a>
    </div>
</aside>
    
    <main class="main-content">
        <h1 style="font-family: 'Playfair Display'; margin-bottom: 30px;">Member Directory</h1>

        <div class="directory-grid">
            
            <?php if(count($users) > 0): ?>
                <?php foreach($users as $user): ?>
                    <?php
                        // Dynamically extract initials from the user's name (e.g., John Doe -> JD)
                        $nameParts = explode(' ', trim($user['full_name'] ?? 'User'));
                        $initials = strtoupper(substr($nameParts[0], 0, 1));
                        if (isset($nameParts[1])) {
                            $initials .= strtoupper(substr($nameParts[1], 0, 1));
                        }
                    ?>
                    
                    <div style="background: white; padding: 20px; border-radius: 8px; display: flex; align-items: center; gap: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                        <div style="width: 50px; height: 50px; border-radius: 50%; background: var(--gold, #c9a84c); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; flex-shrink: 0;">
                            <?= htmlspecialchars($initials) ?>
                        </div>
                        <div style="flex-grow: 1; min-width: 0;">
                            <h4 style="margin: 0; color: var(--navy-deep, #060f22); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <?= htmlspecialchars($user['full_name'] ?? 'Unknown User') ?>
                            </h4>
                            <p style="margin: 0; font-size: 0.85rem; color: #888; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <?= htmlspecialchars($user['email'] ?? 'No email provided') ?>
                            </p>
                            <p style="margin: 0; font-size: 0.75rem; color: var(--gold, #c9a84c); font-weight: 600;">
                                Registered Member
                            </p>
                        </div>
                        <a href="view_profile.php?id=<?= $user['id'] ?? '' ?>" style="margin-left: auto; text-decoration: none; color: #333; font-family: 'Jost', sans-serif; font-size: 0.85rem; background: #f0f2f5; padding: 8px 14px; border-radius: 4px; transition: background 0.2s; flex-shrink: 0;">
                            View Profile
                        </a>
                    </div>

                <?php endforeach; ?>
            <?php else: ?>
                
                <div style="grid-column: 1 / -1; padding: 40px; text-align: center; background: white; border-radius: 8px; border: 1px dashed #ccc; color: #888;">
                    <p>No registered users found in the directory.</p>
                </div>
                
            <?php endif; ?>

        </div>
    </main>
</body>
</html>