<?php
include './include/config.php';
session_save_path('/var/www/html/session_data');

// Handle login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        session_start();
        $_SESSION['loggedin'] = true;
        $row = mysqli_fetch_assoc($result);
        $_SESSION['role'] = $row['role'];
        header("Location: index.php");
    } else {
        echo "Invalid username or password!";
    }
}

// Handle registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $newUsername = $_POST['new_username']; // Change the field name to avoid conflict with login form
    $newPassword = $_POST['new_password']; // Change the field name to avoid conflict with login form

    // Default role
    $role = 'user';

    // Insert user
    $sql = "INSERT INTO users (username, password, role) VALUES ('$newUsername', '$newPassword', '$role')";

    if (mysqli_query($conn, $sql)) {
        // Registration succeeded
        // Start session
        session_start();
        // Set session role
        $_SESSION['role'] = $role;
        echo "Registration successful!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<h2>Login</h2>
<form method="post">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="submit" name="login" value="Login">
</form>

<h2>Register</h2>
<form method="post">
    <input type="text" name="new_username" placeholder="New Username" required>
    <input type="password" name="new_password" placeholder="New Password" required>
    <input type="submit" name="register" value="Register">
</form>
