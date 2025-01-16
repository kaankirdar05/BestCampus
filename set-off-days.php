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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $teacher_id = $_SESSION['user_id'];
    $role = $_SESSION['role'];

    if ($role == 1) {
        if (isset($_POST['off-day-check'])) {
            $action = "off_day";
            $off_day = $_POST['off-day'];
            $start_time = $_POST['off-start-time'];

            $start_time .= ':00';
            $end_time = date('H:i:s', strtotime($start_time) + 3600); // Add 1 hour to start time

            // Check if the same off day already exists
            $sql_check_off = "SELECT * FROM Teacher_off_dates WHERE teacher_id = '$teacher_id' AND off_date = '$off_day' AND start_time = '$start_time' AND end_time = '$end_time'";
            $result_check_off = mysqli_query($conn, $sql_check_off);

            if (mysqli_num_rows($result_check_off) == 0) {
                // Insert off day into the database
                $sql_insert_off = "INSERT INTO Teacher_off_dates (teacher_id, off_date, start_time, end_time) VALUES ('$teacher_id', '$off_day', '$start_time', '$end_time')";
                if (!mysqli_query($conn, $sql_insert_off)) {
                    $error_message = "Error: " . mysqli_error($conn);
                } else {
                    $success_message = "Off day saved successfully!";
                }
            } else {
                $error_message = "Off day already exists for " . turkish_date_format($off_day) . " from " . substr($start_time, 0, 5) . " to " . substr($end_time, 0, 5);
            }

            // Clear POST data to prevent re-submission
            $_POST = [];
        }
    } else {
        $error_message = "Unauthorized access. Only teachers can set off days.";
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

// Retrieve teacher's off days
$teacher_id = $_SESSION['user_id'];
$sql_off_days = "SELECT off_date_id, off_date, start_time, end_time FROM Teacher_off_dates WHERE teacher_id = '$teacher_id'";
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

  <title>Set Off Days</title>
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

  <style>
    .off-day-container {
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

    .btn-submit, .btn-delete, .btn-back {
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

    .btn-submit:hover, .btn-delete:hover, .btn-back:hover {
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

    .btn-back {
        background-color: #5fcf80;
        margin-left: 5rem;
    }

    .btn-back:hover {
        background-color: #3ac162;
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

    .off-day-container h3 {
        text-align: center;
        margin-bottom: 20px;
    }

    .button-container {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
    }
  </style>
</head>

<body>

  <!-- ======= Header ======= -->
  <?php include("headerCheck.php");  ?>
  <!-- End Header -->

  <main class="content-wrapper">
    <div class="container">
        <!-- Off Days Table Section -->
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="off-day-container">
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

        <!-- Off Day Form Section -->
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="off-day-container">
                    <h3>Set Off Day</h3>
                    <form id="off-day-form" method="POST" action="">
                    <input type="hidden" name="off-day-check" value="1">
                        <div class="form-group">
                            <label for="off-day">Select Date:</label>
                            <input type="date" id="off-day" name="off-day">
                        </div>
                        <div class="form-group">
                            <label for="off-start-time">Select Start Time:</label>
                            <input type="time" id="off-start-time" name="off-start-time" step="1800">
                            <label for="off-end-time">End Time:</label>
                            <input type="time" id="off-end-time" name="off-end-time" readonly>
                        </div>
                        <button type="submit" class="btn-submit">Save Off Day</button>
                        <div id="setting-message-container">
                            <?php if ($action == "off_day" && ($error_message || $success_message)): ?>
                                <div class="message <?php echo $error_message ? 'error' : 'success'; ?>">
                                    <?php echo $error_message ?: $success_message; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </form>
                    <div class="button-container">
                        <a href="teacher-availability.php" class="btn-back"><i class="bi bi-arrow-left"></i> Back to Availability</a>
                    </div>
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
    //Auto set the end hour
    document.getElementById('off-start-time').addEventListener('change', function() {
        let startTime = this.value;
        let endTimeInput = document.getElementById('off-end-time');
        if (startTime) {
            let endTime = new Date('1970-01-01T' + startTime + ':00' + 'Z');
            endTime.setHours(endTime.getHours() + 1);
            endTimeInput.value = endTime.toISOString().substr(11, 5);
        } else {
            endTimeInput.value = '';
        }
    });

    // Enforce minute selection to be '00' or '30' only
    document.querySelectorAll('#off-start-time').forEach(input => {
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
