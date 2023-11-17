<?php
session_save_path('/var/www/html/session_data');
session_start(); ?>

<!DOCTYPE html>
<html>

<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <link rel="shortcut icon" href="images/s4.png" type="image/x-icon">

  <title>AI-MED</title>

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

  <!-- fonts style -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Poppins:400,600,700&display=swap" rel="stylesheet" />
  <!-- nice select -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" integrity="sha256-mLBIhmBvigTFWPSCtvdu6a76T+3Xyt+K571hupeFLg4=" crossorigin="anonymous" />
  <!-- datepicker -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css">
  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />
</head>

<body class="sub_page">
  <div class="hero_area">
    <!-- header section strats -->
    <header class="header_section">
      <div class="container-fluid">
        <nav class="navbar navbar-expand-lg custom_nav-container ">
          <a class="navbar-brand" href="index.php">
            <h3>
              AI-MED
            </h3>
          </a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse ml-auto" id="navbarSupportedContent">
            <ul class="navbar-nav  ml-auto">
              <li class="nav-item">
                <a class="nav-link" href="index.php">Accueil <span class="sr-only">(current)</span></a>
              </li>
              <li class="nav-item active">
                <a class="nav-link" href="about.php"> A propos</a>
              </li>
              <?php if (isset($_SESSION['loggedin'])) { ?>
              <li class="nav-item ">
                <a class="nav-link" href="setup.php">Bienvenue, <?php echo htmlspecialchars($_SESSION['last_name'] ); ?></a>
              </li>
            </ul>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {?>
              <form class="form-inline" action="./admin" method="post">
              <button class="btn nav_search-btn" type="submit">
                  <i class="fa fa-cogs" aria-hidden="true"></i>
              </button>
            </form>
            <?php } ?>
            
            <form class="form-inline" action="logout.php" method="post">
              <button class="btn nav_search-btn" type="submit">
                  <i class="fa fa-sign-out" aria-hidden="true"></i>
              </button>
            </form>
            
            <?php  } else{ ?>
            <form class="form-inline" action="login.php" method="post">
              <button class="btn nav_search-btn" type="submit">
                  <i class="fa fa-user" aria-hidden="true"></i>
              </button>
            </form>

              <?php }?>
          </div>
        </nav>
      </div>
    </header>    <!-- end header section -->
  </div>

  <!-- about section -->
  <section class="about_section layout_padding">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>
          <span class="design_dot"></span>
          A propos de notre IA
        </h2>
      </div>
      <div class="col-md-7 mx-auto px-0">
        <div class="img-box b2">
          <img src="images/about-img.png" alt="" />
        </div>
      </div>
      <div class="col-md-9 mx-auto px-0">
        <div class="detail-box">
          <p>
            Je verrai
          </p>
          <a href="">
            LIRE PLUS
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- end about section -->

  <!-- map section -->


  <!-- end map section -->

  <!-- info section -->
  <?php include 'footer.php'; ?>

  <!-- footer section -->

  <script src="js/jquery-3.4.1.min.js"></script>
  <script src="js/bootstrap.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
  <!-- nice select -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js" integrity="sha256-Zr3vByTlMGQhvMfgkQ5BtWRSKBGa2QlspKYJnkjZTmo=" crossorigin="anonymous"></script>
  <!-- datepicker -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
  <!-- custom js -->
  <script src="js/custom.js"></script>
  <!-- Google Map -->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCh39n5U-4IoWpsVGUHWdqB6puEkhRLdmI&callback=myMap">
  </script>
  <!-- End Google Map -->
</body>

</html>