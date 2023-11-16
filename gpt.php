<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Application</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Additional styles if needed */
        .chat-bubble {
            max-width: 80%;
            overflow-wrap: break-word;
        }
        .chat-container {
            max-height: 80vh;
            overflow-y: auto;
        }
    </style>
</head>
<body class="bg-gray-800 text-white font-sans leading-normal tracking-normal">

    <div class="flex flex-col h-screen">
        <!-- Chat container -->
        <div class="flex-grow overflow-hidden">
            <div class="chat-container p-4 space-y-4">
                <!-- Message from user -->
                <div class="flex items-center space-x-2">
                    <div class="rounded-full bg-blue-500 w-6 h-6"></div>
                    <div class="chat-bubble bg-gray-700 p-2 rounded-lg">
                        <span>YOU: Hello there</span>
                    </div>
                </div>
                <!-- Message from GPT -->
                <div class="flex items-center space-x-2 justify-end">
                    <div class="chat-bubble bg-gray-700 p-2 rounded-lg">
                        <span>I am GPT</span>
                    </div>
                    <div class="rounded-full bg-blue-500 w-6 h-6"></div>
                </div>
            </div>
        </div>

        <!-- Input and buttons -->
        <div class="p-4">
            <div class="flex space-x-4">
                <input type="text" class="flex-grow p-2 bg-gray-700 rounded outline-none" placeholder="Type your message here...">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Send</button>
                <button class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">Refresh</button>
            </div>
        </div>
    </div>

    <script>
        // JavaScript functionality can be added here if needed
    </script>

</body>
</html>