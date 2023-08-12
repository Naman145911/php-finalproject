<?php
// show_content.php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Include your database connection
include 'database.php';

// Handle content deletion
if (isset($_POST['delete'])) {
    $deleteContentId = mysqli_real_escape_string($conn, $_POST['delete_content_id']);

    $deleteSql = "DELETE FROM content WHERE id=?";
    $deleteStmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($deleteStmt, $deleteSql)) {
        mysqli_stmt_bind_param($deleteStmt, "i", $deleteContentId);
        mysqli_stmt_execute($deleteStmt);
        // You can add further redirect or success message here
    } else {
        // Handle delete error
        echo "Delete error: " . mysqli_error($conn);
    }
}

// Retrieve content data from the database
$sql = "SELECT * FROM content";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Show Content</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="./css/headerStyle.css">
    <link rel="stylesheet" href="./css/showContentStyle.css">
    <link rel="stylesheet" href="./css/footerStyle.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <h1>Show Content</h1>
        <a href="add_content.php" class="add-button"><i class="fas fa-plus"></i></a> <!-- Add this line -->
        <div class="content-tiles">
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="content-tile">';
                echo '<h2>' . $row['title'] . '</h2>';
                echo '<p>' . nl2br($row['body']) . '</p>';
                echo '<p>' . nl2br('Author: '.$row['author']) . '</p>';
                if ($_SESSION['username'] === $row['author']) {
                    echo '<div class="button-group">';
                    echo '<a href="edit_content.php?id=' . $row['id'] . '" class="edit-button">Edit</a>';
                    echo '<form action="show_content.php" method="POST" onsubmit="return confirm(\'Are you sure you want to delete this content?\');">';
                    echo '<input type="hidden" name="delete_content_id" value="' . $row['id'] . '">';
                    echo '<button type="submit" name="delete" class="delete-button">Delete</button>';
                    echo '</form>';
                    echo '</div>';
                }
                echo '</div>';
            }
            ?>
        </div>

    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
