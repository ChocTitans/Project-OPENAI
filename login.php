<?php
session_start();

include './include/config.php';

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: ./setup.php");
    exit;
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, password, role, last_name FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

// After successful login
if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $hashedPassword, $role, $lastName);
    $stmt->fetch();

    // Verify the password
    if (password_verify($password, $hashedPassword)) {
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $id; // Store user ID in the session
        $_SESSION['email'] = $email;
        $_SESSION['last_name'] = $lastName;
        $_SESSION['role'] = $role;
        header("Location: setup.php");
    } 
    }



    $stmt->close();
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
          <a class="navbar-brand" href="index.html">
            <h3>
              AI-MED
            </h3>
          </a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse ml-auto" id="navbarSupportedContent">
          <ul class="navbar-nav  ml-auto">
              <li class="nav-item ">
                <a class="nav-link" href="index.php">Accueil </a>
              </li>
              <li class="nav-item ">
                <a class="nav-link" href="about.php">A propos</a>
              </li>
            </ul>
            
            <form class="form-inline nav-item active" action="login.php" method="post">
                <button class="btn nav_search-btn" type="submit">
                    <i class="fa fa-user" aria-hidden="true"></i>
                </button>
            </form>
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
                <form method="post">
                    <h4>
                        <span class="design_dot"></span>
                        Se connecter
                    </h4>
                    <div class="form-row">
                        <div class="form-group col-lg-4">
                            <label for="inputEmail">E-mail</label>
                            <input type="email" class="form-control" id="inputEmail" name="email" placeholder="">
                        </div>
                        <div class="form-group col-lg-4">
                            <label for="inputPassword">Mot de passe</label>
                            <input type="password" class="form-control" id="inputPassword" name="password" placeholder="">
                        </div>
                    </div>
                    <div class="btn-box">
                        <button type="submit" name="login" class="btn">Envoyer</button>
                    </div>
                </form>
                <a href="register.php">
                    <button type="button" class="btn" style="color: white;">Tu n'as pas de compte ? Enregistre toi</button>
                </a>

            </div>
        </div>
    </div>
</section>

  <!-- end book section -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

  <!-- info section -->
<?php include 'footer.php'; ?>
  <!-- end info section -->

</body>

</html>
