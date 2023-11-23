<?php
// administrator.php

session_start();

// Include database configuration
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Process form submission for announcements
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['announcement'])) {
        $announcement = mysqli_real_escape_string($connection, $_POST['announcement']);
        $query = "INSERT INTO announcements (content) VALUES ('$announcement')";
        mysqli_query($connection, $query);

        // Redirect to avoid form resubmission on page refresh
        header('Location: administrator.php');
        exit();
    }
}

// Process form submission for student information
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['student_number'], $_POST['last_name'], $_POST['first_name'], $_POST['middle_name'])) {
        $studentNumber = mysqli_real_escape_string($connection, $_POST['student_number']);
        $lastName = mysqli_real_escape_string($connection, $_POST['last_name']);
        $firstName = mysqli_real_escape_string($connection, $_POST['first_name']);
        $middleName = mysqli_real_escape_string($connection, $_POST['middle_name']);

        // Check if a file was uploaded
        if ($_FILES['student_picture']['error'] == 0) {
            $imagePath = 'student_images/' . basename($_FILES['student_picture']['name']);
            move_uploaded_file($_FILES['student_picture']['tmp_name'], $imagePath);
            $query = "INSERT INTO students (student_number, last_name, first_name, middle_name, picture_path) VALUES ('$studentNumber', '$lastName', '$firstName', '$middleName', '$imagePath')";
        } else {
            $query = "INSERT INTO students (student_number, last_name, first_name, middle_name) VALUES ('$studentNumber', '$lastName', '$firstName', '$middleName')";
        }

        mysqli_query($connection, $query);

        // Redirect to avoid form resubmission on page refresh
        header('Location: administrator.php');
        exit();
    }
}

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete_student' && isset($_GET['id'])) {
    $studentId = mysqli_real_escape_string($connection, $_GET['id']);
    $deleteQuery = "DELETE FROM students WHERE id = '$studentId'";
    mysqli_query($connection, $deleteQuery);

    // Redirect to avoid form resubmission on page refresh
    header('Location: administrator.php');
    exit();
}

// Handle delete action for announcements
if (isset($_GET['action']) && $_GET['action'] === 'delete_announcement' && isset($_GET['id'])) {
    $announcementId = mysqli_real_escape_string($connection, $_GET['id']);
    $deleteQuery = "DELETE FROM announcements WHERE id = '$announcementId'";
    mysqli_query($connection, $deleteQuery);

    // Redirect to avoid form resubmission on page refresh
    header('Location: administrator.php');
    exit();
}

// Fetch announcements from the database
$announcementsQuery = "SELECT * FROM announcements ORDER BY id DESC";
$announcementsResult = mysqli_query($connection, $announcementsQuery);
$announcements = mysqli_fetch_all($announcementsResult, MYSQLI_ASSOC);

// Fetch student information from the database
$studentsQuery = "SELECT * FROM students ORDER BY id DESC";
$studentsResult = mysqli_query($connection, $studentsQuery);
$students = mysqli_fetch_all($studentsResult, MYSQLI_ASSOC);

// Close the database connection
mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <title>Administrator Dashboard</title>
</head>
<style>
    /*Css Style*/
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

h2 {
    color: #333;
    margin-bottom: 10px;
}

form {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 5px;
    color: #555;
}

input[type="text"],
textarea,
button,
input[type="file"] {
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
}

button:hover {
    background-color: #012908;
}

ul {
    list-style: none;
    padding: 0;
}

ul li {
    position: relative;
    margin-bottom: 20px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
    background-color: #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

ul li a {
    margin-left: 10px;
    text-decoration: none;
    color: #017915;
}

ul li a:hover {
    text-decoration: underline;
}

.home-head {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #017915;
    border-radius: 50%;
    padding: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.home-button {
    display: block;
    color: #fff;
    text-decoration: none;
    font-weight: bold;
    text-align: center;
    padding: 10px;
}

.home-button:hover {
    background-color:#012908;
}

</style>
<body>
    <div class="container">
        <h1>Administrator Dashboard</h1>
<div class="home-head">
<a href="index.php" class="home-button">
        <i class="fas fa-home"></i>
    </a>
</div>

        <form method="POST" action="">
            <label for="announcement">Add Announcement:</label>
            <textarea name="announcement" id="announcement" rows="4" cols="50" required></textarea>
            <button type="submit">Add Announcement</button>
        </form>

        <ul>
        <?php foreach ($announcements as $announcement): ?>
    <li>
        <?php
        $trimmedContent = strlen($announcement['content']) > 50 ? substr($announcement['content'], 0, 50) . '...' : $announcement['content'];
        echo htmlspecialchars($trimmedContent);
        ?>
        <a href="edit_announcement.php?id=<?= $announcement['id'] ?>">Edit</a>
        <a href="#" onclick="confirmDeleteAnnouncement(<?= $announcement['id'] ?>)">Delete</a>
    </li>
<?php endforeach; ?>

        </ul>

        <h2>Student Information</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <label for="student_number">Student Number:</label>
            <input type="text" name="student_number" required>

            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" required>

            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" required>

            <label for="middle_name">Middle Name:</label>
            <input type="text" name="middle_name" required>

            <label for="student_picture">Student Picture:</label>
            <input type="file" name="student_picture">

            <button type="submit">Add Student</button>
        </form>

        <ul>
            <?php foreach ($students as $student): ?>
                <li>
                    <?= $student['student_number'] ?> -
                    <?= $student['last_name'] ?>, <?= $student['first_name'] ?> <?= $student['middle_name'] ?>
                    <a href="edit_student.php?id=<?= $student['id'] ?>">Edit</a>
                    <a href="#" onclick="confirmDelete(<?= $student['id'] ?>)">Delete</a>
                    <a href="student_profile.php?id=<?= $student['id'] ?>">Profile</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <script>
        // JavaScript function to confirm deletion
        function confirmDelete(studentId) {
            var confirmDelete = confirm('Are you sure you want to delete this student?');
            if (confirmDelete) {
                window.location.href = 'administrator.php?action=delete_student&id=' + studentId;
            }
        }

        // JavaScript function to confirm deletion of announcements
        function confirmDeleteAnnouncement(announcementId) {
            var confirmDelete = confirm('Are you sure you want to delete this announcement?');
            if (confirmDelete) {
                window.location.href = 'administrator.php?action=delete_announcement&id=' + announcementId;
            }
        }
    </script>
</body>
</html>
