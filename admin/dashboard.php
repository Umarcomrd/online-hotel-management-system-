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
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    
    <style>
        /* BlueWave Admin - Modern Dark Theme Styles */
        :root {
            --navy-deep: #050b14;       /* Deep layout background */
            --navy-panel: #0b1528;      /* Primary card/sidebar container background */
            --navy-light: #12213a;      /* Secondary accents & hover fields */
            --gold: #c9a84c;            /* Accent color */
            --gold-glow: rgba(201, 168, 76, 0.15);
            
            --text-main: #f4f7f9;       /* High-contrast text */
            --text-muted: #8496b0;      /* Secondary reading text */
            --sidebar-w: 260px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Jost', sans-serif;
            background-color: var(--navy-deep);
            color: var(--text-main);
            overflow-x: hidden;
            line-height: 1.5;
        }

        /* Layout Wrapper */
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR STYLING */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--navy-panel);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border-right: 1px solid rgba(255, 255, 255, 0.03);
        }

        .admin-logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 30px;
            padding-left: 10px;
            letter-spacing: 0.5px;
        }
        .admin-logo span { 
            color: var(--gold); 
        }

        .sidebar-nav {
            list-style: none;
            flex-grow: 1;
        }

        .sidebar-nav li { 
            margin-bottom: 6px; 
        }

        .sidebar-nav a {
            color: var(--text-muted);
            text-decoration: none;
            padding: 12px 15px;
            display: flex;
            align-items: center;
            border-radius: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 0.95rem;
        }

        .sidebar-nav a:hover, 
        .sidebar-nav a.active {
            background: var(--navy-light);
            color: #ffffff;
        }

        .sidebar-nav a.active {
            background: linear-gradient(90deg, var(--navy-light) 0%, rgba(201,168,76,0.1) 100%);
            border-left: 3px solid var(--gold);
            color: #fff;
            padding-left: 18px;
        }

        /* Bottom Logout block */
        .sidebar-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            padding-top: 20px;
        }

        .sidebar-footer a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.95rem;
            padding: 12px 15px;
            display: block;
            border-radius: 8px;
            transition: 0.3s;
        }
        .sidebar-footer a:hover {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        /* MAIN CONTENT AREA */
        .main-content {
            margin-left: var(--sidebar-w);
            width: calc(100% - var(--sidebar-w));
            padding: 30px 40px;
        }

        /* TOP HEADER PANEL (Matches Reference Layout) */
        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
        }

        .search-bar {
            position: relative;
            width: 320px;
        }

        .search-bar input {
            width: 100%;
            padding: 11px 44px 11px 20px;
            background: var(--navy-panel);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 30px;
            color: var(--text-main);
            font-size: 0.9rem;
            outline: none;
            transition: 0.3s;
        }

        .search-bar input::placeholder {
            color: var(--text-muted);
        }

        .search-bar input:focus {
            border-color: var(--gold);
            box-shadow: 0 0 10px var(--gold-glow);
        }

        .search-bar-btn {
            position: absolute;
            right: 6px;
            top: 50%;
            transform: translateY(-50%);
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: none;
            background: var(--gold);
            color: var(--navy-deep);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.2s;
        }

        .search-bar-btn:hover {
            background: #ddbf6c;
        }

        /* SEARCH RESULTS MODAL (portrait card popup) */
        .search-modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            z-index: 5000;
            justify-content: center;
            align-items: center;
        }

        .search-modal-overlay.active {
            display: flex;
        }

        .search-modal-box {
            background: var(--navy-panel);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 16px;
            width: 380px;
            max-width: 90vw;
            max-height: 80vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 20px 50px rgba(0,0,0,0.4);
            overflow: hidden;
        }

        .search-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 22px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .search-modal-header h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            color: var(--text-main);
        }

        .search-modal-close {
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 1.3rem;
            line-height: 1;
            cursor: pointer;
            padding: 4px;
        }

        .search-modal-close:hover {
            color: #ffffff;
        }

        .search-modal-body {
            padding: 14px;
            overflow-y: auto;
        }

        .search-result-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border-radius: 10px;
            transition: background 0.2s;
        }

        .search-result-item:hover {
            background: var(--navy-light);
        }

        .search-result-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--gold);
            color: var(--navy-deep);
            font-weight: 700;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .search-result-info {
            min-width: 0;
        }

        .search-result-name {
            color: var(--text-main);
            font-weight: 600;
            font-size: 0.9rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .search-result-email {
            color: var(--text-muted);
            font-size: 0.8rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .search-modal-message {
            padding: 30px 20px;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            background: var(--navy-panel);
            padding: 6px 16px 6px 6px;
            border-radius: 30px;
            border: 1px solid rgba(255,255,255,0.03);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--navy-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: var(--gold);
            border: 1px solid rgba(201,168,76,0.3);
        }

        .user-info .profile-name {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-main);
        }

        .user-info .profile-role {
            font-size: 0.75rem;
            color: var(--text-muted);
            display: block;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .page-title {
            font-size: 1.8rem;
            font-weight: 600;
            letter-spacing: -0.5px;
            font-family: 'Playfair Display', serif;
        }

        /* METRIC CARDS - GRADIENTS FROM IMAGE REFERENCE */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 35px;
        }

        .metric-card {
            position: relative;
            padding: 24px;
            border-radius: 16px;
            overflow: hidden;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 130px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            transition: transform 0.3s ease;
        }

        .metric-card:hover {
            transform: translateY(-4px);
        }

        /* Custom Gradient Visuals Match Reference Image Color-Blocks */
        .metric-card.complaints {
            background: linear-gradient(135deg, #1fa5a3 0%, #11626c 100%);
        }
        .metric-card.rooms {
            background: linear-gradient(135deg, #7c4dff 0%, #4a148c 100%);
        }
        .metric-card.users {
            background: linear-gradient(135deg, #ff7043 0%, #d84315 100%);
        }
        .metric-card.bookings {
            background: linear-gradient(135deg, #ec407a 0%, #ad1457 100%);
        }

        .metric-label {
            font-size: 0.85rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            opacity: 0.85;
        }

        .metric-value {
            font-size: 2.2rem;
            font-weight: 700;
            margin-top: 10px;
            line-height: 1;
        }

        /* DATA CONTAINERS */
        .dashboard-panel {
            background: var(--navy-panel);
            border-radius: 16px;
            padding: 25px;
            border: 1px solid rgba(255,255,255,0.03);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .panel-title {
            font-size: 1.15rem;
            font-weight: 600;
        }

        /* TABLES STYLING (Premium Dark Scheme) */
        .modern-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .modern-table th {
            padding: 14px 16px;
            color: var(--text-muted);
            font-weight: 500;
            font-size: 0.85rem;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .modern-table td {
            padding: 16px;
            font-size: 0.9rem;
            border-bottom: 1px solid rgba(255,255,255,0.03);
            color: var(--text-main);
        }

        .modern-table tr:last-child td {
            border-bottom: none;
        }

        /* MODERN GLOW BADGES */
        .status-pill {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.3px;
            text-align: center;
        }

        .status-checked-in {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .status-checked-out {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        /* RESPONSIVE DESIGN */
        @media (max-width: 1200px) {
            .metrics-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .sidebar { 
                width: 70px; 
                padding: 25px 10px; 
            }
            .sidebar-nav span, .admin-logo, .sidebar-footer span { 
                display: none; 
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
            <li><a href="dashboard.php" class="active"><span>Dashboard</span></a></li>
            <li><a href="view_bookings.php"><span>Bookings</span></a></li>
            <li><a href="view_rooms.php"><span>Rooms</span></a></li>
            <li><a href="view_users.php"><span>Users</span></a></li>
            <li><a href="manage_complaints.php"><span>Complaints</span></a></li>
            <li><a href="add-room.php"><span>Add rooms</span></a></li>
        </ul>
        <div class="sidebar-footer">
            <a href="login.php">Log Out</a>
        </div>
    </aside>

    <main class="main-content">
        <header class="top-header">
            <div class="search-bar">
                <input type="text" id="dashboardSearchInput" placeholder="Search Users by name or email...">
                <button type="button" id="dashboardSearchBtn" class="search-bar-btn" aria-label="Search">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                        <path d="M15.5 14h-.79l-.28-.27a6.5 6.5 0 1 0-.7.7l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0A4.5 4.5 0 1 1 14 9.5 4.5 4.5 0 0 1 9.5 14z"/>
                    </svg>
                </button>
            </div>
            <div class="user-profile">
                <div class="user-avatar">AD</div>
                <div class="user-info">
                    <span class="profile-name">Admin Users</span>
                    <span class="profile-role">admin@bluewave.com</span>
                </div>
            </div>
        </header>

        <div class="page-header">
            <h1 class="page-title">Executive Dashboard</h1>
        </div>
        
        <div class="metrics-grid">
            <div class="metric-card complaints">
                <div class="metric-label">Total complaints</div>
                <div class="metric-value"><?php echo $total_complaints;?></div>
            </div>
            <div class="metric-card rooms">
                <div class="metric-label">Total Rooms</div>
                <div class="metric-value"><?php echo $total_rooms; ?></div>
            </div>
            <div class="metric-card users">
                <div class="metric-label">Active Guests</div>
                <div class="metric-value"><?php echo $total_users; ?></div>
            </div>
             <div class="metric-card bookings">
                <div class="metric-label">Total Bookings</div>
                <div class="metric-value"><?php echo $total_bookings; ?></div>
            </div>
        </div>

        <div class="dashboard-panel">
            <div class="panel-header">
                <h3 class="panel-title">Recent Activity</h3>
            </div>
            
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Guest</th>
                        <th>Room</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_activity as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['guest_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['room_id']); ?></td>
                        <td>
                            <?php if ($row['status'] === 'Checked In'): ?>
                                <span class="status-pill status-checked-in">Checked In</span>
                            <?php elseif ($row['status'] === 'Checked Out'): ?>
                                <span class="status-pill status-checked-out">Checked Out</span>
                            <?php else: ?>
                                <span class="status-pill"><?php echo htmlspecialchars($row['status']); ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<div class="search-modal-overlay" id="searchModal">
    <div class="search-modal-box">
        <div class="search-modal-header">
            <h3>Search Results</h3>
            <button type="button" class="search-modal-close" id="searchModalClose" aria-label="Close">&times;</button>
        </div>
        <div class="search-modal-body" id="searchModalBody">
            <div class="search-modal-message">Type a name or email and hit search.</div>
        </div>
    </div>
</div>

<script>
    const searchInput   = document.getElementById('dashboardSearchInput');
    const searchBtn     = document.getElementById('dashboardSearchBtn');
    const searchModal   = document.getElementById('searchModal');
    const searchModalBody  = document.getElementById('searchModalBody');
    const searchModalClose = document.getElementById('searchModalClose');

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str ?? '';
        return div.innerHTML;
    }

    function getInitials(name) {
        const parts = (name || 'User').trim().split(' ');
        let initials = parts[0].charAt(0).toUpperCase();
        if (parts[1]) initials += parts[1].charAt(0).toUpperCase();
        return initials;
    }

    function openSearchModal() {
        searchModal.classList.add('active');
    }

    function closeSearchModal() {
        searchModal.classList.remove('active');
    }

    function runSearch() {
        const q = searchInput.value.trim();

        if (q === '') {
            searchModalBody.innerHTML = '<div class="search-modal-message">Type a name or email and hit search.</div>';
            openSearchModal();
            return;
        }

        searchModalBody.innerHTML = '<div class="search-modal-message">Searching…</div>';
        openSearchModal();

        fetch('search_users.php?q=' + encodeURIComponent(q))
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    searchModalBody.innerHTML = '<div class="search-modal-message">' + escapeHtml(data.error) + '</div>';
                    return;
                }

                if (!data.length) {
                    searchModalBody.innerHTML = '<div class="search-modal-message">No users found.</div>';
                    return;
                }

                searchModalBody.innerHTML = data.map(user => `
                    <div class="search-result-item">
                        <div class="search-result-avatar">${getInitials(user.name)}</div>
                        <div class="search-result-info">
                            <div class="search-result-name">${escapeHtml(user.name || 'Unknown User')}</div>
                            <div class="search-result-email">${escapeHtml(user.email || 'No email')}</div>
                        </div>
                    </div>
                `).join('');
            })
            .catch(() => {
                searchModalBody.innerHTML = '<div class="search-modal-message">Something went wrong. Please try again.</div>';
            });
    }

    searchBtn.addEventListener('click', runSearch);

    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            runSearch();
        }
    });

    searchModalClose.addEventListener('click', closeSearchModal);

    searchModal.addEventListener('click', function(e) {
        if (e.target === this) closeSearchModal();
    });
</script>
</body>
</html>