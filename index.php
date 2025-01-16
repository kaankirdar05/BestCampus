<?php
    
    if (session_status() == PHP_SESSION_NONE) {
    session_start();
    } // Start the session
  
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

  <!-- =======================================================
  * Template Name: Mentor
  * Updated: Sep 18 2023 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/mentor-free-education-bootstrap-theme/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->


  <style>
  .testimonials .testimonial-item .testimonial-img {
  width: 100px; /* Adjust size as needed */
  height: 100px; /* Adjust size as needed */
  border-radius: 50%;
  object-fit: cover;
  display: block;
  margin-left: auto;
  margin-right: auto;
}
</style>

</head>

<body>
  
  <!-- ======= Header ======= -->
  <?php include("headerCheck.php");  ?>
  <!-- End Header -->


  <!-- ======= Hero Section ======= -->
  <section id="hero" class="d-flex justify-content-center align-items-center">
    <div class="container position-relative" data-aos="zoom-in" data-aos-delay="100">
      <h1>Learning Today,<br>Leading Tomorrow</h1>
      <h2>Türkiye'nin en iyi üniversitelerindeki öğrenci ve mezunlardan ders al</h2>
      <a href="courses.php" class="btn-get-started">Hemen Başla</a>
    </div>
  </section><!-- End Hero -->

  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container" data-aos="fade-up">

        <div class="row">
          <div class="col-lg-6 order-1 order-lg-2" data-aos="fade-left" data-aos-delay="100">
            <img src="assets/img/about.jpg" class="img-fluid" alt="">
          </div>
          <div class="col-lg-6 pt-4 pt-lg-0 order-2 order-lg-1 content">
            <h3>Çağın Gerekliliklerine Ayak Uydur</h3>
            <p class="fst-italic">
            BestCampus, Türkiye'nin en prestijli üniversitelerinden öğrenci ve mezunlardan oluşan eğitim kadrosuyla, öğrencilere online eğitim ve koçluk hizmeti sunmaktadır. Amacımız, her öğrencinin potansiyelini en üst düzeye çıkarmak ve akademik başarıya ulaşmalarına yardımcı olmaktır.
            </p>
            <ul>
              <li><i class="bi bi-check-circle"></i> Kapsamlı eğitim programları, farklı alanlarda onlarca ders</li>
              <li><i class="bi bi-check-circle"></i> Her öğrenciye özel, birebir koçluk ve rehberlik hizmetleri</li>
              <li><i class="bi bi-check-circle"></i> Öğrencilerin hedeflerine ulaşmaları için sürekli destek ve takip</li>
            </ul>
            <p>
              Sen de BestCampus ile öğrenmeye hemen başla!
            </p>

          </div>
        </div>

      </div>
    </section><!-- End About Section -->

    <!-- ======= Counts Section ======= -->
    <section id="counts" class="counts section-bg">
      <div class="container">

        <div class="row counters">

          <div class="col-lg-3 col-6 text-center">
            <span data-purecounter-start="0" data-purecounter-end="1232" data-purecounter-duration="1" class="purecounter"></span>
            <p>Students</p>
          </div>

          <div class="col-lg-3 col-6 text-center">
            <span data-purecounter-start="0" data-purecounter-end="64" data-purecounter-duration="1" class="purecounter"></span>
            <p>Courses</p>
          </div>

          <div class="col-lg-3 col-6 text-center">
            <span data-purecounter-start="0" data-purecounter-end="42" data-purecounter-duration="1" class="purecounter"></span>
            <p>Events</p>
          </div>

          <div class="col-lg-3 col-6 text-center">
            <span data-purecounter-start="0" data-purecounter-end="15" data-purecounter-duration="1" class="purecounter"></span>
            <p>Trainers</p>
          </div>

        </div>

      </div>
    </section><!-- End Counts Section -->

    <!-- ======= Why Us Section ======= -->
    <section id="why-us" class="why-us">
      <div class="container" data-aos="fade-up">

        <div class="row">
          <div class="col-lg-4 d-flex align-items-stretch">
            <div class="content">
              <h3>Neden BestCampus?</h3>
              <p>
              Eğitim hayatınızda fark yaratmak için doğru yerdesiniz. BestCampus, Türkiye'nin en iyi üniversitelerinden mezun ve öğrencilerden oluşan deneyimli kadrosu ile öğrencilere özel eğitim fırsatları sunuyor. Her ders, alanında uzman eğitmenler tarafından hazırlanmış ve birebir ihtiyaçlarınıza göre şekillendirilmiştir. Amacımız, öğrencilerin akademik ve kişisel gelişimlerini en üst düzeye çıkarmaktır.
              </p>
              <div class="text-center">
                <a href="about.php" class="more-btn">Learn More <i class="bx bx-chevron-right"></i></a>
              </div>
            </div>
          </div>
          <div class="col-lg-8 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="100">
            <div class="icon-boxes d-flex flex-column justify-content-center">
              <div class="row">
                <div class="col-xl-4 d-flex align-items-stretch">
                  <div class="icon-box mt-4 mt-xl-0">
                    <i class="bx bx-receipt"></i>
                    <h4>Deneyimli Eğitmenler</h4>
                    <p>ODTÜ ve Bilkent'ten uzman eğitmenler</p>
                  </div>
                </div>
                <div class="col-xl-4 d-flex align-items-stretch">
                  <div class="icon-box mt-4 mt-xl-0">
                    <i class="bx bx-cube-alt"></i>
                    <h4>Esnek Öğrenme</h4>
                    <p>Kendi hızınızda ve istediğiniz yerde öğrenme imkanı</p>
                  </div>
                </div>
                <div class="col-xl-4 d-flex align-items-stretch">
                  <div class="icon-box mt-4 mt-xl-0">
                    <i class="bx bx-box"></i>
                    <h4>Başarı Odaklılık</h4>
                    <p>Sonuca ulaştıran eğitim yöntemleri</p>
                  </div>
                </div>
              </div>
            </div><!-- End .content-->
          </div>
        </div>

      </div>
    </section><!-- End Why Us Section -->


    <!-- ======= Popular Courses Section ======= -->
    <section id="popular-courses" class="courses">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>Dersler</h2>
          <p>Popüler Dersler</p>
        </div>

        <div class="row">

          <div class="col-lg-3 col-md-2 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="100">
            <div class="course-item position-relative">
              <img src="assets/img/course-1.jpg" class="img-fluid" alt="...">
              <div class="course-content">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <h4>Coding</h4>                  
                </div>
                <h3><a href="course-details.html" class="readmore stretched-link">Web Tasarımı</a></h3>
                <p>Web tasarımı ve geliştirme konusunda kapsamlı eğitimler</p>                
              </div>
            </div>
          </div> <!-- End Course Item-->

          <div class="col-lg-3 col-md-2 d-flex align-items-stretch mt-4 mt-md-0" data-aos="fade-up" data-aos-delay="200">
            <div class="course-item position-relative">
              <img src="assets/img/course-2.jpg" class="img-fluid" alt="...">
              <div class="course-content">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <h4>Calculus</h4>                  
                </div>
                <h3><a href="course-details.html" class="readmore stretched-link">Applications of Differentiation</a></h3>
                <p>Calculus derslerinde diferansiyasyonun farklı uygulamaları öğretilmektedir</p>
              </div>
            </div>
          </div> <!-- End Course Item-->

          <div class="col-lg-3 col-md-2 d-flex align-items-stretch mt-4 mt-md-0" data-aos="fade-up" data-aos-delay="200">
            <div class="course-item position-relative">
              <img src="assets/img/course-2.jpg" class="img-fluid" alt="...">
              <div class="course-content">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <h4>TYT Matematik</h4>                  
                </div>
                <h3><a href="course-details.html" class="readmore stretched-link">Problemler</a></h3>
                <p>TYT sınavına yönelik matematik problemleri ve çözüm teknikleri</p>
              </div>
            </div>
          </div> <!-- End Course Item-->

          <div class="col-lg-3 col-md-2 d-flex align-items-stretch mt-4 mt-lg-0" data-aos="fade-up" data-aos-delay="300">
            <div class="course-item position-relative">
              <img src="assets/img/course-3.jpg" class="img-fluid" alt="...">
              <div class="course-content">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <h4>Coding</h4>
                  </div>
                <h3><a href="courses.html" class="readmore stretched-link">Pyhton</a></h3>
                <p>Python programlama dilinin temelleri ve ileri seviye kodlama</p>
                </div>
            </div>
          </div> <!-- End Course Item-->

          <div class="col-lg-3 col-md-2 d-flex align-items-stretch mt-4 mt-lg-5" data-aos="fade-up" data-aos-delay="300">
            <div class="course-item position-relative">
              <img src="assets/img/course-3.jpg" class="img-fluid" alt="...">
              <div class="course-content">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <h4>Content</h4>
                  </div>

                <h3><a href="courses.html" class="readmore stretched-link">Copywriting</a></h3>
                <p>Profesyonel metin yazarlığı ve içerik oluşturma teknikleri</p>
                </div>
            </div>
          </div> <!-- End Course Item-->

        </div>

      </div>
    </section><!-- End Popular Courses Section -->

    <!-- ======= Trainers Section ======= -->
    <section id="trainers" class="trainers">
      <div class="container" data-aos="fade-up">

      <div class="section-title">
          <h2>Ekibimiz</h2>
          <p>Deneyimli Hocalarımız</p>
        </div>

        <div class="row" data-aos="zoom-in" data-aos-delay="100">
          <div class="col-lg-4 col-md-6 d-flex align-items-stretch">
            <div class="member">
              <img src="assets/img/trainers/trainer-1.jpg" class="img-fluid" alt="">
              <div class="member-content">
                <h4>Walter White</h4>
                <span>Web Development</span>
                <p>
                  Magni qui quod omnis unde et eos fuga et exercitationem. Odio veritatis perspiciatis quaerat qui aut aut aut
                </p>
                <div class="social">
                  <a href=""><i class="bi bi-twitter"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 d-flex align-items-stretch">
            <div class="member">
              <img src="assets/img/trainers/trainer-2.jpg" class="img-fluid" alt="">
              <div class="member-content">
                <h4>Sarah Jhinson</h4>
                <span>Marketing</span>
                <p>
                  Repellat fugiat adipisci nemo illum nesciunt voluptas repellendus. In architecto rerum rerum temporibus
                </p>
                <div class="social">
                  <a href=""><i class="bi bi-twitter"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 d-flex align-items-stretch">
            <div class="member">
              <img src="assets/img/trainers/trainer-3.jpg" class="img-fluid" alt="">
              <div class="member-content">
                <h4>William Anderson</h4>
                <span>Content</span>
                <p>
                  Voluptas necessitatibus occaecati quia. Earum totam consequuntur qui porro et laborum toro des clara
                </p>
                <div class="social">
                  <a href=""><i class="bi bi-twitter"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div>

        </div>

      </div>
    </section><!-- End Trainers Section -->


    <!-- ======= Testimonials Section ======= -->
    <section id="testimonials" class="testimonials">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>YORUMLAR</h2>
          <p>Öğrencilerimizden Dinleyin</p>
        </div>

        <div class="testimonials-slider swiper" data-aos="fade-up" data-aos-delay="100">
          <div class="swiper-wrapper">

            <div class="swiper-slide">
              <div class="testimonial-wrap">
                <div class="testimonial-item">
                  <img src="assets/img/testimonials/testimonials-1.jpg" class="testimonial-img" alt="">
                  <h3>Emre Çelik</h3>
                  <h4>YKS Öğrencisi</h4>
                  <p>
                    <i class="bx bxs-quote-alt-left quote-icon-left"></i>
                    Dersler çok kaliteli ve eğitmenler çok bilgili. Herkese tavsiye ederim!
                    <i class="bx bxs-quote-alt-right quote-icon-right"></i>
                  </p>
                </div>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-wrap">
                <div class="testimonial-item">
                  <img src="assets/img/testimonials/testimonials-2.jpg" class="testimonial-img" alt="">
                  <h3>Elif Demir</h3>
                  <h4>Bilgisayar Müh. Öğrencisi</h4>
                  <p>
                    <i class="bx bxs-quote-alt-left quote-icon-left"></i>
                    Eğitim programı gerçekten harika. Kısa sürede çok şey öğrendim.
                    <i class="bx bxs-quote-alt-right quote-icon-right"></i>
                  </p>
                </div>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-wrap">
                <div class="testimonial-item">
                  <img src="assets/img/testimonials/testimonials-3.jpg" class="testimonial-img" alt="">
                  <h3>Ayşe Apaydın</h3>
                  <h4>YKS Öğrencisi</h4>
                  <p>
                    <i class="bx bxs-quote-alt-left quote-icon-left"></i>
                    Bu platform sayesinde sınavlarımda büyük başarı elde ettim.
                    <i class="bx bxs-quote-alt-right quote-icon-right"></i>
                  </p>
                </div>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-wrap">
                <div class="testimonial-item">
                  <img src="assets/img/testimonials/testimonials-4.jpg" class="testimonial-img" alt="">
                  <h3>Murat Doğan</h3>
                  <h4>Endüstri Müh. Öğrencisi</h4>
                  <p>
                    <i class="bx bxs-quote-alt-left quote-icon-left"></i>
                    Eğitmenler gerçekten yardımcı ve destekleyici.
                    <i class="bx bxs-quote-alt-right quote-icon-right"></i>
                  </p>
                </div>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-wrap">
                <div class="testimonial-item">
                  <img src="assets/img/testimonials/testimonials-5.jpg" class="testimonial-img" alt="">
                  <h3>Mustafa Kılıçarslan</h3>
                  <h4>Lise Öğrencisi</h4>
                  <p>
                    <i class="bx bxs-quote-alt-left quote-icon-left"></i>
                    Harika bir eğitim deneyimi sunuyorlar. Kesinlikle denemelisiniz!
                    <i class="bx bxs-quote-alt-right quote-icon-right"></i>
                  </p>
                </div>
              </div>
            </div><!-- End testimonial item -->

          </div>
          <div class="swiper-pagination"></div>
        </div>

      </div>
    </section><!-- End Testimonials Section -->

  </main><!-- End #main -->

  <?php
    include("footer.php")
  ?>

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

</body>

</html>