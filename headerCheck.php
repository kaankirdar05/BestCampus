<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
} // Start the session

// Check if the user is not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    include("header.php");
}
else{
    include("headerSigned.php");
}
?>