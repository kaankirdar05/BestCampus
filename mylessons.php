<?php

    if (session_status() == PHP_SESSION_NONE) {
    session_start();
    } // Start the session
  
// Include the common file
include("logedinCheck.php");
include("commonSigned.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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

$user_id = $_SESSION['user_id']; // Using the session variable set in commonSigned.php
$role = $_SESSION['role']; // 0 for student, 1 for teacher

$current_date = date('Y-m-d'); // Get the current date

// Fetch lessons for students
$student_lessons_sql = "
    SELECT 
        Bookings.booking_id,
        Bookings.booking_date,
        Bookings.online_meeting_link,
        Users.name AS student_name,
        Users.surname AS student_surname,
        Teacher.name AS teacher_name,
        Teacher.surname AS teacher_surname,
        Lessons.lesson_name,
        Bookings.lesson_time,
        Bookings.start_time,
        Bookings.end_time
    FROM Bookings
    JOIN Users ON Bookings.student_id = Users.user_id
    JOIN Users AS Teacher ON Bookings.teacher_id = Teacher.user_id
    JOIN Lessons ON Bookings.lesson_id = Lessons.lesson_id
    WHERE Users.user_id = $user_id AND Bookings.lesson_time >= '$current_date'
    ORDER BY Bookings.lesson_time ASC, Bookings.start_time ASC";

$student_lessons_result = mysqli_query($conn, $student_lessons_sql);

// Fetch lessons for teachers
$teacher_given_lessons_sql = "
    SELECT 
        Bookings.booking_id,
        Bookings.booking_date,
        Bookings.online_meeting_link,
        Student.name AS student_name,
        Student.surname AS student_surname,
        Users.name AS teacher_name,
        Users.surname AS teacher_surname,
        Lessons.lesson_name,
        Bookings.lesson_time,
        Bookings.start_time,
        Bookings.end_time
    FROM Bookings
    JOIN Users ON Bookings.teacher_id = Users.user_id
    JOIN Users AS Student ON Bookings.student_id = Student.user_id
    JOIN Lessons ON Bookings.lesson_id = Lessons.lesson_id
    WHERE Users.user_id = $user_id AND Bookings.lesson_time >= '$current_date'
    ORDER BY Bookings.lesson_time ASC, Bookings.start_time ASC";

$teacher_given_lessons_result = mysqli_query($conn, $teacher_given_lessons_sql);

mysqli_close($conn);

// Function to convert month names to Turkish
function turkish_date_format($date) {
    $english_months = array(
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    );
    $turkish_months = array(
        "Ocak", "Şubat", "Mart", "Nisan", "Mayıs", "Haziran",
        "Temmuz", "Ağustos", "Eylül", "Ekim", "Kasım", "Aralık"
    );
    return str_replace($english_months, $turkish_months, date("d F Y", strtotime($date)));
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>My Lessons - BestCampus</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">

    <style>
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .content-container {
            width: 80%;
        }
        .table-container {
            margin-top: 20px;
        }
        td a {
            word-break: break-word; /* Ensures long links wrap to the next line */
        }
        table {
            table-layout: fixed; /* Ensures table columns have minimum size */
            width: 100%; /* Ensures table width is 100% */
            min-width: 600px; /*!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!*/ /* Ensures table has a minimum width to fit "No lessons booked." */ /*!!!!adjust this code for different screen sizes!!!!*/
        }
        th, td {
            word-wrap: break-word; /* Ensures table content wraps within columns */
        }
        footer {
            padding: 10px 0;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>

    <?php
        include('headerCheck.php');
    ?>

    <main id="main" class="main">
        <section class="section">
            <div class="container content-container">
                <div class="row justify-content-center">
                    <div class="col-lg-12 table-container">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title text-center">My Lessons</h3>
                                
                                <?php if ($role == 0) { ?>
                                    <!-- Student Lessons Table -->
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col">Lesson</th>
                                                <th scope="col">Teacher</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">Time</th>
                                                <th scope="col">Meeting Link</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (mysqli_num_rows($student_lessons_result) > 0) {
                                                while ($row = mysqli_fetch_assoc($student_lessons_result)) {
                                                    $formatted_date = turkish_date_format($row['lesson_time']);
                                                    $formatted_start_time = date("H:i", strtotime($row['start_time']));
                                                    $formatted_end_time = date("H:i", strtotime($row['end_time']));
                                                    echo "<tr>";
                                                    echo "<td>" . $row['lesson_name'] . "</td>";
                                                    echo "<td>" . $row['teacher_name'] . " " . $row['teacher_surname'] . "</td>";
                                                    echo "<td>" . $formatted_date . "</td>";
                                                    echo "<td>" . $formatted_start_time . " - " . $formatted_end_time . "</td>";
                                                    echo "<td><a href='" . $row['online_meeting_link'] . "' target='_blank'>" . $row['online_meeting_link'] . "</a></td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='5' class='text-center'>No lessons booked.</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                <?php } else if ($role == 1) { ?>
                                    <!-- Teacher Given Lessons Table -->
                                    <h5>Lessons Given</h5>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col">Lesson</th>
                                                <th scope="col">Student</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">Time</th>
                                                <th scope="col">Meeting Link</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (mysqli_num_rows($teacher_given_lessons_result) > 0) {
                                                while ($row = mysqli_fetch_assoc($teacher_given_lessons_result)) {
                                                    $formatted_date = turkish_date_format($row['lesson_time']);
                                                    $formatted_start_time = date("H:i", strtotime($row['start_time']));
                                                    $formatted_end_time = date("H:i", strtotime($row['end_time']));
                                                    echo "<tr>";
                                                    echo "<td>" . $row['lesson_name'] . "</td>";
                                                    echo "<td>" . $row['student_name'] . " " . $row['student_surname'] . "</td>";
                                                    echo "<td>" . $formatted_date . "</td>";
                                                    echo "<td>" . $formatted_start_time . " - " . $formatted_end_time . "</td>";
                                                    echo "<td><a href='" . $row['online_meeting_link'] . "' target='_blank'>" . $row['online_meeting_link'] . "</a></td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='5' class='text-center'>No lessons given.</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>

                                    <!-- Teacher Taken Lessons Table -->
                                    <h5>Lessons Taken</h5>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col">Lesson</th>
                                                <th scope="col">Teacher</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">Time</th>
                                                <th scope="col">Meeting Link</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (mysqli_num_rows($student_lessons_result) > 0) {
                                                while ($row = mysqli_fetch_assoc($student_lessons_result)) {
                                                    $formatted_date = turkish_date_format($row['lesson_time']);
                                                    $formatted_start_time = date("H:i", strtotime($row['start_time']));
                                                    $formatted_end_time = date("H:i", strtotime($row['end_time']));
                                                    echo "<tr>";
                                                    echo "<td>" . $row['lesson_name'] . "</td>";
                                                    echo "<td>" . $row['teacher_name'] . " " . $row['teacher_surname'] . "</td>";
                                                    echo "<td>" . $formatted_date . "</td>";
                                                    echo "<td>" . $formatted_start_time . " - " . $formatted_end_time . "</td>";
                                                    echo "<td><a href='" . $row['online_meeting_link'] . "' target='_blank'>" . $row['online_meeting_link'] . "</a></td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='5' class='text-center'>No lessons booked.</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                <?php } ?>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- ======= Footer ======= -->
    <?php
    include("footer.php");
    ?>
    <!-- End Footer -->

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/js/main.js"></script>

</body>

</html>

