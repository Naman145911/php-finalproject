<?php session_start();
 ?>
<!DOCTYPE html>
<html>
<head>
    <title>Home Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/headerStyle.css">
    <link rel="stylesheet" href="css/footerStyle.css">
    <link rel="stylesheet" href="css/indexStyle.css">
</head>
<body>
    <?php include('./header.php'); ?>
    <main>
    <main>
        <!-- Hero section -->
        <div class="hero">
            <h1>Welcome to Blog.io!</h1>
        </div>

        <!-- Featured posts -->
        <div class="featured-posts">
            <?php
            include 'database.php';

            // Query to select three random posts
            $sql = "SELECT id, title, body, author FROM content ORDER BY RAND() LIMIT 3";
            $result = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="featured-post">';
                echo '<h3>' . $row['title'] . '</h3>';
                echo '<p>' . $row['body'] . '</p>';
                echo '<p>Author: ' . $row['author'] . '</p>';
                echo '</div>';
            }

            // Close the database connection
            mysqli_close($conn);
            ?>
        </div>
        </div>

    <?php include('./footer.php'); ?>
</body>
</html>
