<?php
    
if (session_status() == PHP_SESSION_NONE) {
session_start();
} // Start the session

include("logedinCheck.php");
include("commonSigned.php");

// Database connection parameters
$server = "localhost";
$username = "root";
$password = "";
$database = "bestcampus";

$conn = mysqli_connect($server, $username, $password, $database);
if (!$conn) {
    die("Error" . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");

$showAlert = false;
$showError = false;

$defaultProfileImagePath = 'uploads/default-profile.png'; // Path to the default profile image
$oldemail = $_SESSION['email'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['form_type'])) {
        $formType = $_POST['form_type'];
    } else {
        $formType = '';
    }

    switch ($formType) {
        case 'profile':
            $name = $_POST["name"];
            $surname = $_POST["surname"];
            $profileImagePath = '';

            // Check if the remove image option was selected
            if (isset($_POST['remove_image']) && $_POST['remove_image'] == '1') {
                $sql = "UPDATE `Users` SET `image_path` = '$defaultProfileImagePath' WHERE `Users`.`email` = '$oldemail'";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    $_SESSION["image_path"] = $defaultProfileImagePath;
                }
            } else {
                // Handle image upload
                if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
                    $photo = $_FILES['photo'];
                    $photoName = $photo['name'];
                    $photoTmpName = $photo['tmp_name'];
                    $photoSize = $photo['size'];
                    $photoError = $photo['error'];
                    $photoType = $photo['type'];

                    $photoExt = explode('.', $photoName);
                    $photoActualExt = strtolower(end($photoExt));

                    $allowed = array('jpg', 'jpeg', 'png', 'gif');

                    if (in_array($photoActualExt, $allowed)) {
                        if ($photoSize < 1000000) { // limit file size to 1MB
                            $photoNewName = uniqid('', true) . "." . $photoActualExt;
                            $photoDestination = 'uploads/' . $photoNewName;

                            if (!is_dir('uploads')) {
                                mkdir('uploads');
                            }

                            move_uploaded_file($photoTmpName, $photoDestination);
                            $profileImagePath = $photoDestination;

                            $sql = "UPDATE `Users` SET `image_path` = '$profileImagePath' WHERE `Users`.`email` = '$oldemail'";
                            $result = mysqli_query($conn, $sql);
                            if ($result) {
                                $_SESSION["image_path"] = $profileImagePath;
                            }
                        } else {
                            $showError = "Your file is too big!";
                        }
                    } else {
                        $showError = "You cannot upload files of this type!";
                    }
                }
            }

            // Update user profile
            if ($name != "") {
                $sql = "UPDATE `Users` SET `name` = '$name' WHERE `Users`.`email` = '$oldemail'";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    $_SESSION["name"] = $name;
                }
            }
            if ($surname != "") {
                $sql = "UPDATE `Users` SET `surname` = '$surname' WHERE `Users`.`email` = '$oldemail'";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    $_SESSION["surname"] = $surname;
                }
            }

            if ($result) {
                $showAlert = true;
            } else {
                $showError = "An error occurred while updating your profile.";
            }
            break;
        case 'settings':
            // Handle settings form inputs
            break;
        case 'password':
            $password = $_POST["password"];
            $newpassword = $_POST["newpassword"];

            $sql = "SELECT * FROM `Users` WHERE `Users`.`email` = '$oldemail'";
            $result = mysqli_query($conn, $sql);
            $num = mysqli_num_rows($result);

            if ($num == 1) {
                while ($row = mysqli_fetch_assoc($result)) {
                    if (password_verify($password, $row['password'])) {
                        $hash = password_hash($newpassword, PASSWORD_DEFAULT);
                        $sql = "UPDATE `Users` SET `password` = '$hash' WHERE `Users`.`email` = '$oldemail'";
                        $result2 = mysqli_query($conn, $sql);
                    } else {
                        $showError = "Incorrect current password.";
                    }
                }
            }
            break;
        default:
            // Handle other cases or show an error
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Dashboard - NiceAdmin Bootstrap Template</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/assetsExample/assetsAdmin/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/assetsExample/assetsAdmin/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/assetsExample/assetsAdmin/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/assetsExample/assetsAdmin/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/assetsExample/assetsAdmin/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/assetsExample/assetsAdmin/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/assetsExample/assetsAdmin/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="assets/assetsExample/assetsAdmin/css/style.css" rel="stylesheet">

    <!-- =======================================================
    * Template Name: NiceAdmin
    * Updated: Jan 09 2024 with Bootstrap v5.3.2
    * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
    * Author: BootstrapMade.com
    * License: https://bootstrapmade.com/license/
    ======================================================== -->

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

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
        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 30%; /* Makes it a circle */
        }
    </style>
</head>

