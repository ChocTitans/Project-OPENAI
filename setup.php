<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit();
}


if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
    echo "Welcome Admin!";
    echo '<a href="logout.php"><button>Logout</button></a>';
    echo '<a href="./admin/admin.php"><button>Go to Admin</button></a>';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_input = $_POST["user_message"];
    sendMessageToChatGPT($user_input);
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
              <li class="nav-item ">
                <a class="nav-link" href="index.php">Home </a>
              </li>
              <li class="nav-item ">
                <a class="nav-link" href="about.php"> About</a>
              </li>
              <li class="nav-item active">
                <a class="nav-link" href="">Bienvenue, <?php echo htmlspecialchars($_SESSION['last_name'] ); ?><span class="sr-only">(current)</span></a>
              </li>
            </ul>
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
<script>
    $(document).ready(function () {
        // Switch to the login section when clicking the "Login" button
        $("#loginBtn").click(function () {
            $("#registerSection").hide();
            $("#loginSection").show();
        });

        // Switch to the register section when clicking the "Register" button
        $("#registerBtn").click(function () {
            $("#registerSection").show();
            $("#loginSection").hide();
        });
    });
</script>


<?php
function sendMessageToChatGPT($user_input) {
    $conn = include './include/config.php'; 
    $system_message = getSystemMessageFromDatabase($conn);

    $api_key = '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';
    $api_url = 'https://api.openai.com/v1/chat/completions';

    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    $post_fields = array(
        "model" => "gpt-3.5-turbo",
        "messages" => array(
            array(
                "role" => "system",
                "content" => $system_message 
            ),
            array(
                "role" => "user",
                "content" => $user_input
            )
        ),
        "max_tokens" => 100,
        "temperature" => 0.7 
    );

    $ch = curl_init();
    $header = array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key
    );

    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_fields));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
    }

    curl_close($ch);

    $chat_data = json_decode($response, true);

    echo '<p><strong>You:</strong> ' . $user_input . '</p>';
    echo '<p><strong>ChatGPT:</strong> ' . $chat_data['choices'][0]['message']['content'] . '</p>';

    saveToDatabase($user_input, $chat_data['choices'][0]['message']['content'], $user_id, $conn);
}

function getSystemMessageFromDatabase($conn) {
    $sql = "SELECT message FROM system_messages WHERE id=1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $system_message = $result->fetch_assoc()["message"];
    } else {
        $system_message = "Default system message";
    }

    return $system_message;
}

function saveToDatabase($user_input, $chat_response, $user_id, $conn) {
    $sql = "INSERT INTO conversations (user_input, chat_response, user_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Bind parameters with proper types
    $stmt->bind_param("sss", $user_input, $chat_response, $user_id);

    if ($stmt->execute()) {
        // Successful insertion
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>


  <!-- info section -->
<?php include 'footer.php'; ?>
  <!-- end info section -->

</body>

</html>
