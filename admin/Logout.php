<?php
// admin/logout.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Clear all admin session data
session_unset();
session_destroy();

// Redirect to admin login
header('Location: login.php');
exit;