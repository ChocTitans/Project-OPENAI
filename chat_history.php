<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Chat with Chan</title>
    <style>
      body {
        font-family: Arial, sans-serif;
        font-size: 16px;
      }
      .chatbox {
        height: 400px;
        overflow: auto;
        border: 1px solid #ccc;
        padding: 10px;
      }
      .message {
        margin-bottom: 10px;
      }
      .user {
        color: blue;
      }
      .bot {
        color: green;
      }
      input[type="text"] {
        width: 100%;
        padding: 10px;
        box-sizing: border-box;
        border: 1px solid #ccc;
      }
    </style>
  </head>
  <body>
    <h1>Chat with Chan</h1>
    <div class="chatbox" id="chatbox"></div>
    <input type="text" id="message" placeholder="Type your message here...">
    <button id="send">Send</button>
    <script>
      var chatbox = document.getElementById("chatbox");
      var message = document.getElementById("message");
      var send = document.getElementById("send");
      
      send.addEventListener("click", function() {
        var userMessage = message.value;
        var userDiv = document.createElement("div");
        userDiv.className = "message user";
        userDiv.innerHTML = "<strong>You:</strong> " + userMessage;
        chatbox.appendChild(userDiv);
        
        message.value = "";
        
        fetch("/get_response?message=" + encodeURIComponent(userMessage))
          .then(function(response) {
            return response.text();
          })
          .then(function(botMessage
          ) {
            var botDiv = document.createElement("div");
            botDiv.className = "message bot";
            botDiv.innerHTML = "<strong>Chan:</strong> " + botMessage;
            chatbox.appendChild(botDiv);
            
            chatbox.scrollTop = chatbox.scrollHeight;
        });
        });
    </script>

    </body>
</html>