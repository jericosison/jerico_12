<?php
// login.php

session_start();

// Include database configuration
include 'config.php';

// Check if the user is already logged in
if (isset($_SESSION['user'])) {
    header('Location: administrator.php');
    exit();
}

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username'], $_POST['password'])) {
        $username = mysqli_real_escape_string($connection, $_POST['username']);
        $password = mysqli_real_escape_string($connection, $_POST['password']);

        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($connection, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            // Login successful
            $_SESSION['user'] = $username;
            header('Location: administrator.php');
            exit();
        } else {
            // Login failed
            $error_message = "Invalid username or password.";
        }
    }
}

// Close the database connection
mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>

    <style>
        /* Add these styles to your existing CSS or create a new CSS file */

body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

.container {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin: 100px auto; /* Adjust the margin as needed */
    padding: 20px;
    width: 80%;
    max-width: 400px;
    text-align: center;
}

h1 {
    color: #333;
    margin-bottom: 20px;
}

form {
    margin-top: 20px;
}

label {
    display: block;
    margin-bottom: 5px;
    color: #555;
}

input {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    box-sizing: border-box;
}

button {
    background-color: #017915;
    color: #fff;
    border: none;
    padding: 10px;
    cursor: pointer;
    width: 100%;
}

button:hover {
    background-color: #012908;
}

.error {
    color: #e74c3c;
    margin-top: 10px;
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>

        <?php if (isset($error_message)): ?>
            <p class="error"><?= $error_message ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" name="username" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
