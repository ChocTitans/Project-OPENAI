<?php
session_start();

include './include/config.php';

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: ./setup.php");
    exit;
}


$registrationSuccess = false;
$phoneormailexist = false;
$error = false;
// Handle registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    // Retrieve form inputs
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Check if email or phone already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR phone = ?");
    $stmt->bind_param("ss", $email, $phone);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Email or phone already exists, show an error message
        $phoneormailexist = true;
    } else {
        // Continue with the registration process
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $Password = $_POST['password'];
        $dateOfBirth = $_POST['date_of_birth'];

        // Default role
        $role = 'user';

        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO users (email, first_name, last_name, phone, password, date_of_birth, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $hashedPassword = password_hash($Password, PASSWORD_DEFAULT); // Hash the password

        $stmt->bind_param("sssssss", $email, $firstName, $lastName, $phone, $hashedPassword, $dateOfBirth, $role);

        if ($stmt->execute()) {
            // Registration succeeded
            // Start session
            // Set session role
            $_SESSION['user_id'] = $stmt->insert_id; // Store user ID in the session
            $_SESSION['role'] = $role;
            $registrationSuccess = true; // Set the variable to true
            header("Location: ./login.php");
        } else {
            $error = true;
        }

        $stmt->close();
    }
    
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

  <section class="book_section layout_padding" id="registerSection">
    <div class="container">
        <div class="row">
            <div class="col">
                <form method="post">
                    <h4>
                        <span class="design_dot"></span>
                        S'enregister
                    </h4>
                    <div class="form-row">
                        <div class="form-group col-lg-4">
                            <label for="inputEmail">E-mail</label>
                            <input type="email" class="form-control" id="inputEmail" name="email" placeholder="">
                        </div>
                        <div class="form-group col-lg-4">
                            <label for="inputFirstName">Nom</label>
                            <input type="text" class="form-control" id="inputFirstName" name="first_name" placeholder="">
                        </div>
                        <div class="form-group col-lg-4">
                            <label for="inputLastName">Prénom</label>
                            <input type="text" class="form-control" id="inputLastName" name="last_name" placeholder="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-lg-4">
                            <label for="inputPhone">Téléphone</label>
                            <input type="tel" class="form-control" id="inputPhone" name="phone">
                        </div>
                        <div class="form-group col-lg-4">
                            <label for="inputPassword">Mot de passe</label>
                            <input type="password" class="form-control" id="inputPassword" name="password" placeholder="">
                        </div>
                        <div class="form-group col-lg-4">
                            <label for="inputDate">Date De Naissance</label>
                            <div class="input-group date" id="inputDate" data-date-format="yyyy-mm-dd">
                                <input type="text" class="form-control" readonly name="date_of_birth">
                                <span class="input-group-addon date_icon">
                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <?php
                    // Display success message if registration succeeded
                    if ($registrationSuccess) {
                        echo '<p class="badge bg-succes">Succces ! Vous êtes maintenant enregistré !</p>';
                        usleep(500000); // 500000 microsecondes = 0.5 seconde

                    }
                    // Display error message if email or phone already exists
                    if ($phoneormailexist) {
                        echo '<p class="badge bg-danger">Erreur ! L\'email ou le numéro de téléphone existe déjà !</p>';
                    }
                    ?>
                    <div class="btn-box">
                        <button type="submit" name="register" class="btn">S'enregistrer</button>
                    </div>
                    
                </form>
                <a href="login.php">
                    <button type="button" class="btn" style="color: white;">Tu as un compte ? Connecte-toi</button>
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