<body>

    <!-- ======= Header ======= -->
    <?php
    include("headerCheck.php");
    ?>
    <!-- End Header -->

    <main id="main" class="main">
        <section class="section profile">

            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-body pt-3">
                                <!-- Bordered Tabs -->
                                <ul class="nav nav-tabs nav-tabs-bordered">

                                    <li class="nav-item">
                                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                                    </li>

                                    <li class="nav-item">
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-settings">Settings</button>
                                    </li>

                                    <li class="nav-item">
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                                    </li>

                                </ul>
                                <div class="tab-content pt-2">

                                    <div class="tab-pane fade show active profile-edit" id="profile-edit">

                                        <!-- Profile Edit Form -->
                                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="profileForm" enctype="multipart/form-data" onsubmit="return confirm('Are you sure you want to save changes?');">
                                            <input type="hidden" name="form_type" value="profile">
                                            <input type="hidden" name="remove_image" id="remove_image" value="0">

                                            <div class="row mb-3">
                                                <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                                                <div class="col-md-8 col-lg-9">
                                                    <?php
                                                    $profileImage = $_SESSION['image_path'] ?? 'uploads/default-profile.png';
                                                    ?>
                                                    <img src="<?php echo $profileImage; ?>" alt="Profile" class="profile-img">
                                                    <div class="pt-2">
                                                        <a href="#" class="btn btn-primary btn-sm" title="Upload new profile image" onclick="document.getElementById('photo').click();"><i class="bi bi-upload"></i></a>
                                                        <input type="file" name="photo" id="photo" style="display: none;">
                                                        <a href="#" class="btn btn-danger btn-sm" title="Remove my profile image" onclick="removeProfileImage();"><i class="bi bi-trash"></i></a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                                <div class="col-md-8 col-lg-9">
                                                    <input name="email" type="email" class="form-control" id="email" maxlength="50" placeholder="<?php echo $_SESSION["email"]; ?>" disabled>
                                                    <div id="profileError" style="color: red; display: none;"></div>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="name" class="col-md-4 col-lg-3 col-form-label">Name</label>
                                                <div class="col-md-8 col-lg-9">
                                                    <input name="name" type="text" class="form-control" id="name" maxlength="15" placeholder="<?php echo $_SESSION["name"]; ?>">
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="about" class="col-md-4 col-lg-3 col-form-label">Surname</label>
                                                <div class="col-md-8 col-lg-9">
                                                    <input name="surname" type="text" class="form-control" id="surname" maxlength="15" placeholder="<?php echo $_SESSION["surname"]; ?>">
                                                </div>
                                            </div>

                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form><!-- End Profile Edit Form -->
                                    </div>

                                    <div class="tab-pane fade pt-3" id="profile-settings">

                                        <!-- Settings Form -->
                                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="settingsForm">
                                            <input type="hidden" name="form_type" value="settings">
                                            <div class="row mb-3">
                                                <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Email Notifications</label>
                                                <div class="col-md-8 col-lg-9">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="changesMade" checked>
                                                        <label class="form-check-label" for="changesMade">
                                                            Changes made to your account
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="newProducts" checked>
                                                        <label class="form-check-label" for="newProducts">
                                                            Information on new products and services
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="proOffers">
                                                        <label class="form-check-label" for="proOffers">
                                                            Marketing and promo offers
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="securityNotify" checked disabled>
                                                        <label class="form-check-label" for="securityNotify">
                                                            Security alerts
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form><!-- End settings Form -->

                                    </div>

                                    <div class="tab-pane fade pt-3" id="profile-change-password">
                                        <!-- Change Password Form -->
                                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="passwordForm">
                                            <input type="hidden" name="form_type" value="password">
                                            <div class="row mb-3">
                                                <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                                                <div class="col-md-8 col-lg-9">
                                                    <input name="password" type="password" class="form-control" id="currentPassword" maxlength="15" required>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                                                <div class="col-md-8 col-lg-9">
                                                    <input name="newpassword" type="password" class="form-control" id="newPassword" maxlength="15" required>
                                                    <div id="passwordError" style="color: red; display: none;"></div>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                                                <div class="col-md-8 col-lg-9">
                                                    <input name="cpassword" type="password" class="form-control" id="cPassword" maxlength="15" required>
                                                    <div id="cpasswordError" style="color: red; display: none;"></div>
                                                </div>
                                            </div>

                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary">Change Password</button>
                                            </div>
                                        </form><!-- End Change Password Form -->

                                    </div>

                                </div><!-- End Bordered Tabs -->

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
    <script src="assets/assetsExample/assetsAdmin/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="assets/assetsExample/assetsAdmin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/assetsExample/assetsAdmin/vendor/chart.js/chart.umd.js"></script>
    <script src="assets/assetsExample/assetsAdmin/vendor/echarts/echarts.min.js"></script>
    <script src="assets/assetsExample/assetsAdmin/vendor/quill/quill.min.js"></script>
    <script src="assets/assetsExample/assetsAdmin/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/assetsExample/assetsAdmin/vendor/tinymce/tinymce.min.js"></script>
    <script src="assets/assetsExample/assetsAdmin/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/assetsExample/assetsAdmin/js/main.js"></script>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>

    <script src="assets/assetsExample/assetsSign/profile.js"></script>

    <script>
        function removeProfileImage() {
            document.getElementById('remove_image').value = '1';
            document.getElementById('photo').value = ''; // Clear the file input
        }
    </script>

</body>

</html>
