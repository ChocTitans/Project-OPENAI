<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit();
}

echo '<a href="logout.php"><button>Logout</button></a>';

if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
    echo "Welcome Admin!";
    echo '<a href="./admin/admin.php"><button>Go to Admin</button></a>';
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_input = $_POST["user_message"];
    sendMessageToChatGPT($user_input);
}
?>

<form method="post" action="">
    <label for="user_message">Your message:</label>
    <input type="text" id="user_message" name="user_message" required>
    <button type="submit">Send</button>
</form>

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

</body>
</html>
