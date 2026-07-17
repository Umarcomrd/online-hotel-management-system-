
<?php
// admin/view_rooms.php
require_once '../confiq.php';
 
// Handle delete
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM rooms WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: view_rooms.php?deleted=1');
    exit;
}
 
// Fetch all rooms
 
try {
    $stmt = $pdo->query("SELECT * FROM rooms ORDER BY id DESC");
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching rooms: " . $e->getMessage());
 
}
try {
    $stmt = $pdo->query("SELECT * FROM rooms ORDER BY id DESC");
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching rooms: " . $e->getMessage());
    
}
try {
    $stmt = $pdo->query("SELECT * FROM rooms ORDER BY id DESC");
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching rooms: " . $e->getMessage());
    
}
try {
    $stmt = $pdo->query("SELECT * FROM rooms ORDER BY id DESC");
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching rooms: " . $e->getMessage());
    
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Rooms - BlueWave Admin</title>
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
 
        .badge {
            font-size: 0.7rem;
            padding: 3px 10px;
            border-radius: 10px;
            text-transform: uppercase;
            font-weight: bold;
        }
 
        .status-available   { background: #e8f5e9; color: #2e7d32; }
        .status-booked      { background: #fff3e0; color: #ef6c00; }
        .status-maintenance { background: #ffebee; color: #c62828; }
 
        .room-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }
 
        .room-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
 
        .room-thumb {
            height: 180px;
            background-size: cover;
            background-position: center;
            background-color: #eee;
        }
 
        .room-body {
            padding: 18px;
        }
 
        .room-header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
        }
 
        .room-name {
            font-family: 'Playfair Display', serif;
            font-size: 1rem;
            color: #060f22;
            margin: 0;
        }
 
        .room-price {
            font-size: 0.88rem;
            color: #555;
            margin: 6px 0 10px;
        }
 
        .room-desc {
            font-size: 0.8rem;
            color: #888;
            line-height: 1.5;
            height: 3em;
            overflow: hidden;
            margin-bottom: 15px;
        }
 
        .action-row {
            display: flex;
            gap: 10px;
        }
 
        .btn-edit,
        .btn-delete {
            flex: 1;
            padding: 9px;
            text-align: center;
            text-decoration: none;
            font-size: 0.83rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            font-family: 'Jost', sans-serif;
            transition: 0.2s;
        }
 
        .btn-edit {
            background: #f0f4ff;
            color: #060f22;
            border: 1px solid #dde2f0;
        }
 
        .btn-edit:hover {
            background: #060f22;
            color: white;
        }
 
        .btn-delete {
            background: #fff0f0;
            color: #c62828;
            border: 1px solid #ffd5d5;
        }
 
        .btn-delete:hover {
            background: #c62828;
            color: white;
        }
 
        /* Success toast */
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #2e7d32;
            color: white;
            padding: 14px 22px;
            font-size: 0.9rem;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            z-index: 9999;
            animation: fadeOut 0.5s ease 3s forwards;
        }
 
        @keyframes fadeOut {
            to { opacity: 0; pointer-events: none; }
        }
 
        /* Confirm modal overlay */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 5000;
            justify-content: center;
            align-items: center;
        }
 
        .modal-overlay.active { display: flex; }
 
        .modal-box {
            background: white;
            padding: 35px;
            max-width: 420px;
            width: 90%;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
 
        .modal-box h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            color: #060f22;
            margin-bottom: 10px;
        }
 
        .modal-box p {
            color: #666;
            font-size: 0.92rem;
            margin-bottom: 25px;
        }
 
        .modal-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
        }
 
        .modal-cancel {
            padding: 10px 28px;
            border: 1px solid #ddd;
            background: white;
            color: #333;
            cursor: pointer;
            font-family: 'Jost', sans-serif;
            font-weight: 600;
        }
 
        .modal-confirm {
            padding: 10px 28px;
            background: #c62828;
            color: white;
            border: none;
            cursor: pointer;
            font-family: 'Jost', sans-serif;
            font-weight: 600;
        }
 
        .modal-confirm:hover { background: #a00; }

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
 
<?php if(isset($_GET['deleted'])): ?>
    <div class="toast">✓ Room deleted successfully.</div>
<?php endif; ?>
 
<!-- Delete Confirmation Modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <h3>Delete Room?</h3>
        <p>This action cannot be undone. The room will be permanently removed from the database.</p>
        <div class="modal-actions">
            <button class="modal-cancel" onclick="closeModal()">Cancel</button>
            <a href="#" class="modal-confirm" id="confirmDeleteBtn">Yes, Delete</a>
        </div>
    </div>
</div>
 
<aside class="sidebar">
    <div class="admin-logo">BlueWave <span>Admin</span></div>
    <ul class="sidebar-nav">
        <li><a href="dashboard.php">📊<span>Dashboard</span></a></li>
        <li><a href="view_bookings.php">📅<span>Bookings</span></a></li>
        <li><a href="view_rooms.php" class="active">🛏️<span>Rooms</span></a></li>
        <li><a href="view_users.php">👥<span>Users</span></a></li>
        <li><a href="manage_complaints.php">📋<span>Complaints</span></a></li>
        <li><a href="add-room.php">➕<span>Add rooms</span></a></li>
    </ul>
    <div class="sidebar-footer">
        <a href="logout.php">🚪<span>Log Out</span></a>
    </div>
</aside>
 
<main class="main-content">
 
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
        <h1 style="font-family:'Playfair Display'; color:#060f22;">Manage Rooms</h1>
        <a href="add-room.php" style="background:#060f22; color:white; padding:10px 22px; text-decoration:none; font-weight:600; font-size:0.9rem;">
            + Add New Room
        </a>
    </div>
 
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px,1fr)); gap:25px;">
 
        <?php if(count($rooms) > 0): ?>
 
            <?php foreach($rooms as $room): ?>
 
                <div class="room-card">
 
                  
                
 
                <div class="room-thumb"
     style="background-image: url('<?= htmlspecialchars($room['image']) ?>');">
</div>
 
 
 
 
                    <div class="room-body">
 
                        <div class="room-header-row">
                            <h4 class="room-name"><?= htmlspecialchars($room['room_name']) ?></h4>
                            <span class="badge status-<?= htmlspecialchars($room['status']) ?>">
                                <?= ucfirst($room['status']) ?>
                            </span>
                        </div>
 
                        <p class="room-price">
                            ₦<?= number_format($room['price'], 2) ?> / night
                        </p>
 
                        <p class="room-desc">
                            <?= htmlspecialchars($room['description']) ?>
                        </p>
 
                        <div class="action-row">
                            <a href="edit_room.php?id=<?= $room['id'] ?>" class="btn-edit">
                                ✏ Edit
                            </a>
                            <button
                                class="btn-delete"
                                onclick="confirmDelete(<?= $room['id'] ?>, '<?= htmlspecialchars($room['room_name'], ENT_QUOTES) ?>')">
                                🗑 Delete
                            </button>
                        </div>
 
                    </div>
 
                </div>
 
            <?php endforeach; ?>
 
        <?php else: ?>
 
            <div style="grid-column:1/-1; text-align:center; padding:60px; background:white; border:1px dashed #ccc;">
                <p style="color:#888;">No rooms found. Click "+ Add New Room" to get started.</p>
            </div>
 
        <?php endif; ?>
 
    </div>
 
</main>
 
<script>
    function confirmDelete(id, name) {
        document.getElementById('confirmDeleteBtn').href = 'view_rooms.php?delete=' + id;
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