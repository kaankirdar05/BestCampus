<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("commonSigned.php");
$role = $_SESSION["role"];
?>

<!-- ======= Header ======= -->
<header id="header" class="fixed-top">
    <div class="container d-flex align-items-center">

        <h1 class="logo me-auto"><a href="index.html">BestCampus</a></h1>
        <!-- Uncomment below if you prefer to use an image logo -->
        <!-- <a href="index.html" class="logo me-auto"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->

        <nav id="navbar" class="navbar order-last order-lg-0">
            <ul>
                <li><a href="index.php">Ana Sayfa</a></li>
                <li><a href="about.php">Hakkımızda</a></li>
                <li class="dropdown"><a href="courses.php"><span>Derslerimiz</span> <i class="bi bi-chevron-down"></i></a>
                    <ul>
                        <li class="dropdown"><a href="courses.php#YKS"><span>YKS'ye Hazırlık</span> <i class="bi bi-chevron-right"></i></a>
                            <ul>
                                <li><a href="courses.php#TYT">TYT</a></li>
                                <li><a href="courses.php#AYT">AYT</a></li>
                            </ul>
                        </li>
                        <li class="dropdown"><a href="courses.php#lise"><span>Lise Dersleri</span> <i class="bi bi-chevron-right"></i></a>
                            <ul>
                                <li><a href="courses.php#math">Matematik</a></li>
                                <li><a href="courses.php#phys">Fizik</a></li>
                                <li><a href="courses.php#chem">Kimya</a></li>
                                <li><a href="courses.php#bio">Biyoloji</a></li>
                            </ul>
                        </li>
                        <li class="dropdown"><a href="courses.php#university"><span>Üniversite Dersleri</span> <i class="bi bi-chevron-right"></i></a>
                            <ul>
                                <li><a href="courses.php#university">Calculus</a></li>
                                <li><a href="courses.php#university">Thermodynamics</a></li>
                                <li><a href="courses.php#university">Circuit Design</a></li>
                                <li><a href="courses.php#university">Physics 101</a></li>
                                <li><a href="courses.php#university">Physics 102</a></li>
                            </ul>
                        </li>
                        <li class="dropdown"><a href="courses.php#coding"><span>Kodlama Dersleri</span> <i class="bi bi-chevron-right"></i></a>
                            <ul>
                                <li><a href="courses.php#coding">Python</a></li>
                                <li><a href="courses.php#coding">Java</a></li>
                                <li><a href="courses.php#coding">C/C++</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li><a href="trainers.php">Ekibimiz</a></li>
                <li><a href="contact2.php">İletişim</a></li>

                <i class="bi bi-list mobile-nav-toggle"></i>

                <li class="nav-item dropdown pe-0">
                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <img src="<?php echo $_SESSION['image_path']; ?>" alt="Profile" class="rounded-circle" width="30" height="30">
                        <span class="d-none d-md-block dropdown-toggle ps-2"></span>
                    </a><!-- End Profile Image Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6><?php echo $_SESSION['name'] . " " . $_SESSION['surname'] ; ?></h6>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="profile.php">
                                <i class="bi bi-person"></i>
                                <span>My Profile</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="mylessons.php">
                                <i class='bi bi-journal-check'></i>
                                <span>My Lessons</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <?php
                            if($role == '1'){
                            echo 
                            "<li>
                            <a class='dropdown-item d-flex align-items-center' href='teacher-availability.php'>
                                <i class='bi bi-gear'></i>
                                <span>Lesson Settings</span>
                            </a>
                            </li>
                            <li>
                            <hr class='dropdown-divider'>
                            </li>";
                            }
                        ?>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <i class="bi bi-question-circle"></i>
                                <span>Need Help?</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="logout.php">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Sign Out</span>
                            </a>
                        </li>

                    </ul><!-- End Profile Dropdown Items -->
                </li><!-- End Profile Nav -->
            </ul>
            <i class="bi bi-list mobile-nav-toggle"></i>

            </ul>
        </nav><!-- .navbar -->

    </div>
</header><!-- End Header -->
