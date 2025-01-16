 <head>
  
<style>
.navbar ul .get-started-btn {
  margin-left: 22px;
  background: #5fcf80;
  color: #fff;
  border-radius: 50px;
  padding: 8px 25px;
  white-space: nowrap;
  transition: 0.3s;
  font-size: 14px;
  display: inline-block;
}

.navbar ul .get-started-btn:hover {
  background: #3ac162;
  color: #fff;
}

@media (max-width: 768px) {
  .navbar ul .get-started-btn {
    margin: 0 15px 0 0;
    padding: 6px 18px;
  }
}

.navbar ul .teacher-signup-btn {
    background-color: #004225; /* Koyu yeşil renk */
    border: none; /* Kenarlık kaldırıldı */
    margin-left: 22px;
    color: #fff;
    border-radius: 50px;
    padding: 8px 25px;
    white-space: nowrap;
    transition: 0.3s;
    font-size: 14px;
    display: inline-block;
}

.navbar ul .teacher-signup-btn:hover {
    background-color: #003814; /* Fare ile üzerine gelindiğinde daha koyu bir yeşil */
    color: #fff;
}

@media (max-width: 768px) {
  .navbar ul .teacher-signup-btn {
    margin: 0 15px 0 0;
    padding: 6px 18px;
  }
}

</style> 

</head>
  
  
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

          <a href="signin.php" class="get-started-btn">    Giriş Yap    </a>
          <a href="signup-teacher.php" class="teacher-signup-btn">Öğretmen Ol</a>

        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->
     
      


    </div>
  </header><!-- End Header -->