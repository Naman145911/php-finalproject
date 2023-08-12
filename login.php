<?php
// login.php
include 'database.php';

// Initialize error messages array
$errors = array();

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Get form data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if all fields are filled
    if (empty($username) || empty($password)) {
        $errors[] = "All fields are required.";
    } else {
        // Check if username exists
        $sql = "SELECT * FROM admin WHERE username=?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            $errors[] = "Database error.";
        } else {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);
            if (!$row || !password_verify($password, $row['password'])) {
                $errors[] = "Invalid username or password.";
            } else {
                // Start session and store user data if login is successful
                session_start();
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];

                // Redirect to dashboard or desired page with a success message
                header("Location: dashboard.php?login=success");
                exit();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="./css/headerStyle.css">
    <link rel="stylesheet" href="./css/RegisterStyle.css">

</head>

<body>
    <?php include 'header.php'?>
    <div class="container">
        <h1>Login</h1>
        <?php
        if (!empty($errors)) {
            echo '<div class="error-box">';
            foreach ($errors as $error) {
                echo '<p>' . $error . '</p>';
            }
            echo '</div>';
        }
        ?>
        <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="Username"><br>
            <input type="password" name="password" placeholder="Password"><br>
            <button type="submit" name="submit">Login</button>
        </form>
    </div>

</body>

</html>
