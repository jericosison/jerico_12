<?php
// student_list.php

// Include database configuration
include 'config.php';

// Fetch all students from the database
$studentsQuery = "SELECT * FROM students ORDER BY last_name, first_name";
$studentsResult = mysqli_query($connection, $studentsQuery);
$students = mysqli_fetch_all($studentsResult, MYSQLI_ASSOC);

// Filter students based on search query
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($connection, $_GET['search']);
    $studentsQuery = "SELECT * FROM students WHERE
CONCAT(student_number, last_name, first_name, middle_name) LIKE '%$search%'
ORDER BY last_name, first_name";
    $studentsResult = mysqli_query($connection, $studentsQuery);
    $students = mysqli_fetch_all($studentsResult, MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Student List</title>
</head>
<style>
    /*Css style*/
.content .container {
    margin: 20px auto;
    padding: 20px;
    max-width: 800px;
    text-align: center;
}

.content .container h1 {
    color: #333;
    
}

.content .search-container {
    margin-bottom: 20px;
    display: flex;
    justify-content: center;
    margin-left: 50px;

}

.content .search-container input {
    padding: 10px;
    width: 600px;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin-right: 5px;
}

.content .container .search-container button {
    padding: 10px;
    cursor: pointer;
    background-color: #017915;
    color: white;
    border: none;
    border-radius: 5px;
    margin-left: 5px;
}

.content .container ul {
    list-style: none;
    text-align: left;
}

.content .container ul li {
    margin-bottom: 10px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.content .container a {
    text-decoration: none;
    color: #017915;
}

.content .container a:hover {
    text-decoration: underline;
}

</style>
<body class="content student-list">
    <div class="content container">
        <h1>Student List</h1>

        <form id="content searchForm">
            <div class="content search-container">
                <input type="text" name="search" id="searchInput" placeholder="Search by ID, Last Name, First Name, or Middle Name">
                <button type="button" onclick="searchStudents()"><i class="fas fa-search"></i></button>
            </div>
        </form>

        <ul>
            <?php foreach ($students as $student): ?>
                <li>
                    <a href="student_profile.php?id=<?= $student['id'] ?>">
                        <?= $student['student_number'] ?> -
                        <?= $student['last_name'] ?>, <?= $student['first_name'] ?> <?= $student['middle_name'] ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <script>
        function searchStudents() {
            var searchInput = document.getElementById('searchInput').value;
            loadContent('student_list.php?search=' + encodeURIComponent(searchInput));
        }

        function loadContent(page) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.querySelector('.content').innerHTML = this.responseText;
                    executeScripts(this.responseText);
                }
            };
            xhttp.open('GET', page, true);
            xhttp.send();
        }

        function executeScripts(content) {
            // Extract and execute scripts from the loaded content
            var scripts = content.match(/<script\b[^>]*>([\s\S]*?)<\/script>/g);
            if (scripts) {
                scripts.forEach(function(script) {
                    var scriptTag = document.createElement('script');
                    scriptTag.innerHTML = script.replace(/<script\b[^>]*>/, '').replace(/<\/script>/, '');
                    document.head.appendChild(scriptTag);
                });
            }
        }
    </script>
</body>
</html>
