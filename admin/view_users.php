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
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Jost:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
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

        /* Adjustments for mobile viewing */
        @media (max-width: 680px) {
            .directory-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
     <aside class="sidebar">
        <h2>BlueWave Admin</h2>
        <ul class="sidebar-nav">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="view_bookings.php">Bookings</a></li>
            <li><a href="view_rooms.php">Rooms</a></li>
            <li><a href="view_users.php" class="active">Users</a></li>
            <li><a href="manage_complaints.php">Complaints</a></li>
            <li><a href="add-room.php">Add rooms</a></li>
            <li style="margin-top: 50px;"><a href="../login.php">Logout</a></li>
        </ul>
    </aside>
    
    <main class="main-content">
        <h1 style="font-family: 'Playfair Display'; margin-bottom: 30px;">Member Directory</h1>

        <div class="directory-grid">
            
            <?php if(count($users) > 0): ?>
                <?php foreach($users as $user): ?>
                    <?php
                        // Dynamically extract initials from the user's name (e.g., John Doe -> JD)
                        $nameParts = explode(' ', trim($user['name'] ?? 'User'));
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
                                <?= htmlspecialchars($user['name'] ?? 'Unknown User') ?>
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