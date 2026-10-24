<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Complaints</title>
 
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
    .main-content > div[style*="display:flex"],
    .main-content > div[style*="display: flex"] {
        flex-direction: column !important;
        gap: 15px !important;
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
        <li><a href="view_users.php">👥<span>Users</span></a></li>
        <li><a href="manage_complaints.php" class="active">📋<span>Complaints</span></a></li>
        <li><a href="add-room.php">➕<span>Add rooms</span></a></li>
    </ul>
    <div class="sidebar-footer">
        <a href="logout.php">🚪<span>Log Out</span></a>
    </div>
</aside>
    <main class="main-content">
        <h1 class="page-title">Guest Feedback & Issues</h1>
 
<div class="complaint-grid" style="display: grid; gap: 20px;">
    <div class="card" style="border-left: 5px solid #ff4d4d;">
        <div style="display: flex; justify-content: space-between;">
            <h4 style="color: var(--navy-deep);">Room 302 - Plumbing Issue</h4>
            <span class="badge badge-danger">High Priority</span>
        </div>
        <p style="margin: 15px 0; color: var(--text-muted);">Guest reported a leak in the bathroom. Plumber has been notified.</p>
        <button style="background: var(--navy-mid); color: white; border: none; padding: 8px 15px; cursor: pointer;">Assign Task</button>
    </div>
</div>
    <h1 style="font-family: 'Playfair Display'; margin-bottom: 30px;">Guest Relations</h1>
 
    <div style="display: flex; gap: 20px; margin-bottom: 30px;">
        <div style="flex: 1; background: #fff; padding: 20px; border-top: 4px solid #ff4d4d;">
            <h3 style="color: #ff4d4d;">Pending (3)</h3>
        </div>
        <div style="flex: 1; background: #fff; padding: 20px; border-top: 4px solid var(--gold);">
            <h3 style="color: var(--gold);">In Progress (1)</h3>
        </div>
        <div style="flex: 1; background: #fff; padding: 20px; border-top: 4px solid #2ecc71;">
            <h3 style="color: #2ecc71;">Resolved (42)</h3>
        </div>
    </div>
 
    <div style="background: white; padding: 25px; border-radius: 4px; margin-bottom: 15px; position: relative;">
        <span style="position: absolute; right: 20px; top: 20px; font-size: 0.8rem; color: #999;">2 hours ago</span>
        <h4 style="color: var(--navy-deep); margin-bottom: 10px;">Issue: Air Conditioning Failure</h4>
        <p style="color: var(--text-body); font-size: 0.9rem; margin-bottom: 15px;">Room 204 reporting that AC unit is making loud noise and not cooling properly.</p>
        <div style="display: flex; gap: 10px;">
            <button style="background: var(--navy-mid); color: white; border: none; padding: 8px 15px; cursor: pointer; border-radius: 2px;">Assign Engineer</button>
            <button style="background: #eee; color: #555; border: none; padding: 8px 15px; cursor: pointer; border-radius: 2px;">Mark Resolved</button>
        </div>
    </div>
</main>
</body>
</html>