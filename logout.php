<?php
// logout.php - QuickBite Project

// Step 1: Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Step 2: Unset all session variables
$_SESSION = array();

// Step 3: Destroy the session
session_destroy();

// Step 4: Redirect to login page with a short delay message
header("Location: login.php?msg=loggedout");
exit();
?>