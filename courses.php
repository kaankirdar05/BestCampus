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

// Modified SQL query to select only lessons with availability
$sql = "
    SELECT 
        L.lesson_category,
        L.lesson_subcategory,
        L.lesson_name,
        L.description,
        L.image_path,
        L.href
    FROM Lessons L
    INNER JOIN Availability A ON L.lesson_id = A.lesson_id
    WHERE A.available_date >= CURDATE()
    GROUP BY L.lesson_id
    ORDER BY L.lesson_category, L.lesson_subcategory, L.lesson_name";

$result = mysqli_query($conn, $sql);

$lessons = [];
$categories = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $lessons[$row['lesson_category']][$row['lesson_subcategory']][] = $row;
        if (!isset($categories[$row['lesson_category']])) {
            $categories[$row['lesson_category']] = [];
        }
        if (!in_array($row['lesson_subcategory'], $categories[$row['lesson_category']])) {
            $categories[$row['lesson_category']][] = $row['lesson_subcategory'];
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Courses - Mentor Bootstrap Template</title>
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
    .icon-box .bi {
        font-size: 2.5em; /* Adjust the size as needed */
        color: #3b5f7f; /* Adjust the color as needed */
    }

    .icon-box i {
        margin-bottom: 20px; /* Ensure consistent spacing */
        display: block;
    }

    .course-linkers {
        display: flex;
        justify-content: center;
    }

    .course-linkers .row {
        justify-content: center;
    }
  </style>

  <!-- =======================================================
  * Template Name: Mentor
  * Updated: Sep 18 2023 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/mentor-free-education-bootstrap-theme/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

    <!-- ======= Header ======= -->
    <?php  include("headerCheck.php"); ?>
    <!-- End Header -->

    <main id="main" data-aos="fade-in">

        <!-- ======= Breadcrumbs ======= -->
        <div class="breadcrumbs">
            <div class="container">
                <h2>Courses</h2>
                <p></p>
            </div>
        </div><!-- End Breadcrumbs -->

        <!-- Filter Section -->
        <section id="filter" class="filter">
            <div class="container" data-aos="fade-up">
                <div class="row">
                    <div class="col-lg-6">
                        <label for="categorySelect">Select Category:</label>
                        <select id="categorySelect" class="form-select" onchange="updateSubcategories()">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $category => $subcategories): ?>
                                <option value="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label for="subcategorySelect">Select Subcategory:</label>
                        <select id="subcategorySelect" class="form-select" onchange="filterLessons()">
                            <option value="">All Subcategories</option>
                        </select>
                    </div>
                </div>
            </div>
        </section><!-- End Filter Section -->

        <!-- Courses Section -->
        <section id="courses" class="courses">
            <div class="container" data-aos="fade-up" id="lessons-container">
                <?php
                foreach ($lessons as $category => $subcategories) {
                    echo '<div class="category-section" data-category="' . htmlspecialchars($category) . '">';
                    echo '<div class="section-title" data-category-title="' . htmlspecialchars($category) . '"><p>' . htmlspecialchars($category) . '</p></div>';
                    
                    foreach ($subcategories as $subcategory => $lessonsList) {
                        echo '<div class="subcategory-section" data-category="' . htmlspecialchars($category) . '" data-subcategory="' . htmlspecialchars($subcategory) . '">';
                        echo '<div class="section-title mt-1" data-subcategory-title="' . htmlspecialchars($subcategory) . '"><h2>' . htmlspecialchars($subcategory) . '</h2></div>';
                        echo '<div class="row lesson-list" data-category="' . htmlspecialchars($category) . '" data-subcategory="' . htmlspecialchars($subcategory) . '" data-aos="zoom-in" data-aos-delay="100">';
                        
                        foreach ($lessonsList as $lesson) {
                            echo '<div class="col-lg-3 col-md-6 d-flex align-items-stretch my-4 mt-md-0" data-aos="fade-up" data-aos-delay="200">';
                            echo '<div class="course-item position-relative">';
                            echo '<img src="' . htmlspecialchars($lesson['image_path']) . '" class="img-fluid" alt="' . htmlspecialchars($lesson['lesson_name']) . '">';
                            echo '<div class="course-content">';
                            echo '<div class="d-flex justify-content-between align-items-center mb-3">';
                            echo '<h4>' . htmlspecialchars($lesson['lesson_subcategory']) . '</h4>';
                            echo '</div>';
                            echo '<h3><a href="' . htmlspecialchars($lesson['href']) . '" class="readmore stretched-link">' . htmlspecialchars($lesson['lesson_name']) . '</a></h3>';
                            echo '<p>' . htmlspecialchars($lesson['description']) . '</p>';
                            echo '</div></div></div>';
                        }

                        echo '</div></div>';
                    }

                    echo '</div>';
                }
                ?>
            </div>
        </section><!-- End Courses Section -->

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

    <!-- Custom JS for Filter -->
    <script>
        const categories = <?php echo json_encode($categories); ?>;
        
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

            filterLessons();
        }

        function filterLessons() {
            const categorySelect = document.getElementById('categorySelect');
            const subcategorySelect = document.getElementById('subcategorySelect');
            const selectedCategory = categorySelect.value;
            const selectedSubcategory = subcategorySelect.value;

            // Remove all lessons
            const lessonsContainer = document.getElementById('lessons-container');
            lessonsContainer.innerHTML = '';

            // Filter and display lessons
            for (const [category, subcategories] of Object.entries(categories)) {
                if (selectedCategory && category !== selectedCategory) continue;

                let categoryHasLessons = false;
                const categorySection = document.createElement('div');
                categorySection.classList.add('category-section');
                categorySection.setAttribute('data-category', category);
                
                const categoryTitle = document.createElement('div');
                categoryTitle.classList.add('section-title');
                categoryTitle.setAttribute('data-category-title', category);
                categoryTitle.innerHTML = `<p>${category}</p>`;
                categorySection.appendChild(categoryTitle);

                for (const subcategory of subcategories) {
                    if (selectedSubcategory && subcategory !== selectedSubcategory) continue;

                    const lessonsList = <?php echo json_encode($lessons); ?>[category][subcategory];
                    if (!lessonsList) continue;

                    categoryHasLessons = true;
                    const subcategorySection = document.createElement('div');
                    subcategorySection.classList.add('subcategory-section');
                    subcategorySection.setAttribute('data-category', category);
                    subcategorySection.setAttribute('data-subcategory', subcategory);
                    
                    const subcategoryTitle = document.createElement('div');
                    subcategoryTitle.classList.add('section-title', 'mt-1');
                    subcategoryTitle.setAttribute('data-subcategory-title', subcategory);
                    subcategoryTitle.innerHTML = `<h2>${subcategory}</h2>`;
                    subcategorySection.appendChild(subcategoryTitle);

                    const row = document.createElement('div');
                    row.classList.add('row', 'lesson-list');
                    row.setAttribute('data-category', category);
                    row.setAttribute('data-subcategory', subcategory);
                    row.setAttribute('data-aos', 'zoom-in');
                    row.setAttribute('data-aos-delay', '100');

                    lessonsList.forEach(lesson => {
                        const lessonCol = document.createElement('div');
                        lessonCol.classList.add('col-lg-3', 'col-md-6', 'd-flex', 'align-items-stretch', 'my-4', 'mt-md-0');
                        lessonCol.setAttribute('data-aos', 'fade-up');
                        lessonCol.setAttribute('data-aos-delay', '200');

                        lessonCol.innerHTML = `
                            <div class="course-item position-relative">
                                <img src="${lesson.image_path}" class="img-fluid" alt="${lesson.lesson_name}">
                                <div class="course-content">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4>${lesson.lesson_subcategory}</h4>
                                    </div>
                                    <h3><a href="${lesson.href}" class="readmore stretched-link">${lesson.lesson_name}</a></h3>
                                    <p>${lesson.description}</p>
                                </div>
                            </div>`;
                        
                        row.appendChild(lessonCol);
                    });

                    subcategorySection.appendChild(row);
                    categorySection.appendChild(subcategorySection);
                }

                if (categoryHasLessons) {
                    lessonsContainer.appendChild(categorySection);
                }
            }
        }

        // Initial filter setup
        document.addEventListener('DOMContentLoaded', () => {
            filterLessons();
        });
    </script>

</body>

</html>
