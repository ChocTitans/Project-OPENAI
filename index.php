<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit();
}

echo '<a href="logout.php"><button>Logout</button></a>';

if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
    echo "Welcome Admin!";
    echo '<a href="admin.php"><button>Go to Admin</button></a>';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatGPT PHP App</title>
</head>
<body>

<?php
echo '<a href="index.php"><button>Go to index</button></a>';
// Function to send a message to ChatGPT API
function sendMessageToChatGPT($user_input) {
    // Retrieve the system message from the database
    $conn = include 'config.php'; // Assuming config.php includes the database connection
    $system_message = getSystemMessageFromDatabase($conn);

    $api_key = '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'; // Replace with your OpenAI API key
    $api_url = 'https://api.openai.com/v1/chat/completions';

    $post_fields = array(
        "model" => "gpt-3.5-turbo",
        "messages" => array(
            array(
                "role" => "system",
                "content" => $system_message // Use the retrieved system message
            ),
            array(
                "role" => "user",
                "content" => $user_input
            )
        ),
        "max_tokens" => 100,
        "temperature" => 0.7  // You can adjust the temperature based on your preference
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

    // Decode the JSON response
    $chat_data = json_decode($response, true);

    // Display the conversation
    echo '<p><strong>You:</strong> ' . $user_input . '</p>';
    echo '<p><strong>ChatGPT:</strong> ' . $chat_data['choices'][0]['message']['content'] . '</p>';

    // Save the conversation to the database
    saveToDatabase($user_input, $chat_data['choices'][0]['message']['content'], $conn); // Pass $conn as the third argument
}

// Function to get the system message from the database
function getSystemMessageFromDatabase($conn) {
    // Query to retrieve the system message from the database
    $sql = "SELECT message FROM system_messages WHERE id=1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch the system message
        $system_message = $result->fetch_assoc()["message"];
    } else {
        // Provide a default system message if none is found
        $system_message = "Default system message";
    }

    return $system_message;
}

// Function to save the conversation to the database
function saveToDatabase($user_input, $chat_response, $conn) {
    // Use prepared statement to prevent SQL injection
    $sql = "INSERT INTO conversations (user_input, chat_response) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $user_input, $chat_response);

    // Execute the statement
    if ($stmt->execute()) {
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_input = $_POST["user_message"];
    sendMessageToChatGPT($user_input);
}
?>

<!-- Simple form for user input -->
<form method="post" action="">
    <label for="user_message">Your message:</label>
    <input type="text" id="user_message" name="user_message" required>
    <button type="submit">Send</button>
</form>

</body>
</html>
