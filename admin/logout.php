<?php
session_start();
session_unset();    // Clear all session variables
session_destroy();  // Destroy the session completely

// Redirect back to login
header("Location: login.php");
exit();
?>