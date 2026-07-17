<?php
// admin/add-room.php
 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../confiq.php';   // DB connection
$error   = '';
$success = '';
 
 
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    $room_name   = trim($_POST['room_name']   ?? '');
    $price       = trim($_POST['price']       ?? '');
    $description = trim($_POST['description'] ?? '');
    $status      = trim($_POST['status']      ?? '');
    $image_path  = '';
    if (empty($room_name) || empty($price) || empty($description) || empty($status)) {
        $error = "All fields are required.";
    } elseif (!is_numeric($price) || $price <= 0) {
        $error = "Please enter a valid price.";
 
    } else {
        if (!empty($_FILES['image']['name'])) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
            $file_type     = mime_content_type($_FILES['image']['tmp_name']);
            $max_size      = 2 * 1024 * 1024;
 
            if (!in_array($file_type, $allowed_types)) {
                $error = "Only JPG, PNG, or WEBP images are allowed.";
            } elseif ($_FILES['image']['size'] > $max_size) {
                $error = "Image must be under 2MB.";
            } else {
                $ext        = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename   = 'room_' . uniqid() . '.' . $ext;
            
                $upload_dir = '/opt/lampp/htdocs/hotel/online-hotel-management-system-/uploads/rooms/';
 
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
 
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $filename)) {
                    $image_path = '../uploads/rooms/' . $filename;
                } else {
                    $error = "Failed to upload image. Please try again.";
                }
            }
            
        } else {
            $error = "Please upload a room image.";
        }
 
        if (empty($error)) {
            $stmt = $pdo->prepare("
                INSERT INTO rooms (room_name, price, image, description, status)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$room_name, $price, $image_path, $description, $status]);
            $success = "Room \"" . htmlspecialchars($room_name) . "\" has been added successfully.";
             header("Refresh: 2; url=view-room.php");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Room – BlueWave Admin</title>
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
 
        /* BREADCRUMB */
        .breadcrumb {
            padding: 0 0 24px 0;
            font-size: 0.78rem;
            color: var(--text-body);
            display: flex;
            align-items: center;
            gap: 8px;
        }
 
        .breadcrumb a { color: var(--text-body); text-decoration: none; transition: color .2s; }
        .breadcrumb a:hover { color: var(--gold); }
        .breadcrumb span { color: var(--navy); font-weight: 600; }
        .breadcrumb .sep { opacity: .4; }
 
        /* PAGE CONTENT */
        .page-header { margin-bottom: 32px; }
 
        .page-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            color: var(--navy);
            margin-bottom: 6px;
        }
 
        .page-header p { font-size: 0.9rem; color: var(--text-body); }
 
        /* CARD */
        .card {
            background: var(--white);
            border-radius: 6px;
            box-shadow: 0 2px 16px rgba(10,28,59,.07);
            overflow: hidden;
        }
 
        .card-header {
            padding: 20px 32px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
        }
 
        .card-icon {
            width: 40px;
            height: 40px;
            background: var(--navy);
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
 
        .card-icon svg { width: 20px; height: 20px; fill: var(--gold); }
 
        .card-header h2 { font-family: 'Playfair Display', serif; font-size: 1.2rem; color: var(--navy); }
        .card-header p  { font-size: 0.8rem; color: var(--text-body); margin-top: 2px; }
 
        .card-body { padding: 32px; }
 
        /* ALERTS */
        .alert {
            padding: 13px 16px;
            border-radius: 4px;
            font-size: 0.85rem;
            margin-bottom: 28px;
            font-weight: 500;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
 
        .alert svg { width: 18px; height: 18px; flex-shrink: 0; margin-top: 1px; }
        .alert-error   { background: #fdecea; color: #b71c1c; border-left: 3px solid #e53935; }
        .alert-success { background: #e8f5e9; color: #1b5e20; border-left: 3px solid #43a047; }
        .alert-error svg   { fill: #e53935; }
        .alert-success svg { fill: #43a047; }
 
        /* FORM GRID */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px 32px;
        }
 
        .form-grid .full { grid-column: 1 / -1; }
 
        .form-group { display: flex; flex-direction: column; }
 
        .form-group label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--navy);
            margin-bottom: 9px;
        }
 
        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 13px 15px;
            border: 1px solid var(--border);
            border-radius: 4px;
            outline: none;
            background: var(--white);
            font-family: 'Jost', sans-serif;
            font-size: 0.95rem;
            color: var(--navy);
            transition: border-color .25s, box-shadow .25s;
        }
 
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(201,168,76,.12);
        }
 
        .form-group input::placeholder,
        .form-group textarea::placeholder { color: #b0bad0; }
 
        .form-group select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%234a5a75' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 38px;
            cursor: pointer;
        }
 
        .form-group textarea { resize: vertical; min-height: 110px; line-height: 1.6; }
 
        /* Price prefix */
        .input-prefix { position: relative; }
        .input-prefix span {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.9rem;
            color: var(--text-body);
            font-weight: 600;
            pointer-events: none;
        }
        .input-prefix input { padding-left: 34px; }
 
        /* Status dots */
        .status-dots { display: flex; gap: 16px; margin-top: 10px; flex-wrap: wrap; }
        .dot-item { display: flex; align-items: center; gap: 6px; font-size: 0.75rem; color: var(--text-body); }
        .dot { width: 8px; height: 8px; border-radius: 50%; }
        .dot.available   { background: #43a047; }
        .dot.booked      { background: #fb8c00; }
        .dot.maintenance { background: #e53935; }
 
        /* Upload zone */
        .upload-zone {
            border: 2px dashed var(--border);
            border-radius: 6px;
            padding: 30px 20px;
            text-align: center;
            cursor: pointer;
            transition: border-color .25s, background .25s;
            position: relative;
            background: var(--off-white);
        }
 
        .upload-zone:hover, .upload-zone.dragover {
            border-color: var(--gold);
            background: #fdf9ef;
        }
 
        .upload-zone input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }
 
        .upload-icon {
            width: 48px;
            height: 48px;
            background: var(--navy);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 14px;
        }
 
        .upload-icon svg { width: 22px; height: 22px; fill: var(--gold); }
        .upload-zone h4 { font-size: 0.95rem; color: var(--navy); font-weight: 600; margin-bottom: 5px; }
        .upload-zone p  { font-size: 0.8rem; color: var(--text-body); }
        .upload-zone .browse { color: var(--gold); font-weight: 700; }
 
        #preview-wrap { display: none; margin-top: 16px; position: relative; }
        #preview-wrap img { width: 100%; max-height: 200px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border); }
 
        .remove-img {
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(6,15,34,.75);
            border: none;
            color: var(--white);
            width: 28px;
            height: 28px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            transition: background .2s;
        }
 
        .remove-img:hover { background: #e53935; }
 
        /* Form footer */
        .form-footer {
            margin-top: 34px;
            padding-top: 24px;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 14px;
        }
 
        .btn-cancel {
            padding: 13px 28px;
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-body);
            font-family: 'Jost', sans-serif;
            font-weight: 600;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            cursor: pointer;
            text-decoration: none;
            border-radius: 3px;
            transition: .25s;
        }
 
        .btn-cancel:hover { border-color: var(--navy); color: var(--navy); }
 
        .btn-submit {
            padding: 13px 36px;
            background: var(--navy-deep);
            color: var(--white);
            border: none;
            font-family: 'Jost', sans-serif;
            font-weight: 700;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            border-radius: 3px;
            transition: .25s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
 
        .btn-submit svg { width: 16px; height: 16px; fill: currentColor; }
        .btn-submit:hover { background: var(--gold); color: var(--navy-deep); }
 
        /* Responsive */
        @media (max-width: 992px) {
            .main-content { margin-left: 0; padding: 30px 20px; }
            .sidebar { display: none; } /* Consider changing to mobile toggle if needed later */
        }
        @media (max-width: 768px) {
            .form-grid { grid-template-columns: 1fr; }
            .form-grid .full { grid-column: 1; }
            .card-body { padding: 24px 20px; }
            .form-footer { flex-direction: column; }
            .btn-cancel, .btn-submit { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>
 
<aside class="sidebar">
    <div class="admin-logo">BlueWave <span>Admin</span></div>
    <ul class="sidebar-nav">
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="view_bookings.php">Bookings</a></li>
        <li><a href="view_rooms.php">Rooms</a></li>
        <li><a href="view_users.php">Users</a></li>
        <li><a href="manage_complaints.php">Complaints</a></li>
        <li><a href="add-room.php" class="active">Add Rooms</a></li>
    </ul>
    <div class="sidebar-footer">
        <a href="logout.php">Log Out</a>
    </div>
</aside>
 
<main class="main-content">
 
    <div class="breadcrumb">
        <a href="dashboard.php">Dashboard</a>
        <span class="sep">›</span>
        <a href="view_rooms.php">Rooms</a>
        <span class="sep">›</span>
        <span>Add New Room</span>
    </div>
 
    <div class="page-wrapper-inner">
 
        <div class="page-header">
            <h1>Add New Room</h1>
            <p>Fill in the details below to list a new room on BlueWave Hotel.</p>
        </div>
 
        <div class="card">
            <div class="card-header">
                <div class="card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M19 3H5a2 2 0 0 0-2 2v16h18V5a2 2 0 0 0-2-2zm-7 12a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 4H7V5h10v14z"/>
                    </svg>
                </div>
                <div>
                    <h2>Room Information</h2>
                    <p>All fields are required to save the room.</p>
                </div>
            </div>
 
            <div class="card-body">
 
                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
 
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M9 16.17 4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                        <?= htmlspecialchars($success) ?>
                    </div>
                <?php endif; ?>
 
                <form method="POST" action="add-room.php" enctype="multipart/form-data">
                    <div class="form-grid">
 
                        <div class="form-group">
                            <label>Room Name</label>
                            <input type="text" name="room_name" placeholder="e.g. Deluxe Ocean Suite"
                                   value="<?= htmlspecialchars($_POST['room_name'] ?? '') ?>" required>
                        </div>
 
                        <div class="form-group">
                            <label>Price per Night (₦)</label>
                            <div class="input-prefix">
                                <span>₦</span>
                                <input type="number" name="price" placeholder="0.00" min="1" step="0.01"
                                       value="<?= htmlspecialchars($_POST['price'] ?? '') ?>" required>
                            </div>
                        </div>
 
                        <div class="form-group">
                            <label>Room Status</label>
                            <select name="status" required>
                                <option value="" disabled <?= empty($_POST['status']) ? 'selected' : '' ?>>— Select status —</option>
                                <option value="available"   <?= (($_POST['status'] ?? '') === 'available')   ? 'selected' : '' ?>>Available</option>
                                <option value="booked"      <?= (($_POST['status'] ?? '') === 'booked')      ? 'selected' : '' ?>>Booked</option>
                                <option value="maintenance" <?= (($_POST['status'] ?? '') === 'maintenance') ? 'selected' : '' ?>>Under Maintenance</option>
                            </select>
                            <div class="status-dots">
                                <div class="dot-item"><div class="dot available"></div> Available</div>
                                <div class="dot-item"><div class="dot booked"></div> Booked</div>
                                <div class="dot-item"><div class="dot maintenance"></div> Maintenance</div>
                            </div>
                        </div>
 
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" placeholder="Describe the room — amenities, view, bed type…" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                        </div>
 
                        <div class="form-group full">
                            <label>Room Image</label>
                            <div class="upload-zone" id="uploadZone">
                                <input type="file" name="image" id="imageInput" accept="image/jpeg,image/png,image/webp" required>
                                <div class="upload-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path d="M21 19V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2zM8.5 13.5l2.5 3 3.5-4.5 4.5 6H5l3.5-4.5z"/>
                                    </svg>
                                </div>
                                <h4>Drag & drop your image here</h4>
                                <p>or <span class="browse">browse files</span> &nbsp;·&nbsp; JPG, PNG, WEBP &nbsp;·&nbsp; Max 2MB</p>
                            </div>
 
                            <div id="preview-wrap">
                                <img id="preview-img" src="" alt="Room preview">
                                <button type="button" class="remove-img" id="removeImg" title="Remove image">✕</button>
                            </div>
                        </div>
 
                    </div>
 
                    <div class="form-footer">
                        <a href="view_rooms.php" class="btn-cancel">Cancel</a>
                        <button type="submit" class="btn-submit">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M9 16.17 4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                            Save Room
                    </button>
                    </div>
 
                </form>
            </div>
        </div>
 
    </div>
</main>
 
<script>
    const input       = document.getElementById('imageInput');
    const zone        = document.getElementById('uploadZone');
    const previewWrap = document.getElementById('preview-wrap');
    const previewImg  = document.getElementById('preview-img');
    const removeBtn   = document.getElementById('removeImg');
 
    function showPreview(file) {
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            previewImg.src = e.target.result;
            previewWrap.style.display = 'block';
            zone.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
 
    input.addEventListener('change', () => showPreview(input.files[0]));
 
    removeBtn.addEventListener('click', () => {
        input.value = '';
        previewWrap.style.display = 'none';
        zone.style.display = 'block';
        previewImg.src = '';
    });
 
    zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('dragover'); });
    zone.addEventListener('dragleave', () => zone.classList.remove('dragover'));
    zone.addEventListener('drop', e => {
        e.preventDefault();
        zone.classList.remove('dragover');
        const file = e.dataTransfer.files[0];
        if (file) {
            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            showPreview(file);
        }
    });
</script>
 
</body>
</htm