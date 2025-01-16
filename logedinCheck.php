<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
} // Start the session

// Check if the user is not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    session_unset();
    session_destroy();
    
    header("location: index.php");
    exit;
}
?>