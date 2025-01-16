<?php
    
    if (session_status() == PHP_SESSION_NONE) {
    session_start();
    } // Start the session
  

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

// Get lesson ID from URL
$lesson_id = isset($_GET['lesson_id']) ? intval($_GET['lesson_id']) : 0;

// Fetch lesson details
$lesson_sql = "
    SELECT 
        lesson_category,
        lesson_subcategory,
        lesson_name,
        description,
        image_path
    FROM Lessons
    WHERE lesson_id = $lesson_id";

$lesson_result = mysqli_query($conn, $lesson_sql);
$lesson = mysqli_fetch_assoc($lesson_result);

// Fetch availability details
$availability_sql = "
    SELECT 
        Availability.teacher_id,
        Users.name AS teacher_name,
        Users.surname AS teacher_surname,
        Availability.available_date,
        Availability.start_time,
        Availability.end_time
    FROM Availability
    JOIN Users ON Availability.teacher_id = Users.user_id
    LEFT JOIN Bookings ON Availability.teacher_id = Bookings.teacher_id
        AND Availability.available_date = DATE(Bookings.lesson_time)
        AND (
            (Availability.start_time BETWEEN Bookings.start_time AND Bookings.end_time) OR
            (Availability.end_time BETWEEN Bookings.start_time AND Bookings.end_time) OR
            (Bookings.start_time BETWEEN Availability.start_time AND Availability.end_time) OR
            (Bookings.end_time BETWEEN Availability.start_time AND Availability.end_time)
        )
    WHERE Availability.lesson_id = $lesson_id
        AND Bookings.booking_id IS NULL";

$availability_result = mysqli_query($conn, $availability_sql);
$availabilities = [];
while ($row = mysqli_fetch_assoc($availability_result)) {
    $availabilities[] = $row;
}

// Sort availabilities by date and start_time
usort($availabilities, function($a, $b) {
    $dateComparison = strtotime($a['available_date']) - strtotime($b['available_date']);
    if ($dateComparison === 0) {
        return strtotime($a['start_time']) - strtotime($b['start_time']);
    }
    return $dateComparison;
});

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

    <title>Course Details - Mentor Bootstrap Template</title>
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
</head>

<style>
.btn-buy {
    margin-top: 1rem;
    background: #5fcf80;
    color: #fff;
    border-radius: 50px;
    padding: 8px 25px;
    white-space: nowrap;
    transition: 0.3s;
    font-size: 14px;
    display: inline-block;
    outline: none; /* Remove outline */
    border: none; /* Remove border */
}

.btn-buy:hover {
    background: #3ac162;
    color: #fff;
}
</style>

