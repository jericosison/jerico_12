<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Main Page</title>
</head>
<body>
    <div class="sidebar">
        <h2>BSIT 2-E</h2>
        <ul>
            <li><a href="announcement.php" onclick="loadContent('announcement.php'); return false;">Announcement</a></li>
            <li><a href="scheduled.php" onclick="loadContent('scheduled.php'); return false;">Scheduled</a></li>
            <li><a href="student_list.php" onclick="loadContent('student_list.php'); return false;">Student</a></li>
            <li><a href="administrator.php">Admin</a></li>
        </ul>
    </div>

    <div class="content psau">
        <img src="psau.png" width="460" height="460">
    </div>

    <script>
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
