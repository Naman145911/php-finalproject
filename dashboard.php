<?php
// dashboard.php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Include your database connection
include 'database.php';

// Retrieve user data from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM admin WHERE id=?";
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
} else {
    // Handle database error
    echo "Database error: " . mysqli_error($conn);
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="./css/headerStyle.css">
    <link rel="stylesheet" href="./css/dashboardStyle.css">
    <link rel="stylesheet" href="./css/footerStyle.css">
    <link rel="stylesheet" href="./css/dashboard.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <h1>Welcome to Your Dashboard, <?php echo $_SESSION['username']; ?>!</h1>
        <div class="user-info">
            <!-- Display user's profile image -->
            <div class="profile-image">
                <img src="<?php echo $user['image']; ?>" alt="Profile Image">
            </div>
            <p><strong>User ID:</strong> <?php echo $user['id']; ?></p>
            <p><strong>Username:</strong> <?php echo $user['username']; ?></p>
            <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
        </div>
        <div class="dashboard-buttons">
            <a href="user_management.php">Show All Users</a>
            <a href="show_content.php">Show Content</a>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
