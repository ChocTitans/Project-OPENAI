<?php

include '../include/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $message = $_POST["user_message"]; 
  
  saveMessage($message);

}

$current_message = getMessage();

?>

<!DOCTYPE html>
<html>
<body>
<?php  echo '<a href="../index.php"><button>Go to index</button></a>'; ?>
<h1>Message System</h1>

<p><?php echo $current_message; ?></p> 
<label for="user_message">Your message:</label>

<form method="post" action="">
    <textarea id="user_message" name="user_message" rows="4" cols="50" required></textarea>
    <br>
    <button type="submit">Send</button>
</form>

</body>
</html>
<?php



function saveMessage($message) {
    global $conn;
  
    $existingMessage = getMessage();
  
    if ($existingMessage === "No message saved") {
      $sql = "INSERT INTO system_messages (id, message) VALUES (1, ?)";
    } else {
      $sql = "UPDATE system_messages SET message=? WHERE id=1";
    }
  
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $message);
  
    if(!$stmt->execute()) {
      echo "Error saving message: " . $stmt->error;
    }
  
    $stmt->close();
  }
  

function getMessage() {

  global $conn;  

  $sql = "SELECT message FROM system_messages  WHERE id=1";

  $result = $conn->query($sql);

  if($result->num_rows > 0) {
    return $result->fetch_assoc()["message"];  
  } else {
    return "No message saved";
  }

}
?>
