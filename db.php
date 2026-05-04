<?php
// Database configuration
$host = "localhost";
$dbname = "hotel_managementsystem";
$username = "root";
$password = "ug23sccs10441";

// PDO connection with error handling
try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $username,
        $password
    );

    // Set error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Optional: disable emulated prepares (more secure)
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    // echo "Database connected successfully"; // (for testing only)

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>