<body>

    <!-- ======= Header ======= -->
    <?php include("headerCheck.php"); ?>
    <!-- End Header -->

    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <div class="breadcrumbs" data-aos="fade-in">
            <div class="container">
                <h2>Course Details</h2>
                <p></p>
            </div>
        </div><!-- End Breadcrumbs -->

        <!-- ======= Course Details Section ======= -->
        <section id="course-details" class="course-details">
            <div class="container" data-aos="fade-up">

                <div class="row">
                    <div class="col-lg-6">
                        <img src="<?php echo htmlspecialchars($lesson['image_path']); ?>" class="img-fluid" alt="">
                        <h3><?php echo htmlspecialchars($lesson['lesson_category']) . ' / ' . htmlspecialchars($lesson['lesson_subcategory']) . ' / ' . htmlspecialchars($lesson['lesson_name']); ?></h3>
                        <p><?php echo nl2br(htmlspecialchars($lesson['description'])); ?></p>
                    </div>
                    <div class="col-lg-6">

                        <!-- Teacher Selection -->
                        <label for="teacher">Teacher</label>
                        <select id="teacher" class="form-select" onchange="updateOptions()">
                            <option value="">Select Teacher</option>
                            <?php
                            $teachers = [];
                            foreach ($availabilities as $availability) {
                                $teacher_id = $availability['teacher_id'];
                                if (!isset($teachers[$teacher_id])) {
                                    $teachers[$teacher_id] = $availability['teacher_name'] . ' ' . $availability['teacher_surname'];
                                    echo '<option value="' . htmlspecialchars($teacher_id) . '">' . htmlspecialchars($teachers[$teacher_id]) . '</option>';
                                }
                            }
                            ?>
                        </select>

                        <!-- Date Selection -->
                        <label class= "mt-2" for="date">Date</label>
                        <select id="date" class="form-select" onchange="updateOptions()">
                            <option value="">Select Date</option>
                            <?php
                            $dates = [];
                            foreach ($availabilities as $availability) {
                                $date = $availability['available_date'];
                                if (!in_array($date, $dates)) {
                                    $dates[] = $date;
                                }
                            }
                            usort($dates, function($a, $b) {
                                return strtotime($a) - strtotime($b);
                            });
                            foreach ($dates as $date) {
                                echo '<option value="' . htmlspecialchars($date) . '">' . htmlspecialchars(turkish_date_format($date)) . '</option>';
                            }
                            ?>
                        </select>

                        <!-- Hour Selection -->
                        <label class= "mt-2" for="hour">Hour</label>
                        <select id="hour" class="form-select" onchange="updateOptions()">
                            <option value="">Select Hour</option>
                            <?php
                            $hours = [];
                            foreach ($availabilities as $availability) {
                                $hour = $availability['start_time'] . ' - ' . $availability['end_time'];
                                if (!in_array($hour, $hours)) {
                                    $hours[] = $hour;
                                }
                            }
                            usort($hours, function($a, $b) {
                                return strtotime(explode(' - ', $a)[0]) - strtotime(explode(' - ', $b)[0]);
                            });
                            foreach ($hours as $hour) {
                                $formatted_hour = date("H:i", strtotime(explode(' - ', $hour)[0])) . ' - ' . date("H:i", strtotime(explode(' - ', $hour)[1]));
                                echo '<option value="' . htmlspecialchars($hour) . '">' . htmlspecialchars($formatted_hour) . '</option>';
                            }
                            ?>
                        </select>

                        <div class="btn-wrap">
                            <button class="btn-buy" onclick="validateAndProceed()">Dersi Al</button>
                            <div id="error-message" class="mt-2 text-danger"></div>
                        </div>
                    </div>
                </div>

            </div>
        </section><!-- End Course Details Section -->

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <?php include("footer.php"); ?>
    <!-- End Footer -->

    <div id="preloader"></div>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>

    <script>
        const availabilities = <?php echo json_encode($availabilities); ?>;

        function updateOptions() {
            const teacher = document.getElementById('teacher').value;
            const date = document.getElementById('date').value;
            const hour = document.getElementById('hour').value;

            const teacherOptions = new Set();
            const dateOptions = new Set();
            const hourOptions = new Set();

            availabilities.forEach(avail => {
                if ((!teacher || avail.teacher_id == teacher) &&
                    (!date || avail.available_date == date) &&
                    (!hour || (avail.start_time + ' - ' + avail.end_time) == hour)) {
                    teacherOptions.add(avail.teacher_id);
                    dateOptions.add(avail.available_date);
                    hourOptions.add(avail.start_time + ' - ' + avail.end_time);
                }
            });

            updateSelect('teacher', teacherOptions, teacher, availabilities, 'teacher_id', 'teacher_name', 'teacher_surname');
            updateSelect('date', dateOptions, date, availabilities, 'available_date', 'turkish_date_format');
            updateSelect('hour', hourOptions, hour, availabilities, 'start_time', 'formatted_hour', 'end_time');
        }

        function updateSelect(id, options, currentValue, data = null, valueKey = '', textKey1 = '', textKey2 = '') {
            const select = document.getElementById(id);
            const current = select.value;
            select.innerHTML = '<option value="">Select ' + id.charAt(0).toUpperCase() + id.slice(1) + '</option>';
            options.forEach(option => {
                let optionText;
                if (data) {
                    const foundItem = data.find(d => d[valueKey] == option);
                    if (textKey1 === 'turkish_date_format') {
                        optionText = foundItem ? turkishDateFormat(foundItem[valueKey]) : option;
                    } else if (textKey1 === 'formatted_hour') {
                        optionText = foundItem ? formatHour(foundItem[valueKey], foundItem[textKey2]) : option;
                    } else {
                        optionText = foundItem ? foundItem[textKey1] + ' ' + foundItem[textKey2] : option;
                    }
                } else {
                    optionText = option;
                }
                const optionValue = option;
                const isSelected = optionValue == current ? ' selected' : '';
                select.innerHTML += `<option value="${optionValue}"${isSelected} data-formatted-hour="${optionText}">${optionText}</option>`;
            });
            select.value = currentValue;
        }

        function turkishDateFormat(date) {
            const englishMonths = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            const turkishMonths = ["Ocak", "Şubat", "Mart", "Nisan", "Mayıs", "Haziran", "Temmuz", "Ağustos", "Eylül", "Ekim", "Kasım", "Aralık"];
            const d = new Date(date);
            const day = d.getDate();
            const month = turkishMonths[d.getMonth()];
            const year = d.getFullYear();
            return `${day} ${month} ${year}`;
        }

        function formatHour(start, end) {
            const startHour = new Date('1970-01-01T' + start + 'Z').toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' });
            const endHour = new Date('1970-01-01T' + end + 'Z').toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' });
            return `${startHour} - ${endHour}`;
        }

        function validateAndProceed() {
            const teacher = document.getElementById('teacher').value;
            const date = document.getElementById('date').value;
            const hour = document.getElementById('hour').value;
            const errorMessage = document.getElementById('error-message');

            if (!teacher || !date || !hour) {
                errorMessage.textContent = 'Please select a teacher, date, and hour.';
            } else {
                errorMessage.textContent = '';
                window.location.href = 'try.php';
            }
        }
    </script>

</body>

</html>
