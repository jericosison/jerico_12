<?php
// edit_student.php

// Include database configuration
include 'config.php';

// Check if the 'id' parameter is present in the URL
if (isset($_GET['id'])) {
    $studentId = mysqli_real_escape_string($connection, $_GET['id']);

    // Fetch student information from the database
    $studentQuery = "SELECT * FROM students WHERE id = '$studentId'";
    $studentResult = mysqli_query($connection, $studentQuery);
    $student = mysqli_fetch_assoc($studentResult);

    // Process form submission for updating student information
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $studentNumber = mysqli_real_escape_string($connection, $_POST['student_number']);
        $lastName = mysqli_real_escape_string($connection, $_POST['last_name']);
        $firstName = mysqli_real_escape_string($connection, $_POST['first_name']);
        $middleName = mysqli_real_escape_string($connection, $_POST['middle_name']);

        // Check if a new file was uploaded
        if ($_FILES['student_picture']['error'] == 0) {
            $imagePath = 'student_images/' . basename($_FILES['student_picture']['name']);
            move_uploaded_file($_FILES['student_picture']['tmp_name'], $imagePath);
            $updateQuery = "UPDATE students SET
                            student_number = '$studentNumber',
                            last_name = '$lastName',
                            first_name = '$firstName',
                            middle_name = '$middleName',
                            picture_path = '$imagePath'
                            WHERE id = '$studentId'";
        } else {
            $updateQuery = "UPDATE students SET
                            student_number = '$studentNumber',
                            last_name = '$lastName',
                            first_name = '$firstName',
                            middle_name = '$middleName'
                            WHERE id = '$studentId'";
        }

        mysqli_query($connection, $updateQuery);
        header("Location: student_profile.php?id=$studentId");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Edit Student</title>
    <style>
        /*Css style*/
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background-color: #f4f4f4;
}

.container {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    text-align: center;
}

h1 {
    color: #017915;
}

form {
    margin-top: 20px;
    display: grid;
    gap: 10px;
    text-align: left;
}

label {
    font-weight: bold;
    display: block;
    margin-bottom: 5px;
}

input {
    width: 100%;
    padding: 8px;
    box-sizing: border-box;
}

img {
    max-width: 100%;
    height: auto;
    display: block;
    margin-top: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}
a,
button {
    background-color: #017915;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    text-align: center;
}
a:hover,
button:hover {
    background-color: #012908;
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Student</h1>

        <?php if (isset($student)): ?>
            <form method="POST" action="" enctype="multipart/form-data">
                <label for="student_number">Student Number:</label>
                <input type="text" name="student_number" value="<?= $student['student_number'] ?>" required>

                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" value="<?= $student['last_name'] ?>" required>

                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" value="<?= $student['first_name'] ?>" required>

                <label for="middle_name">Middle Name:</label>
                <input type="text" name="middle_name" value="<?= $student['middle_name'] ?>" required>

                <label for="student_picture">Student Picture:</label>
                <input type="file" name="student_picture">

                <img src="<?= $student['picture_path'] ?>" alt="Current Student Image">

                <button type="submit">Save Changes</button>
                <a href="administrator.php">Back</a>
            </form>
        <?php else: ?>
            <p>Student not found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
