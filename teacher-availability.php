<?php
    
if (session_status() == PHP_SESSION_NONE) {
session_start();
} // Start the session

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

$error_message = "";
$success_message = "";
$action = "";

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

// Function to convert day names to Turkish
function turkish_days_format($day) {
    $english_days = array(
        "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"
    );
    $turkish_days = array(
        "Pazartesi", "Salı", "Çarşamba", "Perşembe", "Cuma", "Cumartesi", "Pazar"
    );
    return str_replace($english_days, $turkish_days, $day);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Handle saving availability in database
    if (isset($_POST['availability'])) {
        $action = "availability";
        $teacher_id = $_SESSION['user_id'];
        $role = $_SESSION['role'];
        $days = isset($_POST['day']) ? $_POST['day'] : [];
        $start_times = isset($_POST['start-time']) ? $_POST['start-time'] : [];

        // Ensure the user is a teacher
        if ($role == 1) {
            // Map the selected days to their corresponding start times
            foreach ($days as $index => $day) {
                // Calculate the index of the current day in the week
                $day_index = array_search($day, ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);

                // Check if start time for the current day is not empty
                if (!empty($start_times[$day_index])) {
                    $start_time = $start_times[$day_index] . ':00';
                    $end_time = date('H:i:s', strtotime($start_time) + 3600); // Add 1 hour to start time

                    // Check if the same day and time already exists
                    $sql_check = "SELECT * FROM Teacher_availability WHERE teacher_id = '$teacher_id' AND day_of_week = '$day' AND start_time = '$start_time' AND end_time = '$end_time'";
                    $result_check = mysqli_query($conn, $sql_check);

                    if (mysqli_num_rows($result_check) == 0) {
                        $sql_insert = "INSERT INTO Teacher_availability (teacher_id, day_of_week, start_time, end_time) VALUES ('$teacher_id', '$day', '$start_time', '$end_time')";
                        if (!mysqli_query($conn, $sql_insert)) {
                            $error_message = "Error: " . mysqli_error($conn);
                        } else {
                            $success_message = "Availability saved successfully!";
                        }
                    } else {
                        $turkish_day = turkish_days_format($day);
                        $error_message = "Availability already exists for $turkish_day from " . substr($start_time, 0, 5) . " to " . substr($end_time, 0, 5);
                    }
                }
            }

            // Clear POST data to prevent re-submission
            $_POST = [];
        } else {
            $error_message = "Unauthorized access. Only teachers can set availability.";
        }
    }

    // Handle deletion of availability
    if (isset($_POST['delete_availability'])) {
        $action = "delete_availability";
        $availability_id = $_POST['delete_availability'];
        $sql_delete = "DELETE FROM Teacher_availability WHERE availability_id = '$availability_id'";
        if (!mysqli_query($conn, $sql_delete)) {
            $error_message = "Error: " . mysqli_error($conn);
        } else {
            $success_message = "Availability deleted successfully!";
        }
    }

    // Handle deletion of off day
    if (isset($_POST['delete_off_day'])) {
        $action = "delete_off_day";
        $off_date_id = $_POST['delete_off_day'];
        $sql_delete_off = "DELETE FROM Teacher_off_dates WHERE off_date_id = '$off_date_id'";
        if (!mysqli_query($conn, $sql_delete_off)) {
            $error_message = "Error: " . mysqli_error($conn);
        } else {
            $success_message = "Off day deleted successfully!";
        }
    }
}

// Retrieve teacher's availability in chronological order
$teacher_id = $_SESSION['user_id'];
$sql_availability = "SELECT availability_id, day_of_week, start_time, end_time 
                     FROM Teacher_availability 
                     WHERE teacher_id = '$teacher_id' 
                     ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), start_time";
$result_availability = mysqli_query($conn, $sql_availability);
$availability_data = [];
if ($result_availability) {
    while ($row = mysqli_fetch_assoc($result_availability)) {
        $availability_data[] = $row;
    }
}

// Retrieve teacher's off days in chronological order
$sql_off_days = "SELECT off_date_id, off_date, start_time, end_time 
                 FROM Teacher_off_dates 
                 WHERE teacher_id = '$teacher_id' 
                 ORDER BY off_date ASC, start_time ASC";
