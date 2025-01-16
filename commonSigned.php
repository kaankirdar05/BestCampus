<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("logedinCheck.php");

// Database connection parameters
$server = "localhost";
$username = "root";
$password = "";
$database = "bestcampus";

$conn = mysqli_connect($server, $username, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");

// Check if the user is logged in and the session variables are not set
if (!isset($_SESSION['user_id']) && isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $sql = "SELECT * FROM `Users` WHERE `email` = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        // Set session variables
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['surname'] = $row['surname'];
        $_SESSION['role'] = $row['role'];
        $_SESSION['image_path'] = $row['image_path'] ?? 'uploads/default-profile.png';
    }
}

mysqli_close($conn);
?>