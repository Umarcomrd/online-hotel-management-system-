<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <nav class="main-nav">
    <div class="nav-container">
        <a href="index.php" class="nav-logo">Blue<span>Wave</span></a>
        
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="rooms.php">Rooms</a></li>
            <li><a href="services.php">Services</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>

        <div class="nav-actions">
            <?php if(basename($_SERVER['PHP_SELF']) !== 'booking.php'): ?>
                <button class="nav-cta" onclick="location.href='booking.php'">Book Now</button>
            <?php endif; ?>
        </div>
    </div>
</nav>
</body>
</html>