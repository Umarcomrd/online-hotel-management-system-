
<?php
require_once '../confiq.php';

// Check ID
if (!isset($_GET['id'])) {
    die("Room ID missing.");
}

$id = intval($_GET['id']);

// Fetch room
$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->execute([$id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$room) {
    die("Room not found.");
}

// Update room
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $room_name   = trim($_POST['room_name']);
    $price       = trim($_POST['price']);
    $status      = trim($_POST['status']);
    $description = trim($_POST['description']);

    // Keep old image by default
    $image = $room['image'];







    


    
    // Upload new image if provided
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {

         $upload_dir = '/opt/lampp/htdocs/hotel/uploads/rooms/';


        // Create uploads folder if not exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Generate unique filename
        $image_name = time() . "_" . basename($_FILES['image']['name']);
        $target_file = '../uploads/rooms/' . $image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Save only filename in DB
            $image = $image_name;
        }
    }

    try {
        $update = $pdo->prepare("
            UPDATE rooms 
            SET room_name = ?, 
                price = ?, 
                status = ?, 
                description = ?, 
                image = ?
            WHERE id = ?
        ");

        $update->execute([
            $room_name,
            $price,
            $status,
            $description,
            $image,
            $id
        ]);

        header("Location: view_rooms.php?updated=1");
        exit;

    } catch (PDOException $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Room</title>
<link rel="stylesheet" href="admin_style.css">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Jost:wght@400;500;600&display=swap" rel="stylesheet">
<style>
:root {
    --navy-deep: #060f22;
    --navy-mid: #0d1b3d;
    --gold: #d4af37;
}

body {
    margin: 0;
    font-family: 'Jost', sans-serif;
    background: #f5f7fa;
    color: var(--navy-deep);
}

.admin-wrapper {
    display: flex;
    min-height: 100vh;
}

.sidebar {
    width: 220px;
    background: var(--navy-deep);
    color: white;
    padding: 20px;
}

.sidebar h2 {
    font-family: 'Playfair Display', serif;
    font-size: 1.4rem;
    margin-bottom: 20px;
}

.sidebar-nav {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar-nav li {
    margin-bottom: 15px;
}

.sidebar-nav a {
    color: white;
    text-decoration: none;
    font-weight: 500;
    transition: 0.2s;
}

.sidebar-nav a.active,
.sidebar-nav a:hover {
    color: var(--gold);
}

.main-content {
    flex: 1;
    padding: 40px;
}

.page-title {
    font-family: 'Playfair Display', serif;
    font-size: 2rem;
    margin-bottom: 30px;
}

.edit-container {
    max-width: 850px;
    margin: auto;
    background: white;
    padding: 40px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 8px 25px rgba(0,0,0,0.05);
}

.form-group {
    margin-bottom: 22px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--navy-deep);
    font-size: 0.95rem;
}

.form-control {
    width: 100%;
    padding: 13px 15px;
    border: 1px solid #dbe1ea;
    font-size: 0.95rem;
    outline: none;
    transition: 0.2s;
}

.form-control:focus {
    border-color: var(--navy-deep);
}

textarea.form-control {
    min-height: 130px;
    resize: vertical;
}

.image-preview {
    width: 100%;
    height: 280px;
    object-fit: cover;
    border: 1px solid #ddd;
    margin-top: 15px;
}

.button-row {
    display: flex;
    gap: 15px;
    margin-top: 30px;
}

.btn-save {
    background: var(--navy-deep);
    color: white;
    border: none;
    padding: 13px 28px;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.92rem;
    transition: 0.2s;
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
.btn-save:hover {
    background: var(--navy-mid);
}

.btn-back {
    background: #f0f4ff;
    color: var(--navy-deep);
    border: 1px solid #dde2f0;
    padding: 13px 28px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.92rem;
    transition: 0.2s;
}

.btn-back:hover {
    background: var(--navy-deep);
    color: white;
}

.error-box {
    background: #ffebee;
    color: #c62828;
    padding: 14px;
    margin-bottom: 20px;
    border-left: 4px solid #c62828;
}
</style>
</head>
<body>

<aside class="sidebar">
     <div class="admin-logo">BlueWave <span>Admin</span></div>
    <ul class="sidebar-nav">
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="view_bookings.php">Bookings</a></li>
        <li><a href="view_rooms.php" class="active">Rooms</a></li>
        <li><a href="view_users.php">Users</a></li>
        <li><a href="manage_complaints.php">Complaints</a></li>
        <li><a href="add-room.php">Add Rooms</a></li>
        <li style="margin-top:50px;"><a href="logout.php">Logout</a></li>
    </ul>
</aside>

<main class="main-content">
<div class="edit-container">
    <h1 class="page-title">Edit Room</h1>

    <?php if (isset($error)): ?>
        <div class="error-box"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Room Name</label>
            <input type="text" name="room_name" class="form-control" 
                   value="<?= htmlspecialchars($room['room_name']) ?>" required>
        </div>

        <div class="form-group">
            <label>Price Per Night</label>
            <input type="number" step="0.01" name="price" class="form-control" 
                   value="<?= htmlspecialchars($room['price']) ?>" required>
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="available" <?= ($room['status'] == 'available') ? 'selected' : '' ?>>Available</option>
                <option value="booked" <?= ($room['status'] == 'booked') ? 'selected' : '' ?>>Booked</option>
                <option value="maintenance" <?= ($room['status'] == 'maintenance') ? 'selected' : '' ?>>Maintenance</option>
            </select>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" required><?= htmlspecialchars($room['description']) ?></textarea>
        </div>

        <div class="form-group">
            <label>Upload New Image</label>
            <input type="file" name="image" class="form-control">
            <?php if (!empty($room['image'])): ?>
                <img src="../uploads/<?= htmlspecialchars($room['image']) ?>" class="image-preview">
            <?php endif; ?>
        </div>

        <div class="button-row">
            <button type="submit" class="btn-save">Update Room</button>
            <a href="view_rooms.php" class="btn-back">Cancel</a>
        </div>
    </form>
</div>
</main>
</body>
</html>
