<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit();
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $user_input = $_POST["user_message"];
  echo sendMessageToChatGPT($user_input); // Echo the JSON response
  exit(); // Terminate further execution
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
              <li class="nav-item">
                <a class="nav-link" href="index.php">Accueil <span class="sr-only">(current)</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="about.php"> A propos</a>
              </li>
            <?php if (isset($_SESSION['loggedin'])) { ?>
              <li class="nav-item active">
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
    </header>
    <!-- end header section -->
  </div>

  <!-- book section -->
  <section class="book_section layout_padding" id="loginSection">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="conversation-container p-4 bg-white" id="conversation-container">
                </div>
                <form id="chat-form" method="post" action="">
                    <div class="input-group">
                        <input type="text" id="user_message" name="user_message" class="form-control" placeholder="Type your message..." required>  
                        <span class="input-group-btn">
                            <button class="btn btn-info" type="submit">Send</button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>



  <!-- end book section -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


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
                "content" => "Bonjour, je suis AI-MED, votre assistant médical. Je suis là pour vous aider à trouver des informations sur les maladies." 
            ),
            array(
                "role" => "user",
                "content" => $user_input
            )
        ),
        "max_tokens" => 900,
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

    $response = array(
      'userMessage' => $user_input,
      'gpt3Message' => $chat_data['choices'][0]['message']['content']
  );

    saveToDatabase($user_input, $chat_data['choices'][0]['message']['content'], $user_id, $conn);
    return json_encode($response);

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
<script type="text/javascript">
  $(document).ready(function() {
      $('#chat-form').on('submit', function(event) {
          event.preventDefault();

          var user_input = $("#user_message").val();
          addMessage("You", user_input, true);

          $.ajax({
              type: 'POST',
              data: { user_message: user_input }
          })
          .done(function(response) {
              var parsedResponse = JSON.parse(response);
              addMessage("AI-MED", parsedResponse.gpt3Message, false);
          })
          .fail(function(xhr, status, error) {
              console.error(error); // Log any errors to the console
          });

          $("#user_message").val('');  
      });
  });

  function addMessage(name, message, isUser) {
    var alignClass = isUser ? 'text-left' : 'text-left';
    var messageHTML = '<div class="' + alignClass + '"><strong>' + name + ':</strong> ' + message + '</div>' + '<br>';
    $(messageHTML).hide().appendTo('#conversation-container').fadeIn(1000);
}

</script>



  <!-- info section -->
<?php include 'footer.php'; ?>
  <!-- end info section -->

</body>

</html>
