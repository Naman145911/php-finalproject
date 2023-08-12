<?php
// edit_content.php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Include your database connection
include 'database.php';

// Handle form submission for updating content
if (isset($_POST['submit'])) {
    $contentId = mysqli_real_escape_string($conn, $_POST['content_id']);
    $newTitle = mysqli_real_escape_string($conn, $_POST['new_title']);
    $newBody = mysqli_real_escape_string($conn, $_POST['new_body']);

    $updateSql = "UPDATE content SET title=?, body=? WHERE id=?";
    $updateStmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($updateStmt, $updateSql)) {
        mysqli_stmt_bind_param($updateStmt, "ssi", $newTitle, $newBody, $contentId);
        mysqli_stmt_execute($updateStmt);
        header("Location: show_content.php");
        exit();
    } else {
        // Handle update error
        echo "Update error: " . mysqli_error($conn);
    }
}

// Retrieve content data from the database based on the provided content ID
if (isset($_GET['id'])) {
    $contentId = mysqli_real_escape_string($conn, $_GET['id']);
    $sql = "SELECT * FROM content WHERE id=?";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $contentId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $content = mysqli_fetch_assoc($result);
    } else {
        // Handle database error
        echo "Database error: " . mysqli_error($conn);
        exit();
    }
} else {
    // Redirect if no content ID is provided
    header("Location: show_content.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Content</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="./css/headerStyle.css">
    <link rel="stylesheet" href="./css/editContentStyle.css">
    <link rel="stylesheet" href="./css/footerStyle.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <h1>Edit Content</h1>
        <form action="edit_content.php" method="POST">
            <input type="hidden" name="content_id" value="<?php echo $content['id']; ?>">
            <input type="text" name="new_title" value="<?php echo $content['title']; ?>" required><br>
            <textarea name="new_body" rows="10" required><?php echo $content['body']; ?></textarea><br>
            <button type="submit" name="submit">Update Content</button>
        </form>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
