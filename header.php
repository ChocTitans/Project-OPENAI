<?php

function sendMessageToChatGPT($user_input) {
    $conn = include './include/config.php'; 

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
        "max_tokens" => 300,
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

    return json_encode($response);

}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // When form submitted, send user's message to ChatGPT.
  $response = sendMessageToChatGPT($_POST["user_message"]);

  // Add user's message to chat box
  echo '<script type="text/javascript">',
       'addMessage("You", '.json_encode($_POST["user_message"]).', true);',
       '</script>';

  // Add GPT's message to chat box
  $response = json_decode($response);
  echo '<script type="text/javascript">',
       'addMessage("ChatGPT", '.json_encode($response->gpt3Message).', false);',
       '</script>';
}

?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#chat-form').on('submit', function(event) {
            event.preventDefault();

            var user_input = $("#user_message").val();
            addMessage("You", user_input, true);

            $.ajax({
                url: window.location.href, // use the URL of the current page
                type: 'POST',
                data: { user_message: user_input }
            })
            .done(function(response) {
                var response = JSON.parse(response);
                addMessage("ChatGPT", response.gpt3Message, false);
            });

            $("#user_message").val('');  
        });
    })

    function addMessage(name, message, isUser) {
        var alignClass = isUser ? 'text-right' : 'text-left';
        var messageHTML = '<div class="<div class="' + alignClass + '"><strong>' + name + ':</strong> ' + message + '</div>';
        $(messageHTML).hide().appendTo('#conversation-container').fadeIn(1000);
    }
</script>