$result_off_days = mysqli_query($conn, $sql_off_days);
$off_days_data = [];
if ($result_off_days) {
    while ($row = mysqli_fetch_assoc($result_off_days)) {
        $off_days_data[] = $row;
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Mentor Bootstrap Template - Index</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Mentor
  * Updated: Sep 18 2023 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/mentor-free-education-bootstrap-theme/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->

  <style>
    .day-container, .availability-container, .off-day-container {
        background-color: white;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        padding: 20px;
        margin-top: 20px;
    }

    form {
        display: flex;
        flex-direction: column;
    }

    .form-group {
        margin-bottom: 15px;
    }

    label {
        font-size: 18px;
        margin-right: 10px;
    }

    .time-range {
        display: flex;
        flex-direction: column;
    }

    .time-range div {
        margin-bottom: 10px;
    }

    .time-range label {
        margin-right: 10px;
    }

    .time-range input[type="time"] {
        margin-right: 10px;
    }

    .btn-submit, .btn-go, .btn-delete {
        background-color: #5fcf80;
        color: white;
        padding: 5px 10px;
        border: none;
        border-radius: 50px;
        cursor: pointer;
        transition: background-color 0.3s;
        text-align: center;
        text-decoration: none;
    }

    .btn-submit:hover, .btn-go:hover, .btn-delete:hover {
        background-color: #3ac162;
    }

    .btn-submit {
        width: 20rem;
        margin: 0 auto;
    }

    .btn-delete {
        background-color: #e74c3c;
        margin-left: 5px;
        font-size: 0.7rem;
    }

    .btn-delete:hover {
        background-color: #c0392b;
    }

    .content-wrapper {
        padding-top: 5rem; /* Adjusted to use rem units */
    }

    .message {
        margin-top: 15px;
        padding: 10px;
        border-radius: 5px;
    }

    .error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table, th, td {
        border: 1px solid #ddd;
    }

    th, td {
        padding: 10px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    .availability-container h3, .off-day-container h3 {
        text-align: start;
        margin-bottom: 20px;
    }

    .off-day-container .info {
        text-align: center;
        margin-bottom: 10px;
    }

    .off-day-container .btn-go {
        display: block;
        margin: 0 auto;
        width:  20rem; /*fit-content;*/ /* Set a specific width to make the button smaller */
    }
  </style>
</head>

<body>

  <!-- ======= Header ======= -->
  <?php include("headerCheck.php");  ?>
  <!-- End Header -->    

  <main class="content-wrapper">
    <div class="container">
        <!-- Availability and Off Days Table Section -->
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="availability-container">
                    <h3>My Availability</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Day</th>
                                <th>Start Hour</th>
                                <th>End Hour</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($availability_data)): ?>
                            <?php foreach ($availability_data as $availability): ?>
                            <tr>
                                <td><?php echo turkish_days_format($availability['day_of_week']); ?></td>
                                <td><?php echo date('H:i', strtotime($availability['start_time'])); ?></td>
                                <td><?php echo date('H:i', strtotime($availability['end_time'])); ?></td>
                                <td>
                                    <form method="POST" action="" style="display:inline;">
                                        <input type="hidden" name="delete_availability" value="<?php echo $availability['availability_id']; ?>">
                                        <button type="submit" class="btn-delete">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="4">No availability found</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <div id="availability-message-container">
                        <?php if ($action == "delete_availability" && ($error_message || $success_message)): ?>
                            <div class="message <?php echo $error_message ? 'error' : 'success'; ?>">
                                <?php echo $error_message ?: $success_message; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class = "mt-4" > </div>
                    <h3>My Off Days</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Start Hour</th>
                                <th>End Hour</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($off_days_data)): ?>
                            <?php foreach ($off_days_data as $off_day): ?>
                            <tr>
                                <td><?php echo turkish_date_format($off_day['off_date']); ?></td>
                                <td><?php echo date('H:i', strtotime($off_day['start_time'])); ?></td>
                                <td><?php echo date('H:i', strtotime($off_day['end_time'])); ?></td>
                                <td>
                                    <form method="POST" action="" style="display:inline;">
                                        <input type="hidden" name="delete_off_day" value="<?php echo $off_day['off_date_id']; ?>">
                                        <button type="submit" class="btn-delete">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="4">No off days found</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <div id="off-day-message-container">
                        <?php if ($action == "delete_off_day" && ($error_message || $success_message)): ?>
                            <div class="message <?php echo $error_message ? 'error' : 'success'; ?>">
                                <?php echo $error_message ?: $success_message; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="day-container">
                    <form id="availability-form" method="POST" action="">
                        <input type="hidden" name="availability" value="1">
                        <div class="form-group">
                            <label for="days">Select Days and Start Times:</label>
                            <div id="days">
                                <?php
                                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                foreach ($days as $day) {
                                    echo "<div class='time-range'>";
                                    echo "<label><input type='checkbox' name='day[]' value='$day'> ";
                                    echo turkish_days_format($day);
                                    echo "</label>";
                                    echo "<div>";
                                    echo "<label for='start-time-$day'>Start Time:</label>";
                                    echo "<input type='time' id='start-time-$day' name='start-time[]' class='start-time' data-day='$day' step='1800'>";
                                    echo "<label for='end-time-$day'>End Time:</label>";
                                    echo "<input type='time' id='end-time-$day' class='end-time' readonly>";
                                    echo "</div>";
                                    echo "</div>";
                                }
                                ?>
                            </div>
                        </div>
                        <button type="submit" class="btn-submit">Save Availability</button>
                        <div id="setting-message-container">
                            <?php if ($action == "availability" && ($error_message || $success_message)): ?>
                                <div class="message <?php echo $error_message ? 'error' : 'success'; ?>">
                                    <?php echo $error_message ?: $success_message; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Off Day Section -->
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="off-day-container">
                    <p class="info">You can set off days by using this button</p>
                    <a href="set-off-days.php" class="btn-go">Set Off Days</a>
                </div>
            </div>
        </div>
    </div>
  </main>

    <!-- ======= Footer ======= -->
    <?php
    //include("footer.php");
    ?>
    <!-- End Footer -->

  <script>

    //Give an error message if no day is specified or no start hour for the selected days are specified (Other error and success messages are handled with PHP commands in body)
    document.getElementById('availability-form').addEventListener('submit', function(event) {
        let selectedDays = Array.from(document.querySelectorAll('input[name="day[]"]:checked')).map(el => el.value);
        let startTimes = Array.from(document.querySelectorAll('.start-time')).map(el => el.value);

        // Filter out empty start times that belong to unchecked days
        let validStartTimes = [];
        document.querySelectorAll('input[name="day[]"]:checked').forEach((dayCheckbox, index) => {
            let day = dayCheckbox.value;
            let startTimeInput = document.getElementById(`start-time-${day}`);
            if (startTimeInput && startTimeInput.value !== '') {
                validStartTimes.push(startTimeInput.value);
            }
        });

        const messageContainer = document.getElementById('setting-message-container');
        messageContainer.innerHTML = '';  // Clear existing messages

        if (selectedDays.length === 0 || validStartTimes.length !== selectedDays.length) {
            event.preventDefault();
            let errorMessage = messageContainer.querySelector('.message.error');
            if (!errorMessage) {
                errorMessage = document.createElement('div');
                errorMessage.className = 'message error';
                messageContainer.appendChild(errorMessage);
            }
            errorMessage.textContent = 'Please select at least one day and complete the start time for each selected day.';
            return;  // Exit the function to prevent any other messages
        }
    });


    //Auto set the end hour
    document.querySelectorAll('.start-time').forEach(input => {
        input.addEventListener('change', function() {
            let startTime = this.value;
            let day = this.getAttribute('data-day');
            let endTimeInput = document.getElementById(`end-time-${day}`);
            if (startTime) {
                let endTime = new Date('1970-01-01T' + startTime + ':00' + 'Z');
                endTime.setHours(endTime.getHours() + 1);
                endTimeInput.value = endTime.toISOString().substr(11, 5);
            } else {
                endTimeInput.value = '';
            }
        });
    });

    // Enforce minute selection to be '00' or '30' only
    document.querySelectorAll('.start-time').forEach(input => {
        input.addEventListener('input', function() {
            let value = this.value;
            if (value) {
                let minutes = value.split(':')[1];
                if (minutes !== '00' && minutes !== '30') {
                    if (minutes < '15' || minutes > '45') {
                        this.value = value.split(':')[0] + ':00';
                    } else {
                        this.value = value.split(':')[0] + ':30';
                    }
                }
            }
        });
    });
</script>


  <!-- Vendor JS Files -->
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>
</html>
