<?php
    
if (session_status() == PHP_SESSION_NONE) {
session_start();
} // Start the session

$server = "localhost";
$username = "root";
$password = "";
$database = "bestcampus";

// Create connection
$conn = new mysqli($server, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

mysqli_set_charset($conn, "utf8mb4");

// Clear all existing availability and reset auto-increment
$conn->query("TRUNCATE TABLE Availability");

// Calculate the date range (4 days from today and 21 days from today)
$today = new DateTime();
$start_date = $today->modify('+4 days')->format('Y-m-d');
$end_date = (new DateTime())->modify('+21 days')->format('Y-m-d');

// Insert new availability based on Teacher_availability and Teacher_lessons
$sql = "
    INSERT INTO Availability (teacher_id, lesson_id, available_date, start_time, end_time)
    SELECT 
        ta.teacher_id, 
        tl.lesson_id, 
        DATE_ADD(CURDATE(), INTERVAL days.num DAY) AS available_date,
        ta.start_time, 
        ta.end_time
    FROM 
        Teacher_availability ta
    JOIN 
        Teacher_lessons tl ON ta.teacher_id = tl.teacher_id
    JOIN 
        (SELECT 0 AS num UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL 
         SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL 
         SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12 UNION ALL SELECT 13 UNION ALL SELECT 14 UNION ALL 
         SELECT 15 UNION ALL SELECT 16 UNION ALL SELECT 17 UNION ALL SELECT 18 UNION ALL SELECT 19 UNION ALL 
         SELECT 20 UNION ALL SELECT 21) days
    WHERE 
        days.num BETWEEN 4 AND 21
    AND 
        NOT EXISTS (
            SELECT 1 FROM Teacher_off_dates tod 
            WHERE 
                tod.teacher_id = ta.teacher_id 
                AND tod.off_date = DATE_ADD(CURDATE(), INTERVAL days.num DAY)
                AND tod.start_time = ta.start_time 
                AND tod.end_time = ta.end_time
        )
    AND 
        DAYOFWEEK(DATE_ADD(CURDATE(), INTERVAL days.num DAY)) - 1 = 
        CASE ta.day_of_week 
            WHEN 'Monday' THEN 1 
            WHEN 'Tuesday' THEN 2 
            WHEN 'Wednesday' THEN 3 
            WHEN 'Thursday' THEN 4 
            WHEN 'Friday' THEN 5 
            WHEN 'Saturday' THEN 6 
            WHEN 'Sunday' THEN 0 
        END
";

if ($conn->query($sql) === TRUE) {
    echo "Availability updated successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the connection
$conn->close();
?>
