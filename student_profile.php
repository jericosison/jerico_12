<?php
// student_profile.php

// Include database configuration
include 'config.php';

// Check if the 'id' parameter is present in the URL
if (isset($_GET['id'])) {
    $studentId = mysqli_real_escape_string($connection, $_GET['id']);

    // Fetch student information from the database
    $studentQuery = "SELECT * FROM students WHERE id = '$studentId'";
    $studentResult = mysqli_query($connection, $studentQuery);
    $student = mysqli_fetch_assoc($studentResult);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <style>
    /*Css style*/
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
    margin: 20px auto;
    padding: 20px;
    width: 80%;
    max-width: 800px;
    text-align: left;
}

h1 {
    color: #333;
    margin-bottom: 20px;
}

.profile {
    text-align: center;
}

.profile img {
    width: 100%;
    max-width: 200px;
    height: auto;
    border-radius: 50%;
    margin-bottom: 20px;
}

.profile p {
    margin: 5px 0;
    color: #555;
}

.index-button {
display: inline-block;
padding: 10px 20px;
background-color: #017915;
color: #fff;
text-align: center;
text-decoration: none;
border-radius: 5px;
margin-top: 20px;
margin-left: auto;
margin-right: auto;
display: block;
width: 100px;
}

.index-button:hover {
background-color: #017915;
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Student Profile</h1>
        <div class="home-head">
<a href="index.php" class="home-button">
        <i class="fas fa-home"></i>
    </a>
        <?php if (isset($student)): ?>
            <div class="profile">
                <img src="<?= $student['picture_path'] ?>" alt="Student Image">
                <p>ID: <?= $student['student_number'] ?></p>
                <p>Last Name: <?= $student['last_name'] ?></p>
                <p>First Name: <?= $student['first_name'] ?></p>
                <p>Middle Name: <?= $student['middle_name'] ?></p>
            </div>
        <?php else: ?>
            <p>Student not found.</p>
        <?php endif; ?>
        <a href="index.php" class="index-button">BACK</a>
    </div>
</body>
</html>

