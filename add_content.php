<?php
// add_content.php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Include your database connection
include 'database.php';

// Handle form submission for adding new content
if (isset($_POST['submit'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $body = mysqli_real_escape_string($conn, $_POST['body']);
    $author = $_SESSION['username'];

    $insertSql = "INSERT INTO content (title, body, author) VALUES (?, ?, ?)";
    $insertStmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($insertStmt, $insertSql)) {
        mysqli_stmt_bind_param($insertStmt, "sss", $title, $body, $author);
        mysqli_stmt_execute($insertStmt);
        header("Location: show_content.php");
        exit();
    } else {
        // Handle insert error
        echo "Insert error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Blog</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="./css/headerStyle.css">
    <link rel="stylesheet" href="./css/addContentStyle.css">
    <link rel="stylesheet" href="./css/footerStyle.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <h1>Add New Blog</h1>
        <form action="add_content.php" method="POST">
            <input type="text" name="title" placeholder="Title" required><br>
            <textarea name="body" placeholder="Content" rows="10" required></textarea><br>
            <input type="hidden" name="author" value="<?php echo $_SESSION['username']; ?>">
            <button type="submit" name="submit">Add Blog</button>
        </form>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
