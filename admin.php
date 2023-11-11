<?php

// Include config 
include 'config.php';

// Handle form submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get message
  $message = $_POST["message"]; 
  
  // Save message
  saveMessage($message);

}

// Get current message
$current_message = getMessage();

?>

<!DOCTYPE html>
<html>
<body>
<?php  echo '<a href="index.php"><button>Go to index</button></a>'; ?>
<h1>Message System</h1>

<p><?php echo $current_message; ?></p> 

<form method="post">
  <input type="text" name="message">
  <button type="submit">Save Message</button>
</form>

</body>
</html>
<?php

// config.php

// config.php

function saveMessage($message) {
    global $conn;
  
    // Check if a message with id=1 already exists
    $existingMessage = getMessage();
  
    if ($existingMessage === "No message saved") {
      // If no message exists, insert a new record
      $sql = "INSERT INTO system_messages (id, message) VALUES (1, ?)";
    } else {
      // If a message exists, update the existing record
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
