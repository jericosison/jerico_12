<?php
// announcement_list.php

// Include database configuration
include 'config.php';

// Check if the 'id' parameter is provided in the URL
if (isset($_GET['id'])) {
    $announcementId = $_GET['id'];

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcement Details</title>
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

.announcement-details {
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

.announcement-content {
    margin-bottom: 15px;
    font-size: 16px;
}

.announcement-content div {
    white-space: pre-wrap;
    max-width: 100%;
    overflow-wrap: break-word;
}

a {
    color: #017915;
    text-decoration: none;
    font-weight: bold;
}

a:hover {
    text-decoration: underline;
}
    </style>
</head>
<body>
    <div class="container">
        <h1>Announcement Details</h1>

        <div class="announcement-details">
            <p><strong>Date:</strong> <?= date('F j, Y', strtotime($announcement['created_at'])) ?></p>
            <div class="announcement-content">
                <p><strong>Content:</strong></p>
                <div><?= formatText($announcement['content']) ?></div>
            </div>

            <a href="index.php">Back</a>
        </div>
    </div>
</body>
</html>
<?php
function formatText($text) {
    // Insert line breaks after every 80 characters
    return wordwrap($text, 80, "<br />\n", true);
}
?>
