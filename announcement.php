<?php
// announcement.php

// Include database configuration
include 'config.php';

// Fetch announcements from the database
$announcementsQuery = "SELECT * FROM announcements ORDER BY created_at DESC";
$announcementsResult = mysqli_query($connection, $announcementsQuery);
$announcements = mysqli_fetch_all($announcementsResult, MYSQLI_ASSOC);

// Filter announcements based on the selected timeframe
if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];
    switch ($filter) {
        case 'today':
            $announcementsQuery = "SELECT * FROM announcements WHERE DATE(created_at) = CURDATE() ORDER BY created_at DESC";
            break;
        case 'this_week':
            $announcementsQuery = "SELECT * FROM announcements WHERE YEARWEEK(created_at) = YEARWEEK(NOW()) ORDER BY created_at DESC";
            break;
        case 'this_month':
            $announcementsQuery = "SELECT * FROM announcements WHERE MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW()) ORDER BY created_at DESC";
            break;
    }

    $announcementsResult = mysqli_query($connection, $announcementsQuery);
    $announcements = mysqli_fetch_all($announcementsResult, MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Announcements</title>
    <style>
    /*Css Style*/
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

.content .container {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin: 20px auto;
    padding: 20px;
    width: 80%;
    max-width: 800px;
    text-align: left;
}

.content h1 {
    color: #333;
    margin-bottom: 20px;
}

.filter-buttons {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 20px;
}

.content .filter-buttons .dropdown {
    position: relative;
    display: inline-block;
    margin-right: 100px;
}

.filter-buttons .dropdown i {
    margin-right: -5px;
}

.filter-buttons .dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
    z-index: 1;

}

.filter-buttons .dropdown:hover .dropdown-content {
    display: block;
}

.filter-buttons .dropdown-content a {
    color: #333;
    padding: 12px;
    display: block;
    text-decoration: none;
}

.filter-buttons .dropdown-content a:hover {
    background-color: #ddd;
}

.content ul {
    list-style: none;
    padding: 0;
}

.announcement-link {
display: block;
color: inherit;
text-decoration: none;
}

.content ul li {
position: relative;
margin-bottom: 20px;
padding: 20px;
border: 1px solid #ccc;
border-radius: 8px;
background-color: #fff;
box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
overflow: hidden;
}

.content ul li:hover {
background-color: #f0f0f0;
}

.content .content {
font-size: 18px;
white-space: nowrap;
overflow: hidden;
text-overflow: ellipsis;
max-width: calc(100% - 20px);
}

.date {
    position: absolute;
    bottom: 10px;
    right: 10px;
    font-size: 12px;
    color: #888;
    text-transform: lowercase;
    display: block;
}

.icon {
    position: absolute;
    top: 10px;
    left: 10px;
    font-size: 24px;
    color: #017915;
}
    </style>
</head>
<body>
    <div class="content container">
        <h1>Announcements</h1>

        <div class="filter-buttons">
            <div class="dropdown">
                <i class="fas fa-filter"></i>
                <div class="dropdown-content">
                    <a href="announcement.php">All</a>
                    <a href="index.php?filter=today">Today</a>
                    <a href="announcement.php?filter=this_week">This Week</a>
                    <a href="announcement.php?filter=this_month">This Month</a>
                </div>
            </div>
        </div>

        <ul>
        <?php foreach ($announcements as $announcement): ?>
            <li>
            <a class="announcement-link" href="announcement_list.php?id=<?= $announcement['id'] ?>">
               <?= strlen($announcement['content']) > 60 ? substr($announcement['content'], 0, 60) . '...' : $announcement['content'] ?>
               <span class="date"><?= strtolower(date('F j, Y', strtotime($announcement['created_at']))) ?></span>
            </a>
            </li>
        <?php endforeach; ?>
        </ul>
    </div>
</body>

</html>