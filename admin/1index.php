<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit();
}

?>

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
  <link rel="stylesheet" type="text/css" href="../css/bootstrap.css" />

  <!-- fonts style -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Poppins:400,600,700&display=swap" rel="stylesheet" />
  <!-- nice select -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" integrity="sha256-mLBIhmBvigTFWPSCtvdu6a76T+3Xyt+K571hupeFLg4=" crossorigin="anonymous" />
  <!-- datepicker -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css">
  <!-- Custom styles for this template -->
  <link href="../css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="../css/responsive.css" rel="stylesheet" />
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
                <a class="nav-link" href="../index.php">Accueil <span class="sr-only">(current)</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="../about.php"> A propos</a>
              </li>
            <?php if (isset($_SESSION['loggedin'])) { ?>
              <li class="nav-item active">
                <a class="nav-link" href="../setup.php">Bienvenue, <?php echo htmlspecialchars($_SESSION['last_name'] ); ?></a>
              </li>
            </ul>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {?>
              <form class="form-inline" action="" method="post">
              <button class="btn nav_search-btn" type="submit">
                  <i class="fa fa-cogs" aria-hidden="true"></i>
              </button>
            </form>
            <?php } ?>
            
            <form class="form-inline" action="../logout.php" method="post">
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
    </header>
    <!-- end header section -->
  </div>

  <!-- book section -->

<section class="book_section layout_padding" id="loginSection">
    <div class="container">
        <div class="row">
            <div class="col">
            <form method="post" action="">
                <label for="user_message">Your message:</label>
                <input type="text" id="user_message" name="user_message" required>
                <button type="submit">Send</button>
            </form>

            </div>
        </div>
    </div>
</section>

  <!-- end book section -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

  <!-- info section -->
<?php include '../footer.php'; ?>
  <!-- end info section -->

</body>

</html>
