<?php
// edit_announcement.php

// Include database configuration
include 'config.php';

// Check if the 'id' parameter is provided in the URL
if (isset($_GET['id'])) {
    $announcementId = mysqli_real_escape_string($connection, $_GET['id']);

    // Fetch the specific announcement from the database
    $announcementQuery = "SELECT * FROM announcements WHERE id = $announcementId";
    $announcementResult = mysqli_query($connection, $announcementQuery);

    if ($announcementResult) {
        $announcement = mysqli_fetch_assoc($announcementResult);
    } else {
        // Handle the case where the announcement with the provided ID is not found
        echo "Announcement not found!";
        exit;
    }
} else {
    // Handle the case where the 'id' parameter is not provided
    echo "Invalid request!";
    exit;
}

// Process form submission for editing announcement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edited_announcement'])) {
        $editedAnnouncement = mysqli_real_escape_string($connection, $_POST['edited_announcement']);
        $updateQuery = "UPDATE announcements SET content = '$editedAnnouncement' WHERE id = $announcementId";
        mysqli_query($connection, $updateQuery);

        // Redirect to the view announcement page
        header('Location: announcement_list.php?id=' . $announcementId);
        exit();
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
    <title>Edit Announcement</title>
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

form {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 5px;
    color: #555;
}

.announcement-content {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 8px;
    background-color: #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    white-space: pre-line;
    word-wrap: break-word;
}

button,
.announcement {
    background-color: #017915;
    color: #fff;
    border: none;
    padding: 10px;
    cursor: pointer;
}

button:hover,
.announcement:hover {
    background-color: #012908;
}
.announcement a{
    padding-left: 300px;
    color: #fff;
    text-decoration: none;
}
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Announcement</h1>

        <form method="POST" action="">
            <label for="edited_announcement">Edit Announcement:</label>
            <div class="announcement-content"><?= htmlspecialchars($announcement['content']) ?></div>
            <textarea name="edited_announcement" id="edited_announcement" rows="4" cols="50" required style="display: none;"><?= htmlspecialchars($announcement['content']) ?></textarea>
            <button type="button" onclick="toggleEditMode()">Edit</button>
            <button type="submit" style="display: none;">Save Changes</button>
        </form>
    <div class="announcement">
        <a href="index.php?id=<?= $announcement['id'] ?>">Back</a>
    </div>
    </div>

    <script>
        function toggleEditMode() {
            var textarea = document.getElementById('edited_announcement');
            var contentDiv = document.querySelector('.announcement-content');
            var editButton = document.querySelector('button');
            var saveButton = document.querySelectorAll('button')[1];

            textarea.style.display = 'block';
            contentDiv.style.display = 'none';
            editButton.style.display = 'none';
            saveButton.style.display = 'block';
        }
    </script>
</body>
</html>
