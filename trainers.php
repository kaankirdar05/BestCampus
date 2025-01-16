<?php
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

// Fetch trainers from the database
$query = "
    SELECT 
        U.user_id, U.name, U.surname, U.university, U.faculty, U.small_description, U.image_path,
        L.lesson_name, L.lesson_category, L.lesson_subcategory, TA.day_of_week
    FROM Users U
    LEFT JOIN Teacher_lessons TL ON U.user_id = TL.teacher_id
    LEFT JOIN Lessons L ON TL.lesson_id = L.lesson_id
    LEFT JOIN Teacher_availability TA ON U.user_id = TA.teacher_id
    WHERE U.role = 1
";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$trainers = [];
$categories = [];
$subcategories = [];
$days = [];

while ($row = mysqli_fetch_assoc($result)) {
    $trainers[$row['user_id']]['info'] = $row;
    if ($row['lesson_category']) {
        $trainers[$row['user_id']]['lessons'][$row['lesson_category']][] = $row['lesson_subcategory'];
    }
    if ($row['day_of_week']) {
        $trainers[$row['user_id']]['availability'][] = $row['day_of_week'];
    }

    if ($row['lesson_category'] && !isset($categories[$row['lesson_category']])) {
        $categories[$row['lesson_category']] = [];
    }
    if ($row['lesson_category'] && !in_array($row['lesson_subcategory'], $categories[$row['lesson_category']])) {
        $categories[$row['lesson_category']][] = $row['lesson_subcategory'];
    }
    if ($row['lesson_name'] && !isset($subcategories[$row['lesson_name']])) {
        $subcategories[$row['lesson_name']] = [];
    }
    if ($row['lesson_subcategory'] && !in_array($row['lesson_subcategory'], $subcategories[$row['lesson_name']])) {
        $subcategories[$row['lesson_name']][] = $row['lesson_subcategory'];
    }
    if ($row['day_of_week'] && !in_array($row['day_of_week'], $days)) {
        $days[] = $row['day_of_week'];
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Trainers - Mentor Bootstrap Template</title>
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
        .member img {
            height: 300px;  /* Set a fixed height */
            width: 100%;    /* Make width stretch to fill the container */
            object-fit: cover; /* Ensures the image covers the area without distorting aspect ratio */
            border-radius: 20%;
        }

        .col-lg-3.col-md-2 {
            display: flex;
            align-items: stretch;  /* This will make sure all .member divs in a row take equal height */
            justify-content: center;
        }

        .member {
            display: flex;
            flex-direction: column;  /* Ensures the content inside each member is organized vertically */
            align-items: center;  /* Align items in the center horizontally */
            text-align: center;  /* Ensures text within the member is centered */
            width: 100%;  /* Optional: makes sure member takes the full width of the parent column */
            border-radius: 20%;
        }

        /* Custom dropdown styles */
        .form-select option:hover {
            background-color: green;
            color: white;
        }
    </style>

</head>

<body>

    <!-- ======= Header ======= -->
    <?php
    include("headerCheck.php");
    ?>
    <!-- End Header -->

    <main id="main" data-aos="fade-in">

        <!-- ======= Breadcrumbs ======= -->
        <div class="breadcrumbs">
            <div class="container">
                <h2>Trainers</h2>
            </div>
        </div><!-- End Breadcrumbs -->

        <!-- Filter Section -->
        <section id="filter" class="filter">
            <div class="container" data-aos="fade-up">
                <div class="row">
                    <div class="col-lg-3">
                        <label for="lessonSelect">Select Lesson:</label>
                        <select id="lessonSelect" class="form-select" onchange="updateCategories(); filterTrainers();">
                            <option value="">All Lessons</option>
                            <?php foreach ($subcategories as $lesson => $subs): ?>
                                <option value="<?php echo htmlspecialchars($lesson); ?>"><?php echo htmlspecialchars($lesson); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label for="categorySelect">Select Category:</label>
                        <select id="categorySelect" class="form-select" onchange="updateSubcategories(); filterTrainers();">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $category => $subs): ?>
                                <option value="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label for="subcategorySelect">Select Subcategory:</label>
                        <select id="subcategorySelect" class="form-select" onchange="filterTrainers()">
                            <option value="">All Subcategories</option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label for="daySelect">Select Day:</label>
                        <select id="daySelect" class="form-select" onchange="filterTrainers()">
                            <option value="">All Days</option>
                            <?php foreach ($days as $day): ?>
                                <option value="<?php echo htmlspecialchars($day); ?>"><?php echo htmlspecialchars($day); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </section><!-- End Filter Section -->

        <!-- ======= Trainers Section ======= -->
        <section id="trainers" class="trainers">
            <div class="container" data-aos="fade-up">
                <div class="row" data-aos="zoom-in" data-aos-delay="100" id="trainers-container">
                    <?php foreach ($trainers as $trainer): ?>
                        <div class="col-lg-3 col-md-2 d-flex align-items-stretch trainer-item" data-lesson="<?php echo htmlspecialchars($trainer['info']['lesson_name']); ?>" data-category="<?php echo htmlspecialchars(implode(',', array_keys($trainer['lessons'] ?? []))); ?>" data-subcategory="<?php echo htmlspecialchars(implode(',', array_merge(...array_values($trainer['lessons'] ?? [[]])))); ?>" data-day="<?php echo htmlspecialchars(implode(',', $trainer['availability'] ?? [])); ?>">
                            <div class="member">
                                <img src="<?php echo htmlspecialchars($trainer['info']['image_path']); ?>" class="img-fluid" alt="">
                                <div class="member-content">
                                    <h4><?php echo htmlspecialchars($trainer['info']['name'] . ' ' . $trainer['info']['surname']); ?></h4>
                                    <span><?php echo htmlspecialchars($trainer['info']['university'] . ', ' . $trainer['info']['faculty']); ?></span>
                                    <p><?php echo htmlspecialchars($trainer['info']['small_description']); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section><!-- End Trainers Section -->

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <?php
    include("footer.php");
    ?>
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

    <!-- Custom JS for Filter -->
    <script>
        const categories = <?php echo json_encode($categories); ?>;
        const subcategories = <?php echo json_encode($subcategories); ?>;

        function updateCategories() {
            const lessonSelect = document.getElementById('lessonSelect');
            const categorySelect = document.getElementById('categorySelect');
            const selectedLesson = lessonSelect.value;

            // Clear previous options
            categorySelect.innerHTML = '<option value="">All Categories</option>';

            // Add new options
            if (selectedLesson && subcategories[selectedLesson]) {
                subcategories[selectedLesson].forEach(category => {
                    const option = document.createElement('option');
                    option.value = category;
                    option.textContent = category;
                    categorySelect.appendChild(option);
                });
            }

            updateSubcategories();
            filterTrainers();
        }

        function updateSubcategories() {
            const categorySelect = document.getElementById('categorySelect');
            const subcategorySelect = document.getElementById('subcategorySelect');
            const selectedCategory = categorySelect.value;

            // Clear previous options
            subcategorySelect.innerHTML = '<option value="">All Subcategories</option>';

            // Add new options
            if (selectedCategory && categories[selectedCategory]) {
                categories[selectedCategory].forEach(subcategory => {
                    const option = document.createElement('option');
                    option.value = subcategory;
                    option.textContent = subcategory;
                    subcategorySelect.appendChild(option);
                });
            }

            filterTrainers();
        }

        function filterTrainers() {
            const lessonSelect = document.getElementById('lessonSelect');
            const categorySelect = document.getElementById('categorySelect');
            const subcategorySelect = document.getElementById('subcategorySelect');
            const daySelect = document.getElementById('daySelect');
            const selectedLesson = lessonSelect.value;
            const selectedCategory = categorySelect.value;
            const selectedSubcategory = subcategorySelect.value;
            const selectedDay = daySelect.value;

            document.querySelectorAll('.trainer-item').forEach(item => {
                const itemLessons = item.getAttribute('data-lesson').split(',');
                const itemCategories = item.getAttribute('data-category').split(',');
                const itemSubcategories = item.getAttribute('data-subcategory').split(',');
                const itemDays = item.getAttribute('data-day').split(',');

                let showItem = true;

                if (selectedLesson && !itemLessons.includes(selectedLesson)) {
                    showItem = false;
                }

                if (selectedCategory && !itemCategories.includes(selectedCategory)) {
                    showItem = false;
                }

                if (selectedSubcategory && !itemSubcategories.includes(selectedSubcategory)) {
                    showItem = false;
                }

                if (selectedDay && !itemDays.includes(selectedDay)) {
                    showItem = false;
                }

                item.style.display = showItem ? 'block' : 'none';
            });
        }

        // Initial filter setup
        document.addEventListener('DOMContentLoaded', () => {
            updateCategories();
            updateSubcategories();
            filterTrainers();
        });
    </script>

</body>

</html>
