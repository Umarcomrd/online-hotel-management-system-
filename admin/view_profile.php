<?php
// admin/view_profile.php
require_once '../confiq.php';

// Handle Delete Action
if (isset($_GET['delete'])) {
    $del_id = intval($_GET['delete']);
    try {
        $stmt = $pdo->prepare("DELETE FROM people WHERE id = ?");
        $stmt->execute([$del_id]);
        // Redirect back to the directory with a success flag
        header('Location: view_users.php?deleted=1');
        exit;
    } catch (PDOException $e) {
        die("Error deleting user: " . $e->getMessage());
    }
}

// Check if an ID was passed in the URL to view the profile
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: view_users.php');
    exit;
}

$user_id = intval($_GET['id']);
// Fetch the specific user's data
try {
    $stmt = $pdo->prepare("SELECT * FROM people WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // If no user is found with that ID
    if (!$user) {
        die("<div style='padding: 50px; text-align: center; font-family: sans-serif;'><h2>User not found.</h2><a href='view_users.php'>Return to Directory</a></div>");
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Generate initials for the avatar
$nameParts = explode(' ', trim($user['full_name'] ?? 'Unknown User'));
$initials = strtoupper(substr($nameParts[0], 0, 1));
if (isset($nameParts[1])) {
    $initials .= strtoupper(substr($nameParts[1], 0, 1));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($user['full_name'] ?? 'User Profile') ?> - BlueWave Admin</title>
    <link rel="stylesheet" href="admin_style.css">
    <link rel="stylesheet" href="new_admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy:      #0a1c3b;
            --navy-deep: #060f22;
            --gold:      #c9a84c;
            --white:     #ffffff;
            --off-white: #f4f6fb;
            --text-body: #4a5a75;
            --border:    #d0daf0;
            --danger:    #c62828;
            --danger-bg: #fff0f0;
        }

        /* BREADCRUMB */
        .breadcrumb { padding: 0 0 24px 0; font-size: 0.85rem; color: var(--text-body); display: flex; align-items: center; gap: 8px; }
        .breadcrumb a { color: var(--text-body); text-decoration: none; transition: color .2s; font-weight: 500; }
        .breadcrumb a:hover { color: var(--gold); }
        .breadcrumb span { color: var(--navy); font-weight: 600; }
        .breadcrumb .sep { opacity: .4; }

        /* PROFILE LAYOUT */
        .profile-header-banner {
            background: linear-gradient(135deg, var(--navy-deep) 0%, var(--navy) 100%);
            height: 150px;
            border-radius: 8px 8px 0 0;
            position: relative;
        }

        .profile-card {
            background: var(--white);
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(10,28,59,.08);
            margin-top: -50px;
            position: relative;
            z-index: 10;
        }

        .profile-top-section {
            display: flex;
            align-items: flex-end;
            padding: 0 40px 30px;
            gap: 25px;
            border-bottom: 1px solid var(--border);
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: var(--gold);
            border: 5px solid var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: var(--white);
            font-weight: 700;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            margin-top: -60px;
        }

        .profile-title h1 {
            font-family: 'Playfair Display', serif;
            color: var(--navy);
            font-size: 2rem;
            margin-bottom: 5px;
        }

        .profile-title p { color: var(--text-body); font-size: 1rem; font-weight: 500; }

        .profile-body {
            padding: 40px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }

        .info-group { margin-bottom: 25px; }

        .info-group label {
            display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;
            color: var(--text-body); font-weight: 700; margin-bottom: 8px;
        }

        .info-group .info-value {
            font-size: 1.1rem; color: var(--navy); font-weight: 500; padding-bottom: 10px;
            border-bottom: 1px solid var(--border);
        }

        .profile-actions {
            padding: 20px 40px;
            background: var(--off-white);
            border-radius: 0 0 8px 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid var(--border);
        }

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

        .btn {
            padding: 12px 24px; border-radius: 4px; text-decoration: none; font-weight: 600;
            font-size: 0.9rem; transition: 0.2s; display: inline-block; cursor: pointer; border: none; font-family: 'Jost', sans-serif;
        }

        .btn-outline { border: 1px solid var(--border); color: var(--navy); background: var(--white); }
        .btn-outline:hover { border-color: var(--navy); background: #f8f9fa; }

        .btn-danger { background: var(--danger-bg); color: var(--danger); border: 1px solid #ffd5d5; }
        .btn-danger:hover { background: var(--danger); color: var(--white); }

        /* Confirm modal overlay */
        .modal-overlay {
            display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5);
            z-index: 5000; justify-content: center; align-items: center;
        }
        .modal-overlay.active { display: flex; }
        
        .modal-box {
            background: white; padding: 35px; max-width: 420px; width: 90%;
            text-align: center; box-shadow: 0 10px 40px rgba(0,0,0,0.2); border-radius: 8px;
        }
        .modal-box h3 { font-family: 'Playfair Display', serif; font-size: 1.4rem; color: var(--navy-deep); margin-bottom: 10px; }
        .modal-box p { color: #666; font-size: 0.92rem; margin-bottom: 25px; line-height: 1.5; }
        .modal-actions { display: flex; gap: 12px; justify-content: center; }

        .modal-cancel { padding: 10px 28px; border: 1px solid #ddd; background: white; color: #333; cursor: pointer; font-family: 'Jost', sans-serif; font-weight: 600; border-radius: 4px; }
        .modal-confirm { padding: 10px 28px; background: var(--danger); color: white; border: none; cursor: pointer; font-family: 'Jost', sans-serif; font-weight: 600; border-radius: 4px; text-decoration: none; }
        .modal-confirm:hover { background: #a00; }

        /* Responsive */
      
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

<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <h3>Delete User?</h3>
        <p>Are you sure you want to delete <strong><?= htmlspecialchars($user['full_name'] ?? 'this user') ?></strong>? This action cannot be undone.</p>
        <div class="modal-actions">
            <button class="modal-cancel" onclick="closeModal()">Cancel</button>
            <a href="view_profile.php?delete=<?= $user['id'] ?>" class="modal-confirm">Yes, Delete User</a>
        </div>
    </div>
</div>

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
    
    <div class="breadcrumb">
        <a href="dashboard.php">Dashboard</a>
        <span class="sep">›</span>
        <a href="view_users.php">Users Directory</a>
        <span class="sep">›</span>
        <span>User Profile</span>
    </div>

    <div class="profile-wrapper">
        <div class="profile-header-banner"></div>
        
        <div class="profile-card">
            <div class="profile-top-section">
                <div class="profile-avatar">
                    <?= htmlspecialchars($initials) ?>
                </div>
                <div class="profile-title">
                    <h1><?= htmlspecialchars($user['full_name'] ?? 'Unknown') ?></h1>
                    <p>Registered Member</p>
                </div>
            </div>

            <div class="profile-body">
                <div>
                    <div class="info-group">
                        <label>Full Name</label>
                        <div class="info-value"><?= htmlspecialchars($user['full_name'] ?? 'N/A') ?></div>
                    </div>
                    
                    <div class="info-group">
                        <label>Email Address</label>
                        <div class="info-value"><?= htmlspecialchars($user['email'] ?? 'N/A') ?></div>
                    </div>
                </div>

                <div>
                    <div class="info-group">
                        <label>User ID</label>
                        <div class="info-value">#<?= htmlspecialchars($user['id']) ?></div>
                    </div>
                </div>
            </div>

            <div class="profile-actions">
                <a href="view_users.php" class="btn btn-outline">← Back to Directory</a>
                <button type="button" class="btn btn-danger" onclick="openModal()">🗑 Delete User</button>
            </div>
        </div>
    </div>

</main>

<script>
    function openModal() {
        document.getElementById('deleteModal').classList.add('active');
    }

    function closeModal() {
        document.getElementById('deleteModal').classList.remove('active');
    }

    // Close modal if clicking outside the box
    document.getElementById('deleteModal').addEventListener('click', function(e){
        if(e.target === this) closeModal();
    });
</script>

</body>
</html>