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

// Fetch trainers and related data
$query = "
    SELECT 
        U.user_id, U.name, U.surname, U.university, U.faculty, U.small_description, U.image_path,
        L.lesson_name, L.lesson_category, L.lesson_subcategory, TA.day_of_week
    FROM Users U
    LEFT JOIN Teacher_Lessons TL ON U.user_id = TL.teacher_id
    LEFT JOIN Lessons L ON TL.lesson_id = L.lesson_id
    LEFT JOIN Teacher_Availability TA ON U.user_id = TA.teacher_id
    WHERE U.role = 1
";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$trainers = [];
$categories = [];
$days = [];

while ($row = mysqli_fetch_assoc($result)) {
    $trainerId = $row['user_id'];
    if (!isset($trainers[$trainerId])) {
        $trainers[$trainerId] = [
            'info' => [
                'name' => $row['name'],
                'surname' => $row['surname'],
                'university' => $row['university'],
                'faculty' => $row['faculty'],
                'small_description' => $row['small_description'],
                'image_path' => $row['image_path'],
            ],
            'lessons' => [],
            'availability' => [],
        ];
    }

    if ($row['lesson_category']) {
        $trainers[$trainerId]['lessons'][] = [
            'category' => $row['lesson_category'],
            'subcategory' => $row['lesson_subcategory'],
            'lesson_name' => $row['lesson_name'],
        ];
        if (!isset($categories[$row['lesson_category']])) {
            $categories[$row['lesson_category']] = [];
        }
        if (!in_array($row['lesson_subcategory'], $categories[$row['lesson_category']])) {
            $categories[$row['lesson_category']][] = $row['lesson_subcategory'];
        }
    }

    if ($row['day_of_week'] && !in_array($row['day_of_week'], $trainers[$trainerId]['availability'])) {
        $trainers[$trainerId]['availability'][] = $row['day_of_week'];
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

        <div class="breadcrumbs">
            <div class="container">
                <h2>Trainers</h2>
            </div>
        </div>

        <!-- Filter Section -->
        <section id="filter" class="filter">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3">
                        <label for="categorySelect">Select Category:</label>
                        <select id="categorySelect" class="form-select" onchange="updateSubcategories(); filterTrainers();">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $category => $subcategories): ?>
                                <option value="<?= htmlspecialchars($category); ?>"><?= htmlspecialchars($category); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label for="subcategorySelect">Select Subcategory:</label>
                        <select id="subcategorySelect" class="form-select" onchange="filterTrainers();">
                            <option value="">All Subcategories</option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label for="daySelect">Select Day:</label>
                        <select id="daySelect" class="form-select" onchange="filterTrainers();">
                            <option value="">All Days</option>
                            <?php foreach ($days as $day): ?>
                                <option value="<?= htmlspecialchars($day); ?>"><?= htmlspecialchars($day); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </section>

        <!-- Trainers Section -->
        <section id="trainers" class="trainers">
            <div class="container" id="trainers-container">
                <!-- Trainers will be dynamically loaded here -->
            </div>
        </section>
    </main>

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

    
    <script>
        const trainers = <?= json_encode(array_values($trainers)); ?>;
        const categories = <?= json_encode($categories); ?>;

        function updateSubcategories() {
            const categorySelect = document.getElementById('categorySelect');
            const subcategorySelect = document.getElementById('subcategorySelect');
            const selectedCategory = categorySelect.value;

            subcategorySelect.innerHTML = '<option value="">All Subcategories</option>';
            if (categories[selectedCategory]) {
                categories[selectedCategory].forEach(subcategory => {
                    const option = document.createElement('option');
                    option.value = subcategory;
                    option.textContent = subcategory;
                    subcategorySelect.appendChild(option);
                });
            }
        }

        function filterTrainers() {
            const category = document.getElementById('categorySelect').value;
            const subcategory = document.getElementById('subcategorySelect').value;
            const day = document.getElementById('daySelect').value;

            const container = document.getElementById('trainers-container');
            container.innerHTML = '';

            trainers.forEach(trainer => {
                const hasCategory = trainer.lessons.some(lesson => lesson.category === category || !category);
                const hasSubcategory = trainer.lessons.some(lesson => lesson.subcategory === subcategory || !subcategory);
                const hasDay = trainer.availability.includes(day) || !day;

                if (hasCategory && hasSubcategory && hasDay) {
                    const trainerCard = `
                        <div class="col-lg-3 col-md-2">
                            <div class="member">
                                <img src="${trainer.info.image_path}" alt="${trainer.info.name}">
                                <h4>${trainer.info.name} ${trainer.info.surname}</h4>
                                <p>${trainer.info.university}, ${trainer.info.faculty}</p>
                                <p>${trainer.info.small_description}</p>
                            </div>
                        </div>`;
                    container.innerHTML += trainerCard;
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateSubcategories();
            filterTrainers();
        });
    </script>
</body>
</html